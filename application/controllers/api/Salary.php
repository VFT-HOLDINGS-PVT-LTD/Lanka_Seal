<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Salary extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/Salary_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');

    }

    public function index_get()
    {
        $empid = $this->input->get('EmpNo');
        $month = $this->input->get('Month');
        $year = $this->input->get('Year');
        $result = $this->Salary_model->getSalaryRecord($empid, $month, $year);
        if ($result) {
            $this->response(['status' => true, 'data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }

    public function salarydata_get()
    {
        $this->response(['message' => 'Request Accept'], REST_Controller::HTTP_OK);
    }





}