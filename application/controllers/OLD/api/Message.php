<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Message extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/Messages_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get()
    {
        $receiver = $this->input->get('receiver');
        $sender = $this->input->get('sender');
        if ($receiver != null) {
            $result = $this->Messages_model->getAllMessages($receiver);
        } elseif ($sender != null) {
            $result = $this->Messages_model->getMyMessages($sender);
        } else {
            $result = null;

        }

        if ($result) {
            $this->response(['status' => true, 'data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = json_decode($this->input->raw_input_stream, true);
        $result = $this->Messages_model->insertMessage($data);
        if ($result) {
            $this->response(['status' => true, 'message' => 'Message sent successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Failed to send message'], REST_Controller::HTTP_OK);
        }
    }
}