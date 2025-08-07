<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Salary_Advance extends CI_Controller
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
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page in Departmrnt
     */

    public function index()
    {

        $data['title'] = "Other Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');

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
        
        $this->load->view('Reports/Payroll/Salary_Advance', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department()
    {
        $Data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $Data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    COUNT(EmpNo) AS EmpCount, tbl_departments.Dep_ID, tbl_departments.Dep_Name
                                                                FROM
                                                                    tbl_empmaster
                                                                        INNER JOIN
                                                                    tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID
                                                                GROUP BY tbl_departments.Dep_ID");

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }


    public function generateReport()
    {
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");

        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;
        // Filter Data by categories
        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  tbl_salary_advance.Request_Date between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  tbl_salary_advance.Request_Date between '$from_date' and '$to_date'";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where tbl_salary_advance.EmpNo =$emp";
            } else {
                $filter .= " AND tbl_salary_advance.EmpNo =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND tbl_empmaster.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Des_ID  ='$desig'";
            } else {
                $filter .= " AND tbl_empmaster.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.Dep_ID  ='$dept'";
            } else {
                $filter .= " AND tbl_empmaster.Dep_ID  ='$dept'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where tbl_empmaster.B_id  ='$branch'";
            } else {
                $filter .= " AND tbl_empmaster.B_id  ='$branch'";
            }
        }

        // echo $filter;

        //newly added code for filtering| Branch wise
        $loggedEmpNo = (int) $this->session->userdata('login_user')[0]->EmpNo;
        $user_branch_id = $this->Db_model->getfilteredData("SELECT tbl_empmaster.B_id FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$loggedEmpNo'");
        $user_branch_id = $user_branch_id[0]->B_id;

        if ($user_branch_id != '000') {
            if ($filter == null) {
                $filter = "where tbl_empmaster.B_id = '$user_branch_id'";
            } else {
                $filter .= " AND tbl_empmaster.B_id = '$user_branch_id'";
            }
        }


        $data['data_set']= $this->Db_model->getfilteredData("SELECT tbl_salary_advance.EmpNo,
                                                            tbl_salary_advance.Amount,
                                                            tbl_salary_advance.Request_Date,
                                                            tbl_empmaster.Emp_Full_Name,
                                                            tbl_departments.Dep_Name,
                                                            tbl_designations.Desig_Name,
                                                            tbl_branches.B_name
                                                            FROM tbl_salary_advance INNER JOIN tbl_empmaster ON tbl_salary_advance.EmpNo = tbl_empmaster.EmpNo 
                                                            INNER JOIN tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID 
                                                            INNER JOIN tbl_designations ON tbl_empmaster.Des_ID = tbl_designations.Des_ID 
                                                            INNER JOIN tbl_branches ON tbl_empmaster.B_id = tbl_branches.B_id
                                                                    
                                                            {$filter}  order by tbl_branches.B_id,tbl_salary_advance.EmpNo");
        
        if(count($data['data_set']) == 0) {
            $this->session->set_flashdata('error_message', 'No Record Found!');
            redirect(base_url() . "Reports/Payroll/Salary_Advance");
        } else {
            $this->session->set_flashdata('success', 'Record Found!');
        }

        $this->load->view('Reports/Payroll/rpt_sal_adv', $data);
    }
}