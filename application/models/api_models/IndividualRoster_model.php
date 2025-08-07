<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndividualRoster_model extends CI_Model {
    public $table_name = 'tbl_individual_roster';

    public function getIndividualRoster($id = null) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if ($id != null) {
            $this->db->where('EmpNo', $id);
        }
        $query = $this->db->get();
        return $query->result();
    }

}