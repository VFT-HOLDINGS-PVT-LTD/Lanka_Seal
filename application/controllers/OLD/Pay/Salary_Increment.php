<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Salary_Increment extends CI_Controller {

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

        $data['title'] = "Salary Increment | HRM SYSTEM";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_alw'] = $this->Db_model->getData('Alw_ID,Allowance_name', 'tbl_allowance_type');
        $this->load->view('Payroll/Salary_Increment/index', $data);
    }

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
    }

    public function insert_data() {

        $cat = $this->input->post('cmb_cat');
        if ($cat == "Employee") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$cat2'";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Department") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Dep_ID='$cat2'";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Designation") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Des_ID='$cat2'";
            $EmpData = $this->Db_model->getfilteredData($string);
        }
        if ($cat == "Employee_Group") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Grp_ID='$cat2'";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Company") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2'";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        $allowance = $this->input->post('cmb_allowance');
        $month = $this->input->post('cmb_month');
        $amount = $this->input->post('txt_amount');
        $year = date("Y");

        $Count = count($EmpData);
        $Emp = $EmpData[0]->EmpNo;



//        $IsAllowance = $this->Db_model->getfilteredData("select count(EmpNo) HasRow from tbl_varialble_allowance where EmpNo ='$Emp' and Alw_ID='$allowance' and month='$month' and Year = '$year' ");

        $BasicS = $this->Db_model->getfilteredData("select Basic_Salary from tbl_empmaster where EmpNo ='$Emp' ");

        $BasicSal = $BasicS[0]->Basic_Salary;


        $data = array(
            'EmpNo' => $Emp,
            'Old_Salary' => $BasicSal,
            'Increment_amount' => $amount,
            'New_Salary' => $BasicSal + $amount,
            'iYear' => $year,
            'iMonth' => $month
        );

        $result = $this->Db_model->insertData("tbl_salary_increments", $data);

        $data1 = array("Basic_Salary" => $BasicSal + $amount);
        $whereArr = array("EmpNo" => $Emp);
        $result = $this->Db_model->updateData("tbl_empmaster", $data1, $whereArr);

        $this->session->set_flashdata('success_message', 'Salary Increment added successfully');




        redirect(base_url() . "Pay/Salary_Increment");
    }

    /*
     * Get Allowances Details
     */

    public function getAllIncrements() {

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $month = $this->input->post("cmb_month");
        $cmb_year = $this->input->post("cmb_years");
        $cmb_allowance = $this->input->post("cmb_allowances");



        // Filter Data by categories
        $filter = '';

        if (($this->input->post("cmb_allowances"))) {
            if ($filter == '') {
                $filter = " where  si.Alw_ID =$cmb_allowance";
            } else {
                $filter .= " AND  v_alw.Alw_ID =$cmb_allowance";
            }
        }

        if (($this->input->post("cmb_years"))) {
            if ($filter == '') {
                $filter = " where  si.Year =$cmb_year";
            } else {
                $filter .= " AND  si.Year =$cmb_year";
            }
        }

        if (($this->input->post("cmb_month"))) {
            if ($filter == '') {
                $filter = " where  si.Month =$month";
            } else {
                $filter .= " AND  si.Month =$month";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where si.EmpNo =$emp";
            } else {
                $filter .= " AND si.EmpNo =$emp";
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


        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
    si.SI_ID,
    Emp.EmpNo,
    Emp.Emp_Full_Name,
    dsg.Desig_Name,
    dep.Dep_Name,
    si.Old_Salary,
    si.New_Salary,
    si.Increment_amount,
    si.iYear,
    si.iMonth
FROM
    tbl_salary_increments si
        INNER JOIN
    tbl_empmaster Emp ON Emp.EmpNo = si.EmpNo
        LEFT JOIN
    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
        LEFT JOIN
    tbl_departments dep ON dep.Dep_id = Emp.Dep_id


                                                                    {$filter}");



        $this->load->view('Payroll/Salary_Increment/search_data', $data);
    }

    /*
     * Get data
     */

    public function get_details() {
        $id = $this->input->post('id');

        $dataObject = $this->Db_model->getfilteredData("SELECT 
                                                                    v_alw.ID,
                                                                    v_alw.EmpNo,
                                                                    v_alw.Alw_ID,
                                                                    v_alw.Amount,
                                                                    v_alw.Year,
                                                                    v_alw.Month,
                                                                    Emp.Emp_Full_Name,
                                                                    dsg.Desig_Name,
                                                                    dep.Dep_Name,
                                                                    alw_typ.Allowance_name
                                                                FROM
                                                                    tbl_varialble_allowance v_alw
                                                                        INNER JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = v_alw.EmpNo
                                                                        LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                        LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                        LEFT JOIN
                                                                    tbl_allowance_type alw_typ ON alw_typ.Alw_ID = v_alw.Alw_ID where v_alw.ID=$id
                                                                    ");

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $Name = $this->input->post("Name", TRUE);
        $allowance = $this->input->post("allowance", TRUE);
        $amount = $this->input->post("amount", TRUE);

        $data = array("Amount" => $amount);
        $whereArr = array("ID" => $ID);
        $result = $this->Db_model->updateData("tbl_varialble_allowance", $data, $whereArr);

        $this->session->set_flashdata('success_message', 'Allowance edit successfully');

        redirect(base_url() . "Pay/Allowance");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_varialble_allowance";
        $where = 'ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

    /*
     * Create excell download report
     */
    public function download_salary_increment_report() {

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT
                                                                SI_ID,
                                                                EmpNo,
                                                                Old_Salary,
                                                                Increment_amount,
                                                                New_Salary,
                                                                iYear,
                                                                iMonth 
                                                            FROM tbl_salary_increments");
        //var_dump($data['data_set']);die;

        //create excell sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'F') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }
        $sheet->setCellValue('A1', 'SI_ID');
        $sheet->setCellValue('B1', 'EmpNo');
        $sheet->setCellValue('C1', 'Old_Salary');
        $sheet->setCellValue('D1', 'Increment_amount');
        $sheet->setCellValue('E1', 'New_Salary');
        $sheet->setCellValue('F1', 'iYear');
        $sheet->setCellValue('G1', 'iMonth');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

         //check data exists or not
         if (!empty($data['data_set'])) {
                
            $x = 2;

            foreach ($data['data_set'] as $row) {

                //set db value to columns
                $sheet->setCellValue('A' . $x, $row->SI_ID);
                $sheet->setCellValue('B' . $x, $row->EmpNo);
                $sheet->setCellValue('C' . $x, $row->Old_Salary);
                $sheet->setCellValue('D' . $x, $row->Increment_amount);
                $sheet->setCellValue('E' . $x, $row->New_Salary);
                $sheet->setCellValue('F' . $x, $row->iYear);
                $sheet->setCellValue('G' . $x, $row->iMonth);

                $x++;
            }
            
            

            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="salary_increment_table.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error_message', 'No Data Found.');
            redirect(base_url() . "Pay/Salary_Increment");
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
    
    function upload_salary_increment_report()
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
                    $SI_ID = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getValue();
                    $EmpNo = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getValue();
                    $Old_Salary = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getValue();
                    $Increment_amount = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getValue();
                    $New_Salary = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getValue();
                    $iYear = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getValue();
                    $iMonth = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getValue();

                    // echo $formatted_time;
                    // echo "<br/>";
                    $data = array(
                        'SI_ID' => $SI_ID,
                        'EmpNo' => $EmpNo,
                        'Old_Salary' => $Old_Salary,
                        'Increment_amount' => $Increment_amount,
                        'New_Salary' => $New_Salary,
                        'iYear' => $iYear,
                        'iMonth' => $iMonth,
                    );

                    $HasRow_salary_increment = $this->Db_model->getfilteredData("SELECT tbl_salary_increments.SI_ID FROM tbl_salary_increments WHERE
                                                                tbl_salary_increments.SI_ID = '$SI_ID' ");
                    if (!empty($HasRow_salary_increment[0]->SI_ID)) {
                        // echo "Already Exist";
                        $this->db->where('SI_ID', $HasRow_salary_increment[0]->SI_ID);
                        $this->db->update('tbl_salary_increments', $data);
                    } else {
                        // echo "Not Exist";
                        $this->db->insert('tbl_salary_increments', $data);
                    }
                    // $this->db->insert('client', $data);
                    $count_Rows++;
                    // echo json_encode($data);
                    // echo "<br/>";
                }
                $this->session->set_flashdata('success_message', 'Upload Successfully');
                redirect(base_url() . "Pay/Salary_Increment");
            } else {
                $this->session->set_flashdata('error_message', 'Upload Failed');
                 redirect(base_url() . "Pay/Salary_Increment");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Upload Failed');
            redirect(base_url() . "Pay/Salary_Increment");
        }
    }

}
