<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AttendanceRawData_model extends CI_Model
{
    public $table = 'tbl_u_attendancedata';

    public function getAttendanceData($id = null, $start_date = null, $end_date = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if($id != null) {
            $this->db->where('Enroll_No', $id);
        }
        if ($start_date != null && $end_date != null) {
            $this->db->where('AttDate >=', $start_date);
            $this->db->where('AttDate <=', $end_date);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    public function insert_attendance_data($data) {
        return $this->db->insert($this->table, $data);

    }

    

}