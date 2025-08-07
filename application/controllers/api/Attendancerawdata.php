<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Attendancerawdata extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/AttendanceRawData_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    
    }

    public function index_get() {
        $id = $this->input->get('id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $result = $this->AttendanceRawData_model->getAttendanceData($id, $start_date, $end_date);
        if ($result) {
            $this->response(['status' => true, 'data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }

    public function index_post() {
        $data = json_decode($this->input->raw_input_stream, true);
        $result = $this->AttendanceRawData_model->insert_attendance_data($data);

        if ($result) {
            $this->response(['status' => true, 'message' => 'Data inserted successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Failed to insert data'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    

}