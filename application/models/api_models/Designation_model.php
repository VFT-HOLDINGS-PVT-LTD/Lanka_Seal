<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation_model extends CI_Model {
    public $table_name = 'tbl_designations';

    public function getDesignation($id = null) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if ($id != null) {
            $this->db->where('Des_ID', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

}