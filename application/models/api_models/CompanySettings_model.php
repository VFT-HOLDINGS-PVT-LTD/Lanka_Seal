<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CompanySettings_model extends CI_Model {
    public $table = 'tbl_company_settings';

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function create_table() {
        $fields = array(
            'Setting_ID' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'SetName' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'SetValue' => array(
                'type' => 'INT',
                'constraint' => '5',
            ),
            'Note' => array(
                'type' => 'TEXT'
            )
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('Setting_ID', TRUE);
        $this->dbforge->create_table($this->table, TRUE);

    }

    public function get_company_settings($column = null) {
       
        if ($column != null) {
            $this->db->select('SetValue');
            $this->db->where('SetName', $column);
        } else {
            $this->db->select('*');
        }
      
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }

    public function readSetting($sName) {
        $this->db->select('SetValue');
        $this->db->from($this->table);
        $this->db->where('SetName', $sName);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_settings($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_settings($id, $data) {
        $this->db->where('Setting_ID', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_settings($id) {
        $this->db->where('Setting_ID', $id);
        return $this->db->delete($this->table);
    }


}