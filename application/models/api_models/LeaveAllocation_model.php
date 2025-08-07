<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeaveAllocation_model extends CI_Model
{

    public $table = 'tbl_leave_allocation';

    public function getEmployeeLeaveAllocation($id, $year)
    {
        $this->db->select('*');
        $this->db->where('EmpNo', $id);
        $this->db->where('Year', $year);
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLeaveRecord($id) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('EmpNo', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLeaveAllocation($empid, $year, $ltid)  {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('EmpNo', $empid);
        $this->db->where('Year', $year);
        $this->db->where('Lv_T_ID', $ltid);
        $query = $this->db->get();
        return $query->result_array();
        
    }

    public function setLeaveBalanceData($id, $data) {
        $this->db->select('*');
        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);

        
        
    }

}