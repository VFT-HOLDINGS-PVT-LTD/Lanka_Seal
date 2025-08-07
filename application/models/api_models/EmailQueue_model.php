<?php
class EmailQueue_model extends CI_Model {

    public $table_name = 'tbl_email_queue';

    public function getAllMailList() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->limit(20);
        $this->db->get();
        return $this->db->result();
    }

    public function getFailedMailList() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('mail_status', 0);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function insertMail($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    public function updateMailStatus($id, $data) {
        $this->db->where('record_id', $id);
        return $this->db->update($this->table_name, $data);
    }

}