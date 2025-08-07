<?php

defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Weekly_Roster_Excel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        if (!$this->session->userdata('login_user')) {
            redirect(base_url());
        }
        $this->load->model('Db_model', '', true);
    }

    public function index()
    {
        $data['title'] = "Weekly Roster Pattern Excel | HRM System";
        $data['data_set_shift'] = $this->Db_model->getfilteredData(
            "SELECT ShiftCode,ShiftName,FromTime,ToTime,NextDay,DayType,FHDSessionEndTime,SHDSessionStartTime,ShiftGap FROM tbl_shifts WHERE ShiftCode > '165';"
        );
        $data['data_set'] = $this->Db_model->getfilteredData(
            "SELECT RosterCode,RosterName FROM tbl_rosterpatternweeklyhd WHERE RosterCode > 'RS0594';"
        );
        $serialdata = $this->Db_model->getData('serial', 'tbl_serials', ['code' => 'Roster']);
        $serial = "RS" . str_pad((int)$serialdata[0]->serial + 1, 4, "0", STR_PAD_LEFT);
        $data['serial'] = $serial;

        $this->load->view('Master/Weekly_Roster_Excel/index', $data);
    }

    // Generates an Excel file based on the provided roster code, month type, and category.
    public function download_excel()
    {
        $roster_code = $this->input->post('txtRoster_Code');
        $month_type = $this->input->post('txt_MType');
        $category = $this->input->post('cmb_cat');
        if ($category === 'Individual Employee') {
            $roster_name = $this->input->post('txt_nic');
        } else {
            $roster_name = $this->input->post('txtRoster_Name');
        }

        try {
            $result = $this->Db_model->getfilteredData("SELECT MAX(ID) as newID FROM tbl_rosterpatternweeklydtl_monthly");
            $startId = (isset($result[0]->newID) ? $result[0]->newID : 0) + 1;

            $year = date('Y', strtotime($month_type));
            $month = date('m', strtotime($month_type));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Fetch shift codes once
            $shiftRows = $this->Db_model->getfilteredData("SELECT ShiftCode FROM tbl_shifts WHERE ShiftCode > '165';");
            $shiftCodes = array_column($shiftRows, 'ShiftCode');
            $shiftCodesEnum = '"' . implode(',', $shiftCodes) . '"';
            $defaultShiftCode = $shiftCodes[0] ?? '';

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $headers = ['ID', 'RosterCode', 'RosterName', 'ShiftCode', 'DayName', 'ShiftType', 'Date'];
            $sheet->fromArray($headers, null, 'A1');

            for ($i = 1, $row = 2, $id = $startId; $i <= $daysInMonth; $i++, $row++, $id++) {
                $dateString = sprintf('%04d-%02d-%02d', $year, $month, $i);
                $dayName = date('l', strtotime($dateString));

                $sheet->fromArray([
                    $id,
                    $roster_code,
                    $roster_name,
                    $defaultShiftCode,
                    $dayName,
                    '',
                    $dateString
                ], null, 'A' . $row);

                // Data validation for ShiftCode (D)
                $sheet->getCell('D' . $row)->getDataValidation()
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                    ->setAllowBlank(true)
                    ->setShowDropDown(true)
                    ->setFormula1($shiftCodesEnum);

                // Data validation for ShiftType (F)
                $sheet->getCell('F' . $row)->getDataValidation()
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                    ->setAllowBlank(true)
                    ->setShowDropDown(true)
                    ->setFormula1('"DU,EX,OFF"');
            }

            $filename = 'Roster_' . $roster_name . '_' . $month_type . '.xlsx';

            // Set flashdata for success before output
            $this->session->set_flashdata('success', "Excel file generated and downloaded successfully.");

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');
            (new Xlsx($spreadsheet))->save('php://output');
            exit;
        } catch (\Exception $e) {
            // Set flash message for error
            $this->session->set_flashdata('error', 'Error generating Excel file: ' . $e->getMessage());
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

// Handles Excel file upload and imports data into the database.
public function upload_excel()
{
    $uploadPath = 'assets/uploads/imports/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $dateTimeSuffix = date('Ymd_His');
    $originalFileName = $_FILES['upload_excel']['name'] ?? '';
    $fileExt = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
    $newFileName = $baseName . '_' . $dateTimeSuffix . '.' . $fileExt;

    $config = [
        'upload_path'   => $uploadPath,
        'allowed_types' => 'csv|xlsx|xls',
        'max_size'      => 1000000,
        'file_name'     => $newFileName
    ];
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('upload_excel')) {
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('Master/Weekly_Roster_Excel');
        return;
    }

    $fileData = $this->upload->data();
    $filePath = $uploadPath . $fileData['file_name'];

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    } catch (\Exception $e) {
        $this->session->set_flashdata('error', 'Failed to read Excel file: ' . $e->getMessage());
        redirect('Master/Weekly_Roster_Excel');
        return;
    }

    $newRows = [];
    $conflicts = [];

    foreach ($sheetData as $row) {
        // Skip header or invalid rows
        if (!isset($row['A']) || strtoupper(trim($row['A'])) === 'ID') continue;
        if (empty(trim($row['B'] ?? ''))) continue;

        $data = [
            'RosterCode' => trim($row['B']),
            'RosterName' => trim($row['C']),
            'ShiftCode'  => trim($row['D']),
            'DayName'    => trim($row['E']),
            'ShiftType'  => trim($row['F']),
            'Date'       => trim($row['G'])
        ];

        // Validate required fields
        if (empty($data['RosterCode']) || empty($data['RosterName']) || empty($data['Date'])) {
            // Skip invalid row (or log it)
            continue;
        }

        // Check if record exists by unique keys
        $where_arr = [
            'RosterCode' => $data['RosterCode'],
            'RosterName' => $data['RosterName'],
            'Date'       => $data['Date']
        ];

        $exists = $this->Db_model->getData2('tbl_rosterpatternweeklydtl_monthly', [], $where_arr);

        if ($exists) {
            $conflicts[] = $data;
        } else {
            $newRows[] = $data;
        }
    }

    if (!empty($conflicts)) {
        $this->session->set_flashdata('conflicts', $conflicts);
    }

    if (!empty($newRows)) {
        $this->session->set_flashdata('non_conflicts', $newRows);
    }

    redirect('Master/Weekly_Roster_Excel');
}

// Handles the insertion of non-conflicting records into the database.
public function insert_non_conflicts()
{
    $rows = json_decode($this->input->post('rows'), true);

    if (!is_array($rows) || empty($rows)) {
        $this->session->set_flashdata('error', 'No valid records to insert.');
        redirect('Master/Weekly_Roster_Excel');
        return;
    }

    foreach ($rows as $row) {
        if (!empty($row['RosterCode']) && !empty($row['RosterName']) && !empty($row['Date'])) {
            // Optional: Check again if row exists before insert to avoid duplicates
            $exists = $this->Db_model->getData2('tbl_rosterpatternweeklydtl_monthly', [], [
                'RosterCode' => $row['RosterCode'],
                'RosterName' => $row['RosterName'],
                'Date'       => $row['Date'],
            ]);
            if (!$exists) {
                $this->Db_model->insertData('tbl_rosterpatternweeklydtl_monthly', $row);
            }
        }
    }

    $this->updateRosterSerial();
    $this->saveRosterData($rows[0]['RosterCode'], $rows[0]['RosterName'], $rows[0]['Date']);

    $this->session->set_flashdata('success', 'New records inserted successfully.');
    redirect('Master/Weekly_Roster_Excel');
}

// Handles the replacement of conflicting records in the database.
public function replace_conflicts()
{
    $rows = json_decode($this->input->post('rows'), true);

    if (!is_array($rows) || empty($rows)) {
        $this->session->set_flashdata('error', 'Invalid conflict data.');
        redirect('Master/Weekly_Roster_Excel');
        return;
    }

    foreach ($rows as $row) {
        if (!empty($row['RosterCode']) && !empty($row['RosterName']) && !empty($row['Date'])) {
            $updateData = [
                'ShiftCode' => $row['ShiftCode'] ?? '',
                'DayName'   => $row['DayName'] ?? '',
                'ShiftType' => $row['ShiftType'] ?? ''
            ];

            $where = [
                'RosterCode' => $row['RosterCode'],
                'RosterName' => $row['RosterName'],
                'Date'       => $row['Date']
            ];

            $this->Db_model->updateData('tbl_rosterpatternweeklydtl_monthly', $updateData, $where);
        }
    }

    $this->session->set_flashdata('success', 'Conflicting records updated.');
    redirect('Master/Weekly_Roster_Excel');
}


    // update tbl_rosterpatternweeklyhd function

    private function saveRosterData($rosterCode, $rosterName, $date)
    {
        $monthType = date('F', strtotime($date));
        $currentYear = date('Y', strtotime($date));
        $dataField = (preg_match('/^\d+$/', $rosterName)) ? "Individual Employee" : "Only Group";

        $data = [
            'RosterCode'  => $rosterCode,
            'RosterName'  => $rosterName,
            'MonthType'   => $monthType,
            'CurrentYear' => $currentYear,
            'Data'        => $dataField
            ];

        //insert or update the roster header
        $this->Db_model->insertData('tbl_rosterpatternweeklyhd', $data);
            
    }

    // Updates the roster serial number in the database.
    private function updateRosterSerial()
    {
        try {
            $serialRow = $this->Db_model->getData('serial', 'tbl_serials', ['code' => 'Rs']);
            $currentSerial = isset($serialRow[0]->serial) ? (int)$serialRow[0]->serial : 0;
            $newSerial = $currentSerial + 1;

            $this->Db_model->updateData('tbl_serials', ['serial' => $newSerial], ['code' => 'Rs']);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error updating roster serial: ' . $e->getMessage());
            return false;
        }
    }
}
