<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Emailqueue extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/EmailQueue_model', 'EmailQueue', true);
        $this->load->library('emailconfig_lib');
    }

    public function index_get()
    {
        $mailList = $this->EmailQueue->getFailedMailList();
        if ($mailList) {
            foreach ($mailList as $email) {
                $to = $email['reciver_email'];
                $subject = $email['mail_subject'];
                $body = $email['mail_body'];

                $result = $this->emailconfig_lib->send_mail($to, $subject, $body);
                if ($result) {
                    $this->EmailQueue->updateMailStatus($email['record_id'], ['mail_status' => 1]);
                } else {
                    $this->EmailQueue->updateMailStatus($email['record_id'], ['mail_status' => 0]);
                }
            }
            $this->response([
                'status' => true,
                'message' => 'Email  Queue Processed Successfully'
            ], REST_Controller::HTTP_OK);

        }$this->response([
            'status' => false,
            'message' => 'No Email Queue Found'
        ], REST_Controller::HTTP_OK);

    }


}