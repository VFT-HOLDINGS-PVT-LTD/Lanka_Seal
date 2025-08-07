<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LeaveEntry_model extends CI_Model
{

    public $table = 'tbl_leave_entry';

    public function getAllLeaveEntry($id = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if ($id != null) {
            $this->db->where('EmpNo', $id);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function getLeaveEntry($id) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('LV_ID', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLeaveEntryforApprove($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('Approved_by', $id);
        $this->db->where('Is_pending', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_leave_entry($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_leave_entry($id, $data)
    {
        $this->db->where('LV_ID', $id);
        return $this->db->update($this->table, $data);
        
    }

    public function deleteMask_leave_entry($id, $empid) {
        $data = array(
            'delete_ID' => $empid,
            'Is_delete' => 1,
        );
        $this->db->where('LV_ID', $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete_leave_entry($id) {
        $this->db->where('LV_ID', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }




}