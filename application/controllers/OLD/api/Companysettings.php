<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Companysettings extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/CompanySettings_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get()
    {
        $this->CompanySettings_model->create_table();
        $column = $this->input->get('SetName');
        $result = $this->CompanySettings_model->get_company_settings($column);
        if ($result) {
            $this->response(['status' => true, 'data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }



}