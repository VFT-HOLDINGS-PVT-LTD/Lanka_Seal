<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Salary_model extends CI_Model {
    public $table = 'tbl_salary';

    public function getSalaryRecord($empid, $month, $year) {
        $this->db->select('*');
        $this->db->from($this->table);
        if ($empid != null) {
            $this->db->where('EmpNo', $empid);
        }
        if ($month != null) {
            $this->db->where('Month', $month);
        }
        if ($year != null) {
            $this->db->where('Year', $year);
        }
        $query = $this->db->get();
        return $query->result();
    }



}