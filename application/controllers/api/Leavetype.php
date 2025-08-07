<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Leavetype extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/LeaveType_model', 'LeaveType');
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->output->set_content_type('application/json');
    }

    public function index_get() {
        $id = $this->input->get('id');
        $result = $this->LeaveType->getAllLeaveType($id);
        if($result) {
        $this->response(['status' => true, 'data' => $result ], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }
}