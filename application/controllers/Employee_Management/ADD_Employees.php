<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ADD_Employees extends CI_Controller
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
        $this->load->library('form_validation');
    }

    public function index()
    {


        $data['title'] = "ADD Employees | HRM SYSTEM";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_grp'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_u_lvl'] = $this->Db_model->getData('user_level_id,user_level_name', 'tbl_user_level_master');
        $data['data_Rstr'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');
        $data['data_ot'] = $this->Db_model->getData('OTCode,OTName', 'tbl_ot_pattern_hd');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['data_bank'] = $this->Db_model->getData('Bnk_ID,bank_name', 'tbl_banks');
        $data['data_epf'] = $this->Db_model->getData('EPF_CAT,EPF_CAT_Name', 'tbl_epf_cat');
        $data['data_status'] = $this->Db_model->getData('EMP_ST_ID,EMP_ST_Name', 'tbl_emp_status');
        $this->load->view('Employee_Management/ADD_Employees/index', $data);
    }

    public function check_emp()
    {
        //get the username  
        $EmpNo = $this->input->post('txt_emp_no');

        $result = $this->Db_model->getfilteredData("select count(EmpNo) as EmpNo from tbl_empmaster where EmpNo = '$EmpNo' ");


        //if number of rows fields is bigger them 0 that means it's NOT available '  
        if ($result[0]->EmpNo == 0) {

            echo 0;
        } else {
            //else if it's not bigger then 0, then it's available '  
            //and we send 1 to the ajax request  
            echo 1;
        }
    }
    public function getBranchesByBank() {
        $bank_id = $this->input->post('bank_id');
        $branches = $this->Db_model->getfilteredData("SELECT * FROM tbl_bank_branches WHERE tbl_bank_branches.Bank_Id = '$bank_id' "); // Replace with your model method to fetch branches
    
        echo json_encode($branches);
    }

    //***** INsert Employee
    public function insert_Data()
    {

        $Emp_No = $this->input->post('txt_emp_no');

        $Image = md5($Emp_No);



        $config['upload_path'] = 'assets/images/Employees/';
        $config['allowed_types'] = 'jpg|png|docx';
        $config['max_size'] = 100000;
        $config['max_width'] = 4000;
        $config['max_height'] = 4000;
        //      $config['file_name'] = $Image;
        $config['file_name'] = $Image . ".jpg";
        $this->load->library('upload', $config);

        echo $this->input->post('cmb_if_epf');

        /*
         * 'image'  selected image id,name
         */
        if (!$this->upload->do_upload('img_employee')) {
            $error = array('error' => $this->upload->display_errors());

            //            var_dump($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            //            var_dump($data);
        }

        $Password = $this->input->post('txt_nic');


        $Is_Allow = $this->input->post('Is_Allow');
        if ($Is_Allow == null) {
            $Is_Allow = 1;
        } else {
            $Is_Allow = 1;
        }

        $Is_EPF = $this->input->post('cmb_if_epf');
        if ($Is_EPF == null) {
            $Is_EPF = 0;
        }
        // $this->form_validation->set_rules('txt_emp_no', 'Employee Number', 'required|alpha_numeric');
        // $this->form_validation->set_rules('txt_enroll_no', 'Enrollment Number', 'required|alpha_numeric');
        // $this->form_validation->set_rules('txt_epf_no', 'EPF Number', 'required|alpha_numeric');
        // $this->form_validation->set_rules('cmb_epf_cat', 'EPF Category', 'required');
        // $this->form_validation->set_rules('txt_ocp_code', 'Occupation Code', 'required|alpha_numeric');
        // $this->form_validation->set_rules('cmb_emp_status', 'Employee Status', 'required');
        // $this->form_validation->set_rules('cmb_emp_title', 'Title', 'required');
        // $this->form_validation->set_rules('txt_emp_name', 'Full Name', 'required');
        // $this->form_validation->set_rules('txt_emp_name_init', 'Name with Initials', 'required');
        // $this->form_validation->set_rules('txt_basic_sal', 'Basic Salary', 'required|numeric');
        // $this->form_validation->set_rules('cmb_bank', 'Bank', 'required');
        // $this->form_validation->set_rules('txt_B_Branch', 'Bank Branch', 'required');
        // $this->form_validation->set_rules('txt_account', 'Account Number', 'required|numeric');
        // $this->form_validation->set_rules('txt_address', 'Address', 'required');
        // $this->form_validation->set_rules('cmb_district', 'District', 'required');
        // $this->form_validation->set_rules('txt_city', 'City', 'required');
        // $this->form_validation->set_rules('txt_cont_home', 'Home Contact Number', 'required|numeric');
        // $this->form_validation->set_rules('txt_cont_mobile', 'Mobile Contact Number', 'required|numeric');
        // $this->form_validation->set_rules('txt_email', 'Email', 'required|valid_email');
        // $this->form_validation->set_rules('txt_nic', 'NIC', 'required|alpha_numeric');
        // $this->form_validation->set_rules('txt_passport', 'Passport', 'required|alpha_numeric');
        // $this->form_validation->set_rules('txt_dob', 'Date of Birth', 'required|valid_date');
        // $this->form_validation->set_rules('cmb_religin', 'Religion', 'required');
        // $this->form_validation->set_rules('cmb_civil_status', 'Civil Status', 'required');
        // $this->form_validation->set_rules('cmb_blood', 'Blood Group', 'required');
        // $this->form_validation->set_rules('txt_rel_name', 'Relative Name', 'required');
        // $this->form_validation->set_rules('txt_rel_cont', 'Relative Contact Number', 'required|numeric');
        // $this->form_validation->set_rules('txt_no_child', 'Number of Children', 'required|numeric');
        // $this->form_validation->set_rules('txt_user_name', 'Username', 'required|alpha_numeric');
        // $this->form_validation->set_rules('Password', 'Password', 'required');
        // $this->form_validation->set_rules('cmb_user_level', 'User Level', 'required');

        // if ($this->form_validation->run() == FALSE) {
        //     // Validation failed
        //     $errors = validation_errors();
        //     $this->session->set_flashdata('error_message', $errors);
        //     // Handle errors (e.g., display errors to the user)
        // } else {
            $data = array(
                'EmpNo' => $this->input->post('txt_emp_no'),
                'Enroll_No' => $this->input->post('txt_enroll_no'),
                'EPFNO' => $this->input->post('txt_epf_no'),
                'EPF_CAT' => '0',
                // 'Is_EPF' =>$this->input->post('cmb_if_epf'),
                'OCP_Code' => $this->input->post('txt_ocp_code'),
                'EMP_ST_ID' => $this->input->post('cmb_emp_status'),
                'Title' => $this->input->post('cmb_emp_title'),
                'Emp_Full_Name' => $this->input->post('txt_emp_name'),
                'Emp_Name_Int' => $this->input->post('txt_emp_name_init'),
                'Image' => $Image . ".jpg",
                'Gender' => $this->input->post('cmb_gender'),
                'Status' => 1,
                'Dep_ID' => $this->input->post('cmb_dep'),
                'Des_ID' => $this->input->post('cmb_desig'),
                'Grp_ID' => $this->input->post('cmb_group'),
                'RosterCode' => 'RS0001',
                'OTCode' => $this->input->post('cmb_ot_pattern'),
                'B_id' => $this->input->post('cmb_branch'),
                'BR1' => $this->input->post('txt_BG_Allowance1'),
                'BR2' => $this->input->post('txt_BG_Allowance2'),
                'ApointDate' => $this->input->post('txt_appoint_date'),
                'Permanent_Date' => $this->input->post('txt_permanent_date'),
                'Basic_Salary' => $this->input->post('txt_basic_sal'),
                'Incentive' => $this->input->post('txt_Incentive'),
                'Bnk_ID' => $this->input->post('cmb_bank'),
                'Bnk_Br_ID' => $this->input->post('txt_B_Branch'),
                'Account_no' => $this->input->post('txt_account'),
                'Is_EPF' => $Is_EPF,
                'Address' => $this->input->post('txt_address'),
                'District' => $this->input->post('cmb_district'),
                'City' => $this->input->post('txt_city'),
                'Tel_home' => $this->input->post('txt_cont_home'),
                'Tel_mobile' => $this->input->post('txt_cont_mobile'),
                'E_mail' => $this->input->post('txt_email'),
                'NIC' => $this->input->post('txt_nic'),
                'Passport' => $this->input->post('txt_passport'),
                'DOB' => $this->input->post('txt_dob'),
                'Religion' => $this->input->post('cmb_religin'),
                'Civil_status' => $this->input->post('cmb_civil_status'),
                'Blood_group' => $this->input->post('cmb_blood'),
                'Relations_name' => $this->input->post('txt_rel_name'),
                'Relations_Tel' => $this->input->post('txt_rel_cont'),
                'No_Of_Child' => $this->input->post('txt_no_child'),
                'Is_allow_login' => 1,
                'username' => $this->input->post('txt_user_name'),
                'Password' => hash('sha512', $Password),
                //            'user_p_id' => 2,
                'user_p_id' => $this->input->post('cmb_user_level'),
                'Cmp_ID' => 1,
                'Active_process' => 1,
                'leave_allocate_type'=>$this->input->post('cmb_leave_allocation_type'),
            );

            $setting_data=$this->Db_model->getfilteredData("SELECT employee_count FROM tbl_company_setting");
            $setting_emp_count=(int)$setting_data[0]->employee_count;

            $current_data=$this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS employee_count FROM tbl_empmaster WHERE Status=1 AND EmpNo !=9000");
            $current_emp_count=(int)$current_data[0]->employee_count;

            if($setting_emp_count > $current_emp_count){
                $this->Db_model->insertData("tbl_empmaster", $data);
                $this->session->set_flashdata('success_message', 'Employee Successfully Added');
                redirect('/Employee_Management/ADD_Employees/');
            }else{
                $this->session->set_flashdata('error_message', 'Employee Limit Exceeded.');
                redirect('/Employee_Management/ADD_Employees/');
            }
           
        // }
       
    }

     /*
    * Download employee report excell sheet
    */

     public function download_emp_report() {

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    EmpNo,
                                                                    Enroll_No,
                                                                    EPFNO,
                                                                    EPF_CAT,
                                                                    EMP_ST_ID,
                                                                    OCP_Code,
                                                                    Title,
                                                                    Emp_Full_Name,
                                                                    Emp_Name_Int,
                                                                    Image,
                                                                    Gender,
                                                                    Status,
                                                                    Is_Casual,
                                                                    Dep_ID,
                                                                    Des_ID,
                                                                    Grp_ID,
                                                                    RosterCode,
                                                                    OTCode,
                                                                    B_id,
                                                                    ApointDate,
                                                                    Permanent_Date,
                                                                    ResignDate,
                                                                    Basic_Salary,
                                                                    Fixed_Allowance,
                                                                    Incentive,
                                                                    Is_EPF,
                                                                    Address,
                                                                    District,
                                                                    City,
                                                                    Tel_home,
                                                                    Tel_mobile,
                                                                    E_mail,
                                                                    NIC,
                                                                    Passport,
                                                                    DOB,
                                                                    OT_Allow,
                                                                    Religion,
                                                                    Civil_status,
                                                                    Blood_group,
                                                                    Relations_name,
                                                                    Relations_Tel,
                                                                    No_Of_Child,
                                                                    Is_allow_login,
                                                                    username,
                                                                    password,
                                                                    user_p_id,
                                                                    IS_Fixed_allowance,
                                                                    Remarks,
                                                                    highlights,
                                                                    Cmp_ID,
                                                                    Trans_Date,
                                                                    Active_process,
                                                                    is_nopay_calc,
                                                                    BR1,
                                                                    BR2,
                                                                    leave_allocate_type
                                                                FROM tbl_empmaster;
                                                                ");
        //var_dump($data['data_set']);die;

        //create excell sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column auto size for all columns used
        foreach (range('A', 'BI') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }

        // Set header row according to the SELECT order
        $sheet->setCellValue('A1', 'EmpNo');
        $sheet->setCellValue('B1', 'Enroll_No');
        $sheet->setCellValue('C1', 'EPFNO');
        $sheet->setCellValue('D1', 'EPF_CAT');
        $sheet->setCellValue('E1', 'EMP_ST_ID');
        $sheet->setCellValue('F1', 'OCP_Code');
        $sheet->setCellValue('G1', 'Title');
        $sheet->setCellValue('H1', 'Emp_Full_Name');
        $sheet->setCellValue('I1', 'Emp_Name_Int');
        $sheet->setCellValue('J1', 'Image');
        $sheet->setCellValue('K1', 'Gender');
        $sheet->setCellValue('L1', 'Status');
        $sheet->setCellValue('M1', 'Is_Casual');
        $sheet->setCellValue('N1', 'Dep_ID');
        $sheet->setCellValue('O1', 'Des_ID');
        $sheet->setCellValue('P1', 'Grp_ID');
        $sheet->setCellValue('Q1', 'RosterCode');
        $sheet->setCellValue('R1', 'OTCode');
        $sheet->setCellValue('S1', 'B_id');
        $sheet->setCellValue('T1', 'ApointDate');
        $sheet->setCellValue('U1', 'Permanent_Date');
        $sheet->setCellValue('V1', 'ResignDate');
        $sheet->setCellValue('W1', 'Basic_Salary');
        $sheet->setCellValue('X1', 'Fixed_Allowance');
        $sheet->setCellValue('Y1', 'Incentive');
        $sheet->setCellValue('Z1', 'Is_EPF');
        $sheet->setCellValue('AA1', 'Address');
        $sheet->setCellValue('AB1', 'District');
        $sheet->setCellValue('AC1', 'City');
        $sheet->setCellValue('AD1', 'Tel_home');
        $sheet->setCellValue('AE1', 'Tel_mobile');
        $sheet->setCellValue('AF1', 'E_mail');
        $sheet->setCellValue('AG1', 'NIC');
        $sheet->setCellValue('AH1', 'Passport');
        $sheet->setCellValue('AI1', 'DOB');
        $sheet->setCellValue('AJ1', 'OT_Allow');
        $sheet->setCellValue('AK1', 'Religion');
        $sheet->setCellValue('AL1', 'Civil_status');
        $sheet->setCellValue('AM1', 'Blood_group');
        $sheet->setCellValue('AN1', 'Relations_name');
        $sheet->setCellValue('AO1', 'Relations_Tel');
        $sheet->setCellValue('AP1', 'No_Of_Child');
        $sheet->setCellValue('AQ1', 'Is_allow_login');
        $sheet->setCellValue('AR1', 'username');
        $sheet->setCellValue('AS1', 'password');
        $sheet->setCellValue('AT1', 'user_p_id');
        $sheet->setCellValue('AU1', 'IS_Fixed_allowance');
        $sheet->setCellValue('AV1', 'Remarks');
        $sheet->setCellValue('AW1', 'highlights');
        $sheet->setCellValue('AX1', 'Cmp_ID');
        $sheet->setCellValue('AY1', 'Trans_Date');
        $sheet->setCellValue('AZ1', 'Active_process');
        $sheet->setCellValue('BA1', 'is_nopay_calc');
        $sheet->setCellValue('BB1', 'BR1');
        $sheet->setCellValue('BC1', 'BR2');
        $sheet->setCellValue('BD1', 'leave_allocate_type');
        $sheet->getStyle('A1:BD1')->getFont()->setBold(true);

         //check data exists or not
         if (!empty($data['data_set'])) {
                
            $x = 2;

            foreach ($data['data_set'] as $row) {

                // Set db value to columns in the correct order
                $sheet->setCellValue('A' . $x, $row->EmpNo);
                $sheet->setCellValue('B' . $x, $row->Enroll_No);
                $sheet->setCellValue('C' . $x, $row->EPFNO);
                $sheet->setCellValue('D' . $x, $row->EPF_CAT);
                $sheet->setCellValue('E' . $x, $row->EMP_ST_ID);
                $sheet->setCellValue('F' . $x, $row->OCP_Code);
                $sheet->setCellValue('G' . $x, $row->Title);
                $sheet->setCellValue('H' . $x, $row->Emp_Full_Name);
                $sheet->setCellValue('I' . $x, $row->Emp_Name_Int);
                $sheet->setCellValue('J' . $x, $row->Image);
                $sheet->setCellValue('K' . $x, $row->Gender);
                $sheet->setCellValue('L' . $x, $row->Status);
                $sheet->setCellValue('M' . $x, $row->Is_Casual);
                $sheet->setCellValue('N' . $x, $row->Dep_ID);
                $sheet->setCellValue('O' . $x, $row->Des_ID);
                $sheet->setCellValue('P' . $x, $row->Grp_ID);
                $sheet->setCellValue('Q' . $x, $row->RosterCode);
                $sheet->setCellValue('R' . $x, $row->OTCode);
                $sheet->setCellValue('S' . $x, $row->B_id);
                $sheet->setCellValue('T' . $x, $row->ApointDate);
                $sheet->setCellValue('U' . $x, $row->Permanent_Date);
                $sheet->setCellValue('V' . $x, $row->ResignDate);
                $sheet->setCellValue('W' . $x, $row->Basic_Salary);
                $sheet->setCellValue('X' . $x, $row->Fixed_Allowance);
                $sheet->setCellValue('Y' . $x, $row->Incentive);
                $sheet->setCellValue('Z' . $x, $row->Is_EPF);
                $sheet->setCellValue('AA' . $x, $row->Address);
                $sheet->setCellValue('AB' . $x, $row->District);
                $sheet->setCellValue('AC' . $x, $row->City);
                $sheet->setCellValue('AD' . $x, $row->Tel_home);
                $sheet->setCellValue('AE' . $x, $row->Tel_mobile);
                $sheet->setCellValue('AF' . $x, $row->E_mail);
                $sheet->setCellValue('AG' . $x, $row->NIC);
                $sheet->setCellValue('AH' . $x, $row->Passport);
                $sheet->setCellValue('AI' . $x, $row->DOB);
                $sheet->setCellValue('AJ' . $x, $row->OT_Allow);
                $sheet->setCellValue('AK' . $x, $row->Religion);
                $sheet->setCellValue('AL' . $x, $row->Civil_status);
                $sheet->setCellValue('AM' . $x, $row->Blood_group);
                $sheet->setCellValue('AN' . $x, $row->Relations_name);
                $sheet->setCellValue('AO' . $x, $row->Relations_Tel);
                $sheet->setCellValue('AP' . $x, $row->No_Of_Child);
                $sheet->setCellValue('AQ' . $x, $row->Is_allow_login);
                $sheet->setCellValue('AR' . $x, $row->username);
                $sheet->setCellValue('AS' . $x, $row->password);
                $sheet->setCellValue('AT' . $x, $row->user_p_id);
                $sheet->setCellValue('AU' . $x, $row->IS_Fixed_allowance);
                $sheet->setCellValue('AV' . $x, $row->Remarks);
                $sheet->setCellValue('AW' . $x, $row->highlights);
                $sheet->setCellValue('AX' . $x, $row->Cmp_ID);
                $sheet->setCellValue('AY' . $x, $row->Trans_Date);
                $sheet->setCellValue('AZ' . $x, $row->Active_process);
                $sheet->setCellValue('BA' . $x, $row->is_nopay_calc);
                $sheet->setCellValue('BB' . $x, $row->BR1);
                $sheet->setCellValue('BC' . $x, $row->BR2);
                $sheet->setCellValue('BD' . $x, $row->leave_allocate_type);

                $x++;
            }
            
            

            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="employee_table.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error_message', 'No Data Found.');
            redirect(base_url() . "Employee_Management/View_Employee/index");
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

    /*
    * Upload employee report excell sheet
    */
    
    function upload_employee_report()
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
                    $EmpNo = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getValue();
                    $Enroll_No = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getValue();
                    $EPFNO = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getValue();
                    $EPF_CAT = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getValue();
                    $EMP_ST_ID = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getValue();
                    $OCP_Code = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getValue();
                    $Title = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getValue();
                    $Emp_Full_Name = $spreadsheet->getActiveSheet()->getCell('H' . $row->getRowIndex())->getValue();
                    $Emp_Name_Int = $spreadsheet->getActiveSheet()->getCell('I' . $row->getRowIndex())->getValue();
                    $Image = $spreadsheet->getActiveSheet()->getCell('J' . $row->getRowIndex())->getValue();
                    $Gender = $spreadsheet->getActiveSheet()->getCell('K' . $row->getRowIndex())->getValue();
                    $Status = $spreadsheet->getActiveSheet()->getCell('L' . $row->getRowIndex())->getValue();
                    $Is_Casual = $spreadsheet->getActiveSheet()->getCell('M' . $row->getRowIndex())->getValue();
                    $Dep_ID = $spreadsheet->getActiveSheet()->getCell('N' . $row->getRowIndex())->getValue();
                    $Des_ID = $spreadsheet->getActiveSheet()->getCell('O' . $row->getRowIndex())->getValue();
                    $Grp_ID = $spreadsheet->getActiveSheet()->getCell('P' . $row->getRowIndex())->getValue();
                    $RosterCode = $spreadsheet->getActiveSheet()->getCell('Q' . $row->getRowIndex())->getValue();
                    $OTCode = $spreadsheet->getActiveSheet()->getCell('R' . $row->getRowIndex())->getValue();
                    $B_id = $spreadsheet->getActiveSheet()->getCell('S' . $row->getRowIndex())->getValue();
                    $ApointDate = $spreadsheet->getActiveSheet()->getCell('T' . $row->getRowIndex())->getValue();
                    $Permanent_Date = $spreadsheet->getActiveSheet()->getCell('U' . $row->getRowIndex())->getValue();
                    $ResignDate = $spreadsheet->getActiveSheet()->getCell('V' . $row->getRowIndex())->getValue();
                    $Basic_Salary = $spreadsheet->getActiveSheet()->getCell('W' . $row->getRowIndex())->getValue();
                    $Fixed_Allowance = $spreadsheet->getActiveSheet()->getCell('X' . $row->getRowIndex())->getValue();
                    $Incentive = $spreadsheet->getActiveSheet()->getCell('Y' . $row->getRowIndex())->getValue();
                    $Is_EPF = $spreadsheet->getActiveSheet()->getCell('Z' . $row->getRowIndex())->getValue();
                    $Address = $spreadsheet->getActiveSheet()->getCell('AA' . $row->getRowIndex())->getValue();
                    $District = $spreadsheet->getActiveSheet()->getCell('AB' . $row->getRowIndex())->getValue();
                    $City = $spreadsheet->getActiveSheet()->getCell('AC' . $row->getRowIndex())->getValue();
                    $Tel_home = $spreadsheet->getActiveSheet()->getCell('AD' . $row->getRowIndex())->getValue();
                    $Tel_mobile = $spreadsheet->getActiveSheet()->getCell('AE' . $row->getRowIndex())->getValue();
                    $E_mail = $spreadsheet->getActiveSheet()->getCell('AF' . $row->getRowIndex())->getValue();
                    $NIC = $spreadsheet->getActiveSheet()->getCell('AG' . $row->getRowIndex())->getValue();
                    $Passport = $spreadsheet->getActiveSheet()->getCell('AH' . $row->getRowIndex())->getValue();
                    $DOB = $spreadsheet->getActiveSheet()->getCell('AI' . $row->getRowIndex())->getValue();
                    $OT_Allow = $spreadsheet->getActiveSheet()->getCell('AJ' . $row->getRowIndex())->getValue();
                    $Religion = $spreadsheet->getActiveSheet()->getCell('AK' . $row->getRowIndex())->getValue();
                    $Civil_status = $spreadsheet->getActiveSheet()->getCell('AL' . $row->getRowIndex())->getValue();
                    $Blood_group = $spreadsheet->getActiveSheet()->getCell('AM' . $row->getRowIndex())->getValue();
                    $Relations_name = $spreadsheet->getActiveSheet()->getCell('AN' . $row->getRowIndex())->getValue();
                    $Relations_Tel = $spreadsheet->getActiveSheet()->getCell('AO' . $row->getRowIndex())->getValue();
                    $No_Of_Child = $spreadsheet->getActiveSheet()->getCell('AP' . $row->getRowIndex())->getValue();
                    $Is_allow_login = $spreadsheet->getActiveSheet()->getCell('AQ' . $row->getRowIndex())->getValue();
                    $username = $spreadsheet->getActiveSheet()->getCell('AR' . $row->getRowIndex())->getValue();
                    $password = $spreadsheet->getActiveSheet()->getCell('AS' . $row->getRowIndex())->getValue();
                    $user_p_id = $spreadsheet->getActiveSheet()->getCell('AT' . $row->getRowIndex())->getValue();
                    $IS_Fixed_allowance = $spreadsheet->getActiveSheet()->getCell('AU' . $row->getRowIndex())->getValue();
                    $Remarks = $spreadsheet->getActiveSheet()->getCell('AV' . $row->getRowIndex())->getValue();
                    $highlights = $spreadsheet->getActiveSheet()->getCell('AW' . $row->getRowIndex())->getValue();
                    $Cmp_ID = $spreadsheet->getActiveSheet()->getCell('AX' . $row->getRowIndex())->getValue();
                    $Trans_Date = $spreadsheet->getActiveSheet()->getCell('AY' . $row->getRowIndex())->getValue();
                    $Active_process = $spreadsheet->getActiveSheet()->getCell('AZ' . $row->getRowIndex())->getValue();
                    $is_nopay_calc = $spreadsheet->getActiveSheet()->getCell('BA' . $row->getRowIndex())->getValue();
                    $BR1 = $spreadsheet->getActiveSheet()->getCell('BB' . $row->getRowIndex())->getValue();
                    $BR2 = $spreadsheet->getActiveSheet()->getCell('BC' . $row->getRowIndex())->getValue();
                    $leave_allocate_type = $spreadsheet->getActiveSheet()->getCell('BD' . $row->getRowIndex())->getValue();

                    // Convert DOB to the 
                    if (!empty($DOB)) {
                        $DOB = \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($spreadsheet->getActiveSheet()->getCell('AO' . $row->getRowIndex())) 
                            ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($DOB)) 
                            : date('Y-m-d', strtotime($DOB));
                    }
                    // Convert ResignDate, Permanent_Date,Trans_Date and ApointDate 
                    if (!empty($ResignDate)) {
                        $ResignDate = \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($spreadsheet->getActiveSheet()->getCell('V' . $row->getRowIndex())) 
                            ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ResignDate)) 
                            : date('Y-m-d', strtotime($ResignDate));
                    }

                    if (!empty($Permanent_Date)) {
                        $Permanent_Date = \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($spreadsheet->getActiveSheet()->getCell('U' . $row->getRowIndex())) 
                            ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($Permanent_Date)) 
                            : date('Y-m-d', strtotime($Permanent_Date));
                    }

                    if (!empty($ApointDate)) {
                        $ApointDate = \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($spreadsheet->getActiveSheet()->getCell('T' . $row->getRowIndex())) 
                            ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ApointDate)) 
                            : date('Y-m-d', strtotime($ApointDate));
                    }
 
                    if (!empty($Trans_Date)) {
                        $Trans_Date = \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($spreadsheet->getActiveSheet()->getCell('BE' . $row->getRowIndex())) 
                            ? date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($Trans_Date)) 
                            : date('Y-m-d', strtotime($Trans_Date));
                    }

                    //validate email
                    if (!filter_var($E_mail, FILTER_VALIDATE_EMAIL)) {
                        $this->session->set_flashdata('error_message', 'Invalid email format in Row ' . $row->getRowIndex());
                        redirect(base_url() . "Employee_Management/ADD_Employees/index");
                    }
                    // Validate NIC in Sri Lankan context
                    if (!preg_match('/^\d{9}[vVxX]|\d{12}$/', $NIC)) {
                        $this->session->set_flashdata('error_message', 'Invalid NIC format in Row ' . $row->getRowIndex());
                        redirect(base_url() . "Employee_Management/ADD_Employees/index");
                    }

                    // Prepend 0 if the number has only 9 digits
                    if (preg_match('/^[1-9][0-9]{8}$/', $Tel_mobile)) {
                        $Tel_mobile = '0' . $Tel_mobile;
                    }

                    // Now validate the full 10-digit number (mobile or landline)
                    if (!preg_match('/^(0(7\d{8}|[1-9]{2}\d{7}))$/', $Tel_mobile)) {
                        $this->session->set_flashdata('error_message', 'Invalid phone number format in Row ' . $row->getRowIndex());
                        redirect(base_url() . "Employee_Management/ADD_Employees/index");
                    }

                    // echo $formatted_time;
                    // echo "<br/>";
                    $data = array(
                        'EmpNo' => $EmpNo,
                        'Enroll_No' => $Enroll_No,
                        'EPFNO' => $EPFNO,
                        'EPF_CAT' => $EPF_CAT,
                        'EMP_ST_ID' => $EMP_ST_ID,
                        'OCP_Code' => $OCP_Code,
                        'Title' => $Title,
                        'Emp_Full_Name' => $Emp_Full_Name,
                        'Emp_Name_Int' => $Emp_Name_Int,
                        'Image' => $Image,
                        'Gender' => $Gender,
                        'Status' => $Status,
                        'Is_Casual' => $Is_Casual,
                        'Dep_ID' => $Dep_ID,
                        'Des_ID' => $Des_ID,
                        'Grp_ID' => $Grp_ID,
                        'RosterCode' => $RosterCode,
                        'OTCode' => $OTCode,
                        'B_id' => $B_id,
                        'ApointDate' => $ApointDate,
                        'Permanent_Date' => $Permanent_Date,
                        'ResignDate' => $ResignDate,
                        'Basic_Salary' => $Basic_Salary,
                        'Fixed_Allowance' => $Fixed_Allowance,
                        'Incentive' => $Incentive,
                        'Is_EPF' => $Is_EPF,
                        'Address' => $Address,
                        'District' => $District,
                        'City' => $City,
                        'Tel_home' => $Tel_home,
                        'Tel_mobile' => $Tel_mobile,
                        'E_mail' => $E_mail,
                        'NIC' => $NIC,
                        'Passport' => $Passport,
                        'DOB' => $DOB,
                        'OT_Allow' => $OT_Allow,
                        'Religion' => $Religion,
                        'Civil_status' => $Civil_status,
                        'Blood_group' => $Blood_group,
                        'Relations_name' => $Relations_name,
                        'Relations_Tel' => $Relations_Tel,
                        'No_Of_Child' => $No_Of_Child,
                        'Is_allow_login' => $Is_allow_login,
                        'username' => $username,
                        'password' => $password,
                        'user_p_id' => $user_p_id,
                        'IS_Fixed_allowance' => $IS_Fixed_allowance,
                        'Remarks' => $Remarks,
                        'highlights' => $highlights,
                        'Cmp_ID' => $Cmp_ID,
                        'Trans_Date' => $Trans_Date,
                        'Active_process' => $Active_process,
                        'is_nopay_calc' => $is_nopay_calc,
                        'BR1' => $BR1,
                        'BR2' => $BR2,
                        'leave_allocate_type' => $leave_allocate_type,

                    );

                    $HasRow_employee = $this->Db_model->getfilteredData("SELECT tbl_empmaster.EmpNo FROM tbl_empmaster WHERE
                                                                tbl_empmaster.EmpNo = '$EmpNo' ");
                    if (!empty($HasRow_employee[0]->EmpNo)) {
                        // echo "Already Exist";
                        $this->db->where('EmpNo', $HasRow_employee[0]->EmpNo);
                        $this->db->update('tbl_empmaster', $data);
                    } else {
                        // echo "Not Exist";
                        $requiredFields = [
                            $EmpNo, $Enroll_No, $EPFNO, $EPF_CAT, $EMP_ST_ID, $OCP_Code, $Title, $Emp_Full_Name, 
                            $Emp_Name_Int, $Image, $Gender, $Status, $Is_Casual, $Dep_ID, $Des_ID, $Grp_ID, 
                            $RosterCode, $OTCode, $B_id, $ApointDate, $Permanent_Date, $ResignDate, $Basic_Salary, 
                            $Fixed_Allowance, $Incentive, $Is_EPF, $Address, $District, 
                            $City, $Tel_home, $Tel_mobile, $E_mail, $NIC, $Passport, $DOB, $OT_Allow, $Religion, 
                            $Civil_status, $Blood_group, $Relations_name, $Relations_Tel, $No_Of_Child, 
                            $Is_allow_login, $username, $user_p_id, $IS_Fixed_allowance, $Remarks, 
                            $highlights, $Cmp_ID, $Trans_Date, $Active_process, $is_nopay_calc, $BR1, $BR2,$leave_allocate_type
                        ];
    
                        if (in_array(null, $requiredFields, true) || in_array('', $requiredFields, true)) {
                            $this->session->set_flashdata('error_message', 'Please Fill All Required Fields in Row ' . $row->getRowIndex());
                            redirect(base_url() . "Employee_Management/ADD_Employees/index");
                        }else{
                            //set newly added employee password as NIC
                            $data['password'] = hash('sha512', $NIC);

                            $setting_data=$this->Db_model->getfilteredData("SELECT employee_count FROM tbl_company_setting");
                            $setting_emp_count=(int)$setting_data[0]->employee_count;

                            $current_data=$this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS employee_count FROM tbl_empmaster WHERE Status=1 AND EmpNo !=9000");
                            $current_emp_count=(int)$current_data[0]->employee_count;

                            if($setting_emp_count > $current_emp_count){
                                
                                $this->db->insert('tbl_empmaster', $data);
                            }else{
                                
                                $this->session->set_flashdata('error_message', 'Employee Limit Exceeded.');
                                redirect(base_url() . "Employee_Management/ADD_Employees/index");
                            }
                        }
                    }
                    // $this->db->insert('client', $data);
                    $count_Rows++;
                    // echo json_encode($data);
                    // echo "<br/>";
                }
                $this->session->set_flashdata('success_message', 'Upload Successfully');
                redirect(base_url() . "Employee_Management/ADD_Employees/index");
            } else {
                $this->session->set_flashdata('error_message', 'Upload Failed');
                redirect(base_url() . "Employee_Management/ADD_Employees/index");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Upload Failed');
            redirect(base_url() . "Employee_Management/ADD_Employees/index");
        }
    }
    
}
