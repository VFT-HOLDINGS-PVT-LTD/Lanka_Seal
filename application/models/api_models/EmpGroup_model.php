<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmpGroup_model extends CI_Model {
    public $table = 'tbl_emp_group';

    public function getAllEmpGroup() {
        $this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSupervisorId($id) {
        $this->db->select('Sup_ID');
        $this->db->from($this->table);
        $this->db->where('Grp_ID', $id);
        $query = $this->db->get();
        return $query->result();
    }

}