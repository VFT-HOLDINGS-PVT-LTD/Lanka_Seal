<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Messages_model extends CI_Model
{
    public $table = 'tbl_messages';

    public function getAllMessages($receiver)
    {
        $this->db->select('*');
        $this->db->where('recever', $receiver);
        $this->db->from($this->table);
        $this->db->order_by('Trans_time', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMyMessages($sender)
    {
        $this->db->select('*');
        $this->db->where('sender', $sender);
        $this->db->from($this->table);
        $this->db->order_by('Trans_time', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insertMessage($data) {
        return $this->db->insert($this->table, $data);
    }

}


