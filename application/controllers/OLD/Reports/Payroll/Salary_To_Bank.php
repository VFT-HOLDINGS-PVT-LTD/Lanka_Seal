<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Salary_To_Bank extends CI_Controller
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
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $this->load->view('Reports/Payroll/Report_Salary_To_Bank', $data);
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
    public function get_report()
    {
        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $cmp_id = $this->input->post("cmb_comp");
        $dept = $this->input->post("cmb_dep");
        $year1 = $this->input->post("cmb_year");
        $Month = $this->input->post("cmb_month");

        if (empty($year1) && empty($Month)) {
            echo "No data provided.";
        } else {
            $filter = '';

            // Build the query filter
            if ($year1) {
                $filter = "WHERE tbl_salary_advance.Month = '$Month' AND tbl_salary_advance.Year = '$year1'";
            }
            if ($emp) {
                $filter .= $filter ? " AND tbl_salary_advance.EmpNo = $emp" : " WHERE tbl_salary_advance.EmpNo = $emp";
            }
            if ($emp_name) {
                $filter .= $filter ? " AND tbl_empmaster.Emp_Full_Name = '$emp_name'" : " WHERE tbl_empmaster.Emp_Full_Name = '$emp_name'";
            }
            if ($dept) {
                $filter .= $filter ? " AND tbl_departments.Dep_id = '$dept'" : " WHERE tbl_departments.Dep_id = '$dept'";
            }

            //Filter by logged user branch |newly added 
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

            ob_start(); // Start output buffering

            // Your header calls
            header("Content-Type: text/plain");
            header('Content-Disposition: attachment; filename="salary_advance_report.txt"');

            // Query to fetch data
            $query = "SELECT * FROM tbl_salary_advance 
        INNER JOIN tbl_empmaster ON tbl_salary_advance.EmpNo = tbl_empmaster.EmpNo
        INNER JOIN tbl_branches ON tbl_branches.B_id = tbl_empmaster.B_id
        INNER JOIN tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID {$filter}";

            $data['data_set'] = $this->Db_model->getfilteredData($query);

            // Check if data exists and export it
            if (!empty($data['data_set'])) {
                foreach ($data['data_set'] as $row) {
                    $formattedRow = [
                        '72843127',
                        $row->Amount,
                        'BCEYLKLX683',
                        'PAYROLL CHKROLL SALARY',
                        'SALARY',
                        $row->Emp_Full_Name,
                        $row->EmpNo,
                        '740003',
                        'CONSULTANCY FEES LEGAL CHARGES AND SALARIES',
                        'NRM',
                        'SHA',
                    ];
                    echo implode('|', array_map('htmlspecialchars', $formattedRow)) . "\n";
                }
            } else {
                echo "No data available for export.";
            }
            ob_end_flush();
        }
        // redirect('Reports/Payroll/Salary_Advance_Report');
    }

    
}
