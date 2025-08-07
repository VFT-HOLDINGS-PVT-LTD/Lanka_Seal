<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companyprofile_model extends CI_Model {
    public $table_name = 'tbl_companyprofile';

    public function getCompanydetails() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return $query->result();
    }


}