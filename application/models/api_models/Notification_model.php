<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_model extends CI_Model {
    public $table = 'tbl_notifications';

    public function get_notification($status = null) {
        $this->db->select('*');
        if ($status != null) {
            $this->db->where('Is_Display', $status);
        }
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

}