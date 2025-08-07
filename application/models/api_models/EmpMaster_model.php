<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmpMaster_model extends CI_Model {

    public $table = 'tbl_empmaster';
    public function getEmployeeDataEnrollNo($id = null) {
        $this->db->select('*');
        if ($id != null) {
            $this->db->where('Enroll_No', $id);
        }
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getEmployeeGroupID($id) {
        $this->db->select('Grp_ID');
        $this->db->from($this->table);
        $this->db->where('Enroll_No', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getEmployeeBirthDays($date) {
        $this->db->select('EmpNo, Enroll_No, Emp_Name_Int');
        $this->db->from($this->table);
        $this->db->where("DATE_FORMAT(DOB, '%m-%d') =", date('m-d', strtotime($date)));
        $result = $this->db->get();
        return $result->result_array();
    }


    public function getEmpEmails() {
        $this->db->select('Emp_Name_Int, E_mail');
        $this->db->from($this->table);
        $result = $this->db->get();
        return $result->result_array();
    }

    


}