<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Leave_Allocation extends CI_Controller {

    public function __construct() {
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

    public function index() {

        $data['title'] = "Leave Allocation | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_leave'] = $this->Db_model->getData('Lv_T_ID,leave_name,leave_entitle', 'tbl_leave_types');
        $this->load->view('Leave_Transaction/Leave_Allocation/index', $data);
    }

    /*
     * Get data
     */

    public function get_details() {
        $ShiftCode = $this->input->post('ShiftCode');

        $whereArray = array('ShiftCode' => $ShiftCode);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('ShiftCode,ShiftName,FromTime,ToTime,ShiftGap', 'tbl_shifts');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ShiftCode = $this->input->post("ShiftCode", TRUE);
        $ShiftName = $this->input->post("ShiftName", TRUE);
        $FromTime = $this->input->post("FromTime", TRUE);
        $ToTime = $this->input->post("ToTime", TRUE);
        $ShiftGap = $this->input->post("ShiftGap", TRUE);



        $data = array("ShiftName" => $ShiftName, "FromTime" => $FromTime, "ToTime" => $ToTime, "ShiftGap" => $ShiftGap,);
        $whereArr = array("ShiftCode" => $ShiftCode);
        $result = $this->Db_model->updateData("tbl_shifts", $data, $whereArr);
        redirect(base_url() . "Master/Shifts");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_shifts";
        $where = 'ShiftCode';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

    /*
     * Dependent Dropdown
     */

    public function dropdown() {

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

//        if ($cat == "Department") {
//            $query = $this->Db_model->get_dropdown_dep();
//            
//            echo"<select class='form-control' id='Dep' name='Dep'>";
//            foreach ($query->result() as $row) {
//                echo "<option value='" . $row->ID . "'>" . $row->Dep_Name . "</option>";
//            }
//            echo"</select>";
//        }
    }

    public function insert_data() {

        $cat = $this->input->post('cmb_cat');
        if ($cat == "Employee") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Department") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Dep_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Designation") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Des_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }
        if ($cat == "Employee_Group") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Grp_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Company") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        $leave_type = $this->input->post('cmb_leave_type');
        $year = $this->input->post('cmb_year');
        $entitle = $this->input->post('txt_entitle');
        date_default_timezone_set('Asia/Colombo');
        $date = date_create();
        $timestamp = date_format($date, 'Y-m-d H:i:s');

        $Emp = $EmpData[0]->EmpNo;

        $rusult = $this->Db_model->getfilteredData("select count(EmpNo) as IsAllcate from tbl_leave_allocation where EmpNo = '$Emp' and Year = '$year' and Lv_T_ID = '$leave_type' ");



        if ($rusult[0]->IsAllcate == 1) {
//            echo 'Already Allocated';
            $this->session->set_flashdata('error_message', 'Leave Already Allocated');
            redirect('/Leave_Transaction/Leave_Allocation/');
        } else {
            $Count = count($EmpData);

            for ($i = 0; $i < $Count; $i++) {
                $data = array(
                    array(
                        'EmpNo' => $EmpData[$i]->EmpNo,
                        'Lv_T_ID' => $leave_type,
                        'Entitle' => $entitle,
                        'Balance' => $entitle,
                        'Year' => $year,
                        'Trans_time' => $timestamp,
                ));

                $this->db->insert_batch('tbl_leave_allocation', $data);
            }
            $this->session->set_flashdata('success_message', 'Leave Allocated successfully');

            redirect(base_url() . 'Leave_Transaction/Leave_Allocation');
        }
    }

    /*
     * Create excell download report
    */

    public function download_leave_allocation_report() {

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT
                                                                ID,
                                                                EmpNo,
                                                                Year,
                                                                Lv_T_ID,
                                                                Entitle,
                                                                Used,
                                                                Balance
                                                            FROM tbl_leave_allocation");
        //var_dump($data['data_set']);die;

        //create excell sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'F') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'EmpNo');
        $sheet->setCellValue('C1', 'Year');
        $sheet->setCellValue('D1', 'Lv_T_ID');
        $sheet->setCellValue('E1', 'Entitle');
        $sheet->setCellValue('F1', 'Used');
        $sheet->setCellValue('G1', 'Balance');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

         //check data exists or not
         if (!empty($data['data_set'])) {
                
            $x = 2;

            foreach ($data['data_set'] as $row) {

                //set db value to columns
                $sheet->setCellValue('A' . $x, $row->ID);
                $sheet->setCellValue('B' . $x, $row->EmpNo);
                $sheet->setCellValue('C' . $x, $row->Year);
                $sheet->setCellValue('D' . $x, $row->Lv_T_ID);
                $sheet->setCellValue('E' . $x, $row->Entitle);
                $sheet->setCellValue('F' . $x, $row->Used);
                $sheet->setCellValue('G' . $x, $row->Balance);

                $x++;
            }
            
            

            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="leave_allocation_table.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error_message', 'No Data Found.');
            redirect(base_url() . "Leave_Transaction/Leave_Allocation/index");
        }
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
    
    function upload_leave_allocation_report()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            ////call this function heare
            $upload_status = $this->uploadDoc();

            if ($upload_status != false) {
                
                $inputFileName = 'assets/uploads/imports/' . $upload_status;
                $inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getSheet(0);
                $count_Rows = 0;

                // print_r($sheet->getRowIterator());
                // die;

                $counter = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($counter++ == 0) continue;
                    $ID = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getValue();
                    $EmpNo = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getValue();
                    $Year = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getValue();
                    $Lv_T_ID = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getValue();
                    $Entitle = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getValue();
                    $Used = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getValue();
                    $Balance = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getValue();

                    // echo $formatted_time;
                    // echo "<br/>";
                    $data = array(
                        'ID' => $ID,
                        'EmpNo' => $EmpNo,
                        'Year' => $Year,
                        'Lv_T_ID' => $Lv_T_ID,
                        'Entitle' => $Entitle,
                        'Used' => $Used,
                        'Balance' => $Balance,
                    );

                    $HasRow_leave_allocation = $this->Db_model->getfilteredData("SELECT tbl_leave_allocation.ID FROM tbl_leave_allocation WHERE
                                                                tbl_leave_allocation.ID = '$ID' ");
                    if (!empty($HasRow_leave_allocation[0]->ID)) {
                        // echo "Already Exist";
                        $this->db->where('ID', $HasRow_leave_allocation[0]->ID);
                        $this->db->update('tbl_leave_allocation', $data);
                    } else {
                        // echo "Not Exist";
                        $this->db->insert('tbl_leave_allocation', $data);
                    }
                    // $this->db->insert('client', $data);
                    $count_Rows++;
                    // echo json_encode($data);
                    // echo "<br/>";
                }
                $this->session->set_flashdata('success_message', 'Upload Successfully');
                redirect(base_url() . "Leave_Transaction/Leave_Allocation/index");
            } else {
                $this->session->set_flashdata('error_message', 'Upload Failed');
                redirect(base_url() . "Leave_Transaction/Leave_Allocation/index");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Upload Failed');
            redirect(base_url() . "Leave_Transaction/Leave_Allocation/index");
        }
    }

}
