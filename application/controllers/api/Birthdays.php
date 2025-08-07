<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Birthdays extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/EmpMaster_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');

    }

    public function index_get() {
        $date = $this->input->get('date');
        $db_data = $this->EmpMaster_model->getEmployeeBirthDays($date);
        if(!empty($db_data)) {
            $this->response(['staus' => true, 'data' => $db_data], REST_Controller::HTTP_OK);
        } else {
            $this->response(['staus' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }

}