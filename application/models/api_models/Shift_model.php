<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Shift_model extends CI_Model {
    public $table_name = 'tbl_shifts';

    public function getShift($id = null) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if ($id != null) {
            $this->db->where('ShiftCode', $id);
        }
        $query = $this->db->get();
        return $query->result();
    }


}