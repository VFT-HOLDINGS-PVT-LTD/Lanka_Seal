<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ShiftAllocation_model extends CI_Model {
    public $table_name = 'tbl_individual_roster';

    public function getShiftAllocation($empid = null, $year = null, $start_date = null, $end_date = null) {
        $this->db->select('*');
        $this->db->from($this->table_name);
    
        if ($empid != null) {
            $this->db->where('EmpNo', $empid);
        }
    
        if ($year != null) {
            $this->db->where('RYear', $year);
        }
    
        if ($start_date != null && $end_date != null) {
            $this->db->where('FDate >=', $start_date);
            $this->db->where('FDate <=', $end_date);
        } elseif ($start_date != null) {
            $this->db->where('FDate >=', $start_date);
        } elseif ($end_date != null) {
            $this->db->where('FDate <=', $end_date);
        }
    
        $query = $this->db->get();
        return $query->result();
    }

    
}