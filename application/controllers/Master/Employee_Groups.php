<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employee_Groups extends CI_Controller
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
     * Index page in Departmrnt
     */
    public function index()
    {
        $data['title'] = "Employee Groups | HRM System";
        // $data['data_set'] = $this->Db_model->getData('Grp_ID,EmpGroupName,GracePeriod,NosLeaveForMonth,MaxSLS,Allow1stSession,Allow2ndSession,OTPattern,Sup_ID', 'tbl_emp_group');

        $data['data_set'] = $this->Db_model->getfilteredData('SELECT tbl_empmaster.Emp_Full_Name,tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,

        tbl_payroll_setting.Is_Nopay,
        tbl_payroll_setting.Is_Att_allow,
        tbl_payroll_setting.Is_Ot_Cal,
        tbl_payroll_setting.Is_Dot_Cal,
        tbl_payroll_setting.Is_Ot_Fixed_Amount_Rate,
        tbl_payroll_setting.Ot_Rate,
        tbl_payroll_setting.Is_Daily_Rate,
        tbl_payroll_setting.Daily_Rate,

        tbl_emp_group.Sup_ID,tbl_setting.Ot_m,tbl_setting.Ot_e,tbl_setting.Ot_d_Late,tbl_setting.Late,tbl_setting.late_Grs_prd,tbl_setting.Ed,
        tbl_setting.Min_time_t_ot_m,tbl_setting.Min_time_t_ot_e,tbl_setting.`Round`,tbl_setting.Hd_d_from,tbl_setting.Dot_f_holyday,tbl_setting.Dot_f_offday
        FROM tbl_setting INNER JOIN tbl_emp_group ON tbl_setting.Group_id = tbl_emp_group.Grp_ID 
        INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.EmpNo INNER JOIN tbl_payroll_setting ON tbl_emp_group.Grp_ID = tbl_payroll_setting.Group_Id');
        // $data['data_ot'] = $this->Db_model->getData('OTCode,OTName', 'tbl_ot_pattern_hd');
        $data['emp_sup'] = $this->Db_model->getfilteredData("select EmpNo,Emp_Full_Name,Enroll_No from tbl_empmaster where Status=1");

        // $data['hierarchy'] = $this->Db_model->getfilteredData('SELECT tbl_companyprofile.Company_Name, 
        // tbl_emp_group.EmpGroupName, 
        // supervisor.Image AS SupervisorImage, 
        // employee.Image AS EmployeeImage, 
        // supervisor.Emp_Full_Name AS SupervisorName, 
        // employee.Emp_Full_Name AS EmployeeName
        // FROM tbl_emp_group
        // LEFT JOIN tbl_empmaster AS supervisor ON supervisor.EmpNo = tbl_emp_group.Sup_ID
        // LEFT JOIN tbl_empmaster AS employee ON employee.Grp_ID = tbl_emp_group.Grp_ID
        // LEFT JOIN tbl_companyprofile ON tbl_companyprofile.Cmp_ID = supervisor.Cmp_ID
        // WHERE supervisor.Image IS NOT NULL AND employee.Image IS NOT NULL
        // GROUP BY employee.EmpNo');

        $data['hierarchy'] = $this->Db_model->getfilteredData('SELECT 
            tbl_companyprofile.Company_Name, 
            tbl_emp_group.EmpGroupName, 
            supervisor.Image AS SupervisorImage, 
            employee.Image AS EmployeeImage, 
            supervisor.Emp_Full_Name AS SupervisorName, 
            employee.Emp_Full_Name AS EmployeeName
        FROM 
            tbl_emp_group
        LEFT JOIN 
            tbl_empmaster AS supervisor ON supervisor.EmpNo = tbl_emp_group.Sup_ID
        LEFT JOIN 
            tbl_empmaster AS employee ON employee.Grp_ID = tbl_emp_group.Grp_ID
        LEFT JOIN 
            tbl_companyprofile ON tbl_companyprofile.Cmp_ID = supervisor.Cmp_ID
        WHERE 
            supervisor.Image IS NOT NULL 
            AND employee.Image IS NOT NULL 
            AND employee.STATUS = 1
        GROUP BY 
            employee.EmpNo, 
            tbl_companyprofile.Company_Name, 
            tbl_emp_group.EmpGroupName, 
            supervisor.Image, 
            employee.Image, 
            supervisor.Emp_Full_Name, 
            employee.Emp_Full_Name
        ');
        $this->load->view('Master/Employee_Groups/index', $data);
    }
    /*
     * Insert Departmrnt
     */
    public function insert_data()
    {

        $ot_m = $this->input->post('ot_m');
        $ot_e = $this->input->post('ot_e');
        $min_time_to_ot = $this->input->post('min_t_ot');
        $min_time_to_mor_ot = $this->input->post('min_t_e_ot');
        $round = $this->input->post('round');
        $late = $this->input->post('late');
        $ed = $this->input->post('ed');
        $late_deduct_for_full_leave_in_halfd = $this->input->post('sh_lv');
        $late_ded_from_ot = $this->input->post('late_ot');
        $dot_for_holyday = $this->input->post('dot_holyday');
        $dot_for_off = $this->input->post('dot_offday');$late_grace = $this->input->post('late_gp');

        // Payroll related data
        $is_nopay = $this->input->post('is_nopay');
        $is_att_allow = $this->input->post('is_att_allow');
        $is_daily_rate = $this->input->post('is_daily_rate');
        $daily_rate = $this->input->post('daily_rate');
        $is_ot_cal = $this->input->post('is_ot_cal');
        $is_dot_cal = $this->input->post('is_dot_cal');
        $is_ot_fixed_amount_rate = $this->input->post('is_ot_Fixed_amount_rate');
        $ot_rate = $this->input->post('ot_rate');
        $currency = $this->input->post('currency');
        

        $ot_mo = 0;
        $ot_ev = 0;
        $late_status = 0;
        $ed_status = 0;
        $late_deduct_leave_in_halfday = 0;
        $late_deduct_from_ot = 0;
        $dot_holyday = 0;
        $dot_offday = 0;
        $late_grace = 0;

        
        $is_nopay_set=0;
        $is_att_allow_set=0;
        $is_daily_rate_set=0;
        $is_ot_cal_set= 0;
        $is_dot_cal_set=0;
        $is_ot_fixed_amount_rate_set= 0;



        if(!empty($this->input->post('late_gp'))){
            $late_grace = $this->input->post('late_gp');
        }

        if ($ot_m == 'on') {
            $ot_mo = 1;
        }
        if ($ot_e == 'on') {
            $ot_ev = 1;
        }
        if ($late == 'on') {
            $late_status = 1;
        }
        if ($ed == 'on') {
            $ed_status = 1;
        }
        if ($dot_for_holyday == 'on') {
            $dot_holyday = 1;
        }
        if ($dot_for_off == 'on') {
            $dot_offday = 1;
        }
        if ($late_deduct_for_full_leave_in_halfd == 'on') {
            $late_deduct_leave_in_halfday = 1;
        }
        if ($late_ded_from_ot == 'on') {
            $late_deduct_from_ot = 1;
        }

        //check payroll related checkboxes and set value
        if ($is_nopay == 'on') {
            $is_nopay_set = 1;
        }
        if ($is_att_allow == 'on') {
            $is_att_allow_set = 1;
        }
        if ($is_daily_rate == 'on') {
            $is_daily_rate_set = 1;
        }
        if ($is_ot_cal == 'on') {
            $is_ot_cal_set = 1;
        }
        if ($is_dot_cal == 'on') {
            $is_dot_cal_set = 1;
        }
        if ($is_ot_fixed_amount_rate == 'on') {
            $is_ot_fixed_amount_rate_set = 1;
        }


        $FSt = $this->input->post('chk_1st');
        if ($FSt == null) {
            $FSt = 0;
        } elseif ($FSt == 'on') {
            $FSt = 1;
        }

        $Snd = $this->input->post('chk_2nd');
        if ($Snd == null) {
            $Snd = 0;
        } elseif ($Snd == 'on') {
            $Snd = 1;
        }

        $sup = $this->input->post('cmb_Supervisor');
        if ($sup == null) {
            $sup = 9000;
        }

        $group_name = $this->input->post('txt_group_name');
        $check_availble = $this->Db_model->getfilteredData("select * from tbl_emp_group where EmpGroupName='$group_name'");
        // echo $check_availble[0]->Grp_ID;
        if (empty($check_availble[0]->Grp_ID)) {
            $group_name2 = $this->input->post('txt_group_name');
            $data = array(
                'EmpGroupName' => $this->input->post('txt_group_name'),
                'GracePeriod' => 0,
                'Sup_ID' => $sup,
                'NosLeaveForMonth' => 0,
                'MaxSLS' => 0,
                'Allow1stSession' => 0,
                'Allow2ndSession' => 0,
                'OTPattern' => 'OT0001'
            );

            $result = $this->Db_model->insertData("tbl_emp_group", $data);
            $last_in_id = $this->Db_model->getfilteredData("select * from tbl_emp_group where EmpGroupName='$group_name2'");
            $last_insert_id = $last_in_id[0]->Grp_ID;

            $data2 = array(
                'Group_id' => $last_insert_id,
                'Ot_m' => $ot_mo,
                'Ot_e' => $ot_ev,
                'Ot_d_Late' => $late_deduct_from_ot,
                'Late' => $late_status,
                'Ed' => $ed_status,
                'Min_time_t_ot_e' => $min_time_to_ot,
                'Min_time_t_ot_m' => $min_time_to_mor_ot,
                'Dot_f_holyday' => $dot_holyday,
                'Dot_f_offday' => $dot_offday,
                'Hd_d_from' => $late_deduct_leave_in_halfday,
                'Round' => $round,
                'late_Grs_prd' => $late_grace,
                'ST' => 1,
            );
            
            $result2 = $this->Db_model->insertData("tbl_setting", $data2);

            // insert into payroll setting table
            $data3 = array(
                'Group_Id' => $last_insert_id,
                'Is_Nopay' => $is_nopay_set,
                'Is_Att_allow' => $is_att_allow_set,
                'Is_Daily_Rate' => $is_daily_rate_set,
                'Daily_Rate' => $daily_rate,
                'Is_Ot_Cal' => $is_ot_cal_set,
                'Is_Dot_Cal' => $is_dot_cal_set,
                'Is_Ot_Fixed_Amount_Rate' => $is_ot_fixed_amount_rate_set,
                'Ot_Rate' => $ot_rate,
                'Currency' => $currency,
            );
            
            $result3 = $this->Db_model->insertData("tbl_payroll_setting", $data3);
            $this->session->set_flashdata('success_message', 'Employee Group Added Successfully');

        } else {
            $this->session->set_flashdata('Error_message', 'Employee Group Already Added');
        }
        redirect('Master/Employee_Groups/');
    }
    /*
     * Get Department data
     */
    public function get_details()
    {
        $id = $this->input->post('id');
        // echo $id;
        $whereArray = array('Grp_ID' => $id);
        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Grp_ID,EmpGroupName,GracePeriod,NosLeaveForMonth,MaxSLS,Allow1stSession,Allow2ndSession,Sup_ID', 'tbl_emp_group');
        // $dataObject = $this->Db_model->getfilteredData('SELECT tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,tbl_emp_group.GracePeriod,tbl_emp_group.NosLeaveForMonth,tbl_emp_group.MaxSLS,tbl_emp_group.Allow1stSession,tbl_emp_group.Allow2ndSession,tbl_emp_group.OTPattern,tbl_emp_group.Sup_ID FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.Enroll_No ');
        $array = (array) $dataObject;
        echo json_encode($array);
    }
    public function updateAttView()
    {
        $id = $this->input->get('id');

        //    echo "OkM " . $id;

        $whereArray = array('ID' => $id);


        $this->Db_model->setWhere($whereArray);
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Emp_Full_Name,tbl_empmaster.EmpNo,tbl_emp_group.Grp_ID,tbl_emp_group.EmpGroupName,
        tbl_emp_group.Sup_ID,tbl_setting.Ot_m,tbl_setting.Ot_e,tbl_setting.Ot_d_Late,tbl_setting.Late,tbl_setting.late_Grs_prd,tbl_setting.Ed,
        tbl_setting.Min_time_t_ot_m,tbl_setting.Min_time_t_ot_e,tbl_setting.`Round`,tbl_setting.Hd_d_from,tbl_setting.Dot_f_holyday,tbl_setting.Dot_f_offday,
        
        tbl_payroll_setting.Is_Nopay,
        tbl_payroll_setting.Is_Att_allow,
        tbl_payroll_setting.Is_Ot_Cal,
        tbl_payroll_setting.Is_Dot_Cal,
        tbl_payroll_setting.Is_Ot_Fixed_Amount_Rate,
        tbl_payroll_setting.Ot_Rate,
        tbl_payroll_setting.Is_Daily_Rate,
        tbl_payroll_setting.Daily_Rate

        
        FROM tbl_setting 
        INNER JOIN tbl_emp_group ON tbl_setting.Group_id = tbl_emp_group.Grp_ID 
        INNER JOIN tbl_empmaster ON tbl_emp_group.Sup_ID = tbl_empmaster.EmpNo
        INNER JOIN tbl_payroll_setting ON tbl_setting.Group_id = tbl_payroll_setting.Group_Id 


        WHERE tbl_emp_group.Grp_ID = '$id';");
        $data['emp_sup'] = $this->Db_model->getfilteredData("select EmpNo,Emp_Full_Name,Enroll_No from tbl_empmaster where Status=1");
        $data['title'] = "Employee Group | HRM System";


        $this->load->view('Master/Employee_Groups/update', $data);
    }
    /*
     * Edit Data
     */
    public function edit()
    {

        $group_id = $this->input->post("txt_group_id");

        $ot_m = $this->input->post('ot_m');
        $ot_e = $this->input->post('ot_e');
        $min_time_to_ot = $this->input->post('min_t_e_ot');
        $min_time_to_mor_ot = $this->input->post('min_t_ot');
        $round = $this->input->post('round');
        $late = $this->input->post('late');
        $ed = $this->input->post('ed');
        $late_deduct_for_full_leave_in_halfd = $this->input->post('sh_lv');
        $late_ded_from_ot = $this->input->post('late_ot');
        $dot_for_holyday = $this->input->post('dot_holyday');
        $dot_for_off = $this->input->post('dot_offday');
        $late_grace = $this->input->post('late_gp');

        // Payroll related data
        $is_nopay = $this->input->post('is_nopay');
        $is_att_allow = $this->input->post('is_att_allow');
        $is_daily_rate = $this->input->post('is_daily_rate');
        $daily_rate = $this->input->post('daily_rate');
        $is_ot_cal = $this->input->post('is_ot_cal');
        $is_dot_cal = $this->input->post('is_dot_cal');
        $is_ot_fixed_amount_rate = $this->input->post('is_ot_fixed_amount_rate');
        $ot_rate = $this->input->post('ot_rate');


        $ot_mo = 0;
        $ot_ev = 0;
        $late_status = 0;
        $ed_status = 0;
        $late_deduct_leave_in_halfday = 0;
        $late_deduct_from_ot = 0;
        $dot_holyday = 0;
        $dot_offday = 0;


        $is_nopay_set=0;
        $is_att_allow_set=0;
        $is_daily_rate_set=0;
        $is_ot_cal_set= 0;
        $is_dot_cal_set=0;
        $is_ot_fixed_amount_rate_set= 0;



        if ($ot_m == 'on') {
            $ot_mo = 1;
        }
        if ($ot_e == 'on') {
            $ot_ev = 1;
        }
        if ($late == 'on') {
            $late_status = 1;
        }
        if ($ed == 'on') {
            $ed_status = 1;
        }
        if ($dot_for_holyday == 'on') {
            $dot_holyday = 1;
        }
        if ($dot_for_off == 'on') {
            $dot_offday = 1;
        }
        if ($late_deduct_for_full_leave_in_halfd == 'on') {
            $late_deduct_leave_in_halfday = 1;
        }
        if ($late_ded_from_ot == 'on') {
            $late_deduct_from_ot = 1;
        }

        //check payroll related checkboxes and set value
        if ($is_nopay == 'on') {
            $is_nopay_set = 1;
        }
        if ($is_att_allow == 'on') {
            $is_att_allow_set = 1;
        }
        if ($is_daily_rate == 'on') {
            $is_daily_rate_set = 1;
        }
        if ($is_ot_cal == 'on') {
            $is_ot_cal_set = 1;
        }
        if ($is_dot_cal == 'on') {
            $is_dot_cal_set = 1;
        }
        if ($is_ot_fixed_amount_rate == 'on') {
            $is_ot_fixed_amount_rate_set = 1;
        }


        $FSt = $this->input->post('chk_1st');
        if ($FSt == null) {
            $FSt = 0;
        } elseif ($FSt == 'on') {
            $FSt = 1;
        }

        $Snd = $this->input->post('chk_2nd');
        if ($Snd == null) {
            $Snd = 0;
        } elseif ($Snd == 'on') {
            $Snd = 1;
        }

        $sup = $this->input->post('cmb_Supervisor');
        if ($sup == null) {
            $sup = 9000;
        }

        $group_name = $this->input->post('txt_group_name');

        $data = array("EmpGroupName" => $group_name, "Sup_ID" => $sup);
        $whereArr = array("Grp_ID" => $group_id);
        $result1 = $this->Db_model->updateData("tbl_emp_group", $data, $whereArr);

        $data1 = array(
            'Ot_m' => $ot_mo,
            'Ot_e' => $ot_ev,
            'Ot_d_Late' => $late_deduct_from_ot,
            'Late' => $late_status,
            'Ed' => $ed_status,
            'Min_time_t_ot_e' => $min_time_to_ot,
            'Min_time_t_ot_m' => $min_time_to_mor_ot,
            'Dot_f_holyday' => $dot_holyday,
            'Dot_f_offday' => $dot_offday,
            'Hd_d_from' => $late_deduct_leave_in_halfday,
            'Round' => $round,
            'late_Grs_prd' => $late_grace,
        );
        $whereArr1 = array(
            "Group_id" => $group_id
        );

        $data2 = array(
            'Is_Nopay' => $is_nopay_set,
            'Is_Att_allow' => $is_att_allow_set,
            'Is_Daily_Rate' => $is_daily_rate_set,
            'Daily_Rate' => $daily_rate,
            'Is_Ot_Cal' => $is_ot_cal_set,
            'Is_Dot_Cal' => $is_dot_cal_set,
            'Is_Ot_Fixed_Amount_Rate' => $is_ot_fixed_amount_rate_set,
            'Ot_Rate' => $ot_rate,
        );
        $whereArr1 = array(
            "Group_Id" => $group_id
        );


        $result = $this->Db_model->updateData("tbl_setting", $data1, $whereArr1);
        $result2 = $this->Db_model->updateData("tbl_payroll_setting", $data2, $whereArr1);

        if( $result1 && $result2) {
            $this->session->set_flashdata('success_message', 'Payroll Detais and Shift Employee Group Updated.');
            redirect(base_url() . "Master/Employee_Groups");
        }

        
    }
    /*
     * Delete Data
     */
    public function ajax_delete($id)
    {
        $table = "tbl_emp_group";
        $where = 'Grp_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }
}