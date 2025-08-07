<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Salary_model extends CI_Model
{
    public $salary_table = 'tbl_salary';
    public $allowance_has_salary_table = 'tbl_allowance_has_tbl_salary';
    public $deduction_has_salary_table = 'tbl_deduction_has_tbl_salary';
    public $department_table = 'tbl_departments';
    public $designation_table = 'tbl_designations';

    public function getSalaryRecord($empid = null, $month = null, $year = null)
    {
        // First get salary data with department and designation
        $this->db->select('tbl_salary.*, tbl_departments.Dep_Name, tbl_designations.Desig_Name');
        $this->db->from($this->salary_table);
        $this->db->join($this->department_table, 'tbl_departments.Dep_ID = tbl_salary.Dep_ID', 'left');
        $this->db->join($this->designation_table, 'tbl_designations.Des_ID = tbl_salary.Des_ID', 'left');

        if ($empid != null) {
            $this->db->where('tbl_salary.EmpNo', $empid);
        }
        if ($month != null) {
            $this->db->where('tbl_salary.Month', $month);
        }
        if ($year != null) {
            $this->db->where('tbl_salary.Year', $year);
        }

        $query = $this->db->get();
        $salaries = $query->result();
        

        // Then get allowances for each salary
        foreach ($salaries as &$salary) {
            $this->db->select('Alw_Name, Alw_Amount');
            $this->db->from($this->allowance_has_salary_table);
            $this->db->where('tbl_salary_ID', $salary->ID);
            $query = $this->db->get();
            $salary->allowances = $query->result();
            // Then get deductions for each salary
            $this->db->select('Ded_Name, Ded_Amount');
            $this->db->from($this->deduction_has_salary_table);
            $this->db->where('tbl_salary_ID', $salary->ID);
            $query = $this->db->get();
            $salary->deductions = $query->result();
        }


        return $salaries;
    }




}