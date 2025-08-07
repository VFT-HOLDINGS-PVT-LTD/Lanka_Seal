<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Style;


class Attendance_Manual_Entry extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Attendance Manual Entry | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_grp'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_set_att'] = $this->Db_model->getfilteredData("SELECT `M_ID`,`EmpNo`,`Emp_Full_Name`,`Att_Date`,`In_Time`,`tbl_manual_entry`.`Status`,`Reason` from tbl_manual_entry inner join tbl_empmaster on tbl_empmaster.EmpNo = tbl_manual_entry.Enroll_No WHERE Is_Admin_App_ID=1 order by M_ID desc");


        $this->load->view('Attendance/Attendance_Manual_Entry/index', $data);
    }

    public function dropdown()
    {

        $cat = $this->input->post('cmb_cat');

        if ($cat == "Employee") {
            $query = $this->Db_model->get_dropdown();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {

                echo "<option value='" . $row->EmpNo . "'>" . $row->Emp_Full_Name . "</option>";
            }
        }

        if ($cat == "Department") {
            $query = $this->Db_model->get_dropdown_dep();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Dep_ID . "'>" . $row->Dep_Name . "</option>";
            }
        }
        if ($cat == "Designation") {
            $query = $this->Db_model->get_dropdown_des();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Des_ID . "'>" . $row->Desig_Name . "</option>";
            }
        }
        if ($cat == "Employee_Group") {
            $query = $this->Db_model->get_dropdown_group();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Grp_ID . "'>" . $row->EmpGroupName . "</option>";
            }
        }

        if ($cat == "Company") {
            $query = $this->Db_model->get_dropdown_comp();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Cmp_ID . "'>" . $row->Company_Name . "</option>";
            }
        }
    }

    /*
     * Search Employee Manual Attendance Entry
     */

    public function emp_manual_entry()
    {


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $comp = $this->input->post("cmb_comp");

        $att_date = $this->input->post("att_date");
        $in_time = $this->input->post("in_time");
        // $out_time = $this->input->post("out_time");
        $out_time = "00:00:00";
        $reason = $this->input->post("txt_reason");
        $satus = $this->input->post('employee_status');
        $App_Sup_User = 1;
        $Is_App_Sup_User = 1;

        if ($satus == 'Active') {
            $st = "0";
        }
        // else{
        //     $st = "1";
        // }
        if ($satus == 'Inactive') {
            $st = "1";
        }
        $EmpData = $this->Db_model->getfilteredData("select EmpNo,Enroll_No from tbl_empmaster where EmpNo ='$emp' or Emp_Full_Name='$emp_name' ");

        $EnrollNo = $EmpData[0]->Enroll_No;

        $data = array(
            'Att_Date' => $att_date,
            'In_Time' => $in_time,
            'Out_Time' => $out_time,
            'Enroll_No' => $EnrollNo,
            'Reason' => $reason,
            'Status' => $st,
            // 'App_Sup_User' => 1,
            // 'Is_App_Sup_User' => 1,
            // 'App_Sup_User' => $App_Sup_User,
            // 'Is_App_Sup_User' => $Is_App_Sup_User
        );

        $this->Db_model->insertData('tbl_manual_entry', $data);


        // $data = array(
        //     'AttDate' => $att_date,
        //     'AttTime' => $in_time,
        //     'AttDateTimeStr' => "0000-00-00 00:00:00",
        //     'Enroll_No' => $EnrollNo,
        //     'AttPlace' => "null",
        //     'Status' => $st,
        //     'verify_type' => "0",
        //     'EventName' => "null",
        // );

        // $this->Db_model->insertData('tbl_u_attendancedata', $data);
        $this->session->set_flashdata('success_message', 'Manual Entry added successfully');

        redirect(base_url() . "Attendance/Attendance_Manual_Entry");
    }

    public function download_sample()
    {


        // echo "hello";
        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");

        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  iro.FDate between '$from_date' and '$to_date' AND Emp.Status = '1'";
            } else {
                $filter .= " AND  iro.FDate between '$from_date' and '$to_date' AND Emp.Status = '1'";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where ir.Enroll_No =$emp";
            } else {
                $filter .= " AND ir.Enroll_No =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where Emp.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where dsg.Des_ID  ='$desig'";
            } else {
                $filter .= " AND dsg.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where dep.Dep_id  ='$dept'";
            } else {
                $filter .= " AND dep.Dep_id  ='$dept'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where br.B_id  ='$branch'";
            } else {
                $filter .= " AND br.B_id  ='$branch'";
            }
        }

        $data_s = $this->Db_model->getfilteredData("SELECT 
        Emp.Emp_Full_Name,
        Emp.Status,
        iro.EmpNo,
        iro.InTime,
        iro.OutTime,
        iro.DayStatus,
        iro.FDate,
        iro.TDate
    FROM
        tbl_individual_roster iro
            LEFT JOIN
        tbl_empmaster Emp ON Emp.EmpNo = iro.EmpNo
            LEFT JOIN
        tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
            LEFT JOIN
        tbl_departments dep ON dep.Dep_id = Emp.Dep_id
            INNER JOIN
        tbl_branches br ON Emp.B_id = br.B_id
               
         
        {$filter} AND DayStatus='MS' ORDER BY Emp.EmpNo,iro.FDate ASC");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'F') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }
        $sheet->setCellValue('A1', 'EMP NO'); // ID
        $sheet->setCellValue('B1', 'NAME'); // Full Name
        $sheet->setCellValue('C1', 'DATE'); // Desig_Name
        $sheet->setCellValue('D1', 'IN TIME'); // Dep_Name
        $sheet->setCellValue('E1', 'OUT TIME'); // AttDate
        $sheet->setCellValue('F1', 'MISSING TIME'); // InTime
        $sheet->setCellValue('G1', 'STATUS'); // InTime
        // $sheet->setCellValue('G1', 'Double OT'); // OutTime

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);


        $x = 2;
        $st = 0;
        foreach ($data_s as $row) {

            $sheet->setCellValue('A' . $x, $row->EmpNo);

            $sheet->setCellValue('B' . $x, $row->Emp_Full_Name);


            $sheet->setCellValue('C' . $x, $row->FDate);
            if ($row->InTime != '00:00:00') {
                $st = 1;
            } else {
                $st = 0;
            }

            $inTimeExcel = Date::PHPToExcel(new DateTime($row->InTime));
            $outTimeExcel = Date::PHPToExcel(new DateTime($row->OutTime));

            
            $sheet->setCellValue('D' . $x, $inTimeExcel);
            $sheet->setCellValue('E' . $x, $outTimeExcel);

            
            $sheet->getStyle('D' . $x)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);
            $sheet->getStyle('E' . $x)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);

            $sheet->setCellValue('F' . $x, '');
            $sheet->setCellValue('G' . $x, $st);



            // $sheet->setCellValue('G' . $x, $doubleot);

            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Cm_international_miss_punch.xlsx';

        
        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        // redirect(base_url() . "Attendance/Attendance_Manual_Entry");
    }
    function uploadDoc()
    {

        ////excel file upload
        $uploadPath = 'assets/uploads/imports/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, TRUE); // FOR CREATING DIRECTORY IF ITS NOT EXIST
        }

        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'csv|xlsx|xls';
        $config['max_size'] = 1000000;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_excel')) {
            $fileData = $this->upload->data();
            $data['file_name'] = $fileData['file_name'];
            // $this->db->insert('excel_file', $data);
            // $insert_id = $this->db->insert_id();
            // $_SESSION['lastid'] = $insert_id;

            return $fileData['file_name'];
        } else {
            return false;
        }
        
    }
    function upload_sample()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $upload_status = $this->uploadDoc();
            if ($upload_status != false) {
                $inputFileName = 'assets/uploads/imports/' . $upload_status;
                $inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getSheet(0);
                $count_Rows = 0;
                //print_r($sheet->getRowIterator());
                //die;
                $counter = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($counter++ == 0) continue;
                    $Emp_no = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getValue();
                    $Name = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getValue();
                    $Date = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getValue();
                    $In_time = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getValue();
                    $Out_time = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getValue();
                    $Missing_time = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getValue();
                    $Status = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getValue();
                    if (is_numeric($Missing_time)) {
                        $formatted_time = Date::excelToDateTimeObject($Missing_time)->format('H:i:s');
                    } else {
                        $formatted_time = $Missing_time; 
                    }
                    // echo $formatted_time;
                    // echo "<br/>";
                    $data = array(
                        'Enroll_No' => $Emp_no,
                        'Att_Date' => $Date,
                        'In_Time' => $formatted_time,
                        'Status' => $Status,

                    );


                    $HasRow_manual_entry = $this->Db_model->getfilteredData("SELECT tbl_manual_entry.M_ID FROM tbl_manual_entry WHERE 
                                                                tbl_manual_entry.Att_Date = '$Date' AND tbl_manual_entry.Enroll_No = '$Emp_no'
                                                                AND tbl_manual_entry.`Status` = '$Status'");

                    if (!empty($HasRow_manual_entry[0]->M_ID)) {
                        // echo "Already Exist";
                        $this->db->where('M_ID', $HasRow_manual_entry[0]->M_ID);
                        $this->db->update('tbl_manual_entry', $data);
                    } else {
                        // echo "Not Exist";
                        $this->db->insert('tbl_manual_entry', $data);
                    }

                    // $this->db->insert('client', $data);
                    $count_Rows++;
                    // echo json_encode($data);
                    // echo "<br/>";
                }

                $this->session->set_flashdata('success_message', 'Upload Successfully');
                redirect(base_url() . "Attendance/Attendance_Manual_Entry");
            } else {
                $this->session->set_flashdata('error_message', 'Upload Failed');
                redirect(base_url() . "Attendance/Attendance_Manual_Entry");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Upload Failed');
            redirect(base_url() . "Attendance/Attendance_Manual_Entry");
        }
    }
}