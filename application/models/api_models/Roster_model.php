<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roster_model extends CI_Model {
    public $table_name = 'tbl_rosterpatternweeklyhd';

    public function getRoster($id = null) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if ($id != null) {
            $this->db->where('RosterCode', $id);
        }
        $query = $this->db->get();
        return $query->result();
    }


}