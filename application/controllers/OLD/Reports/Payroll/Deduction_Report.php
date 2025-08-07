<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_Report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index() {

        $data['title'] = "Deduction Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_alw'] = $this->Db_model->getData('Alw_ID,Allowance_name', 'tbl_allowance_type');
        //$data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');

        //newly added code for filtering| Branch wise
        $filter='';
        $loggedEmpNo = (int) $this->session->userdata('login_user')[0]->EmpNo;
        $user_branch_id = $this->Db_model->getfilteredData("SELECT tbl_empmaster.B_id FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$loggedEmpNo'");
        $user_branch_id = $user_branch_id[0]->B_id;

        if($user_branch_id == '000'){
            $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        }
        else{
            $data['data_branch'] = $this->Db_model->getfilteredData("SELECT B_id,B_name FROM tbl_branches WHERE B_id = '$user_branch_id'");
        }
        
        $this->load->view('Reports/Payroll/Report_deductions', $data);
    }

    /**
     *  Filter data according deduction type
     */
    
    public function Report_deduction_by_type() {

        //get company profile
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        //get inputa data
        $emp_no = $this->input->post("emp_no");
        $emp_name = $this->input->post("emp_name");
        $designation = $this->input->post("designation");
        $department = $this->input->post("department");
        $branch = $this->input->post("branch");
        $year = $this->input->post("year");
        $month = $this->input->post("month");
        $deduction_type = $this->input->post("deduction_type");
        $group = $this->input->post("group");

        //filter data by deduction type
        if($deduction_type=="loan" && !empty($deduction_type)){
        
            $filter = '';

            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }
            if (($this->input->post("month"))) {
                if ($filter == '') {
                    $filter = " where  lo.Month =$month";
                } else {
                    $filter .= " AND  lo.Month =$month";
                }
            }
            if (($this->input->post("year"))) {
                if ($filter == '') {
                    $filter = " where  lo.Year =$year";
                } else {
                    $filter .= " AND  lo.Year =$year";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where lo.EmpNo =$emp_no";
                } else {
                    $filter .= " AND lo.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }
    
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    lo.ID,
                                                                    lo.FullAmount,
                                                                    lo.Month_Installment,
                                                                    lo.Paid_Amount,
                                                                    lo.Balance_amount,
                                                                    lo.Month,
                                                                    lo.Year,
                                                                    lo.EmpNo,
                                                                    lo.Loan_date  as daduction_date,
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name

                                                                FROM
                                                                    tbl_loans lo
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = lo.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.B_id
                                                                LEFT JOIN
                                                                    tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID  

                                                                {$filter}");
    
        // var_dump($data);die;
        $this->load->view('Reports/Payroll/rpt_deductions', $data);
        // $this->session->set_flashdata('success_message', 'Report Generated Successfully.');
        
        }elseif($deduction_type=="festival" && !empty($deduction_type)){
            
            $filter = '';

            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }
            if (($this->input->post("month"))) {
                if ($filter == '') {
                    $filter = " where  fa.Month =$month";
                } else {
                    $filter .= " AND  fa.Month =$month";
                }
            }
            if (($this->input->post("year"))) {
                if ($filter == '') {
                    $filter = " where  fa.Year =$year";
                } else {
                    $filter .= " AND  fa.Year =$year";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where fa.EmpNo =$emp_no";
                } else {
                    $filter .= " AND fa.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }
    
    
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    fa.ID,
                                                                    fa.FullAmount,
                                                                    fa.Month_Installment,
                                                                    fa.Paid_Amount,
                                                                    fa.Balance_amount,
                                                                    fa.Month,
                                                                    fa.Year,
                                                                    fa.EmpNo,
                                                                    fa.Festival_advance_date as daduction_date,
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name

                                                                FROM
                                                                    tbl_festival_advance fa
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = fa.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.B_id
                                                                LEFT JOIN
	                                                                tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID        
                                                                {$filter}");
    
            // var_dump($data);die;
            
            // $this->session->set_flashdata('success_message', 'Report Generated Successfully.');
            $this->load->view('Reports/Payroll/rpt_deductions', $data);
            
        }else{
            $this->session->set_flashdata('Error_message', 'Could not generate report. Please try again.');
            redirect('Reports/Payroll/Deduction_Report/');
        }


    }


    //Filter Data by name jquery part
    function get_auto_emp_name() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }
    
    function get_auto_emp_no() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }

}