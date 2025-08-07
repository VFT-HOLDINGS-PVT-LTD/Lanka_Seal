<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Rostershift extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/RosterShift_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }
}