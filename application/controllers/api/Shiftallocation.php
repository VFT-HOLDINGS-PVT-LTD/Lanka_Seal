<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Shiftallocation extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/ShiftAllocation_model');
        $this->load->model('api_models/IndividualRoster_model');
        $this->load->model('api_models/Shift_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get() {
        $empid = $this->input->get('EmpNo');
        $year = $this->input->get('Year');
        $start_date = $this->input->get('Start_Date');
        $end_date = $this->input->get('End_Date');
        $f_result = [];

        $db_data = $this->ShiftAllocation_model->getShiftAllocation($empid, $year, $start_date, $end_date);

        foreach ($db_data as $row) {
            $shift = $this->Shift_model->getShift($row->ShiftCode);
            $row->ShiftName = $shift[0]->ShiftName;
            $f_result[] = $row;

        }

        if(!empty($f_result)) {
            $this->response(['staus' => true, 'data' => $f_result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['staus' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }



        
    }




}