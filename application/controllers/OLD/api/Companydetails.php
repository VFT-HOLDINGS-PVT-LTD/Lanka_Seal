<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Companydetails extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/Companyprofile_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get() {
        $db_data = $this->Companyprofile_model->getCompanydetails();
        if(!empty($db_data)) {
            $this->response(['staus' => true, 'data' => $db_data], REST_Controller::HTTP_OK);
        } else {
            $this->response(['staus' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }


}