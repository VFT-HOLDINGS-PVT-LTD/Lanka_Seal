<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeaveType_model extends CI_Model {
    public $table = 'tbl_leave_types';

    public function getAllLeaveType($id = null) {
        $this->db->select('*');
        if ($id != null) {
            $this->db->where('Lv_T_ID', $id);
        }
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();  
    }

    

}