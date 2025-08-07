<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Leaves extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function summery_get($id=0) {
        $query = $this->db->select('tbl_leave_allocation.*, tbl_leave_types.leave_name')->from('tbl_leave_allocation')->join('tbl_leave_types','tbl_leave_allocation.Lv_T_ID = tbl_leave_types.Lv_T_ID')->where('EmpNo',$id)->get();
        $this->response($query->result(), 200);

    }

    public function log_get($id=0) {
        $query = $this->db->select('tbl_leave_entry.*,tbl_leave_types.leave_name')->from('tbl_leave_entry')->join('tbl_leave_types','tbl_leave_entry.Lv_T_ID = tbl_leave_types.Lv_T_ID')->where('EmpNo',$id)->get();
        $this->response($query->result(), 200);
    }
    public function types_get() {
        $query = $this->db->select('*')->from('tbl_leave_types')->get();
        $this->response($query->result(), 200);
    }

    public function request_post() {
        $data = $this->post();
        $this->db->insert('tbl_leave_entry', $data);
        $this->response(array("message"=>"Success"), 200);
    }

    public function index_delete() {
        // DELETE method implementation
    }
}
