<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Mailtest extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('emailconfig_lib');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
        $this->load->model('api_models/EmpMaster_model');
    }

    public function index_get()
    {
        $this->mailHandler();
    }




    public function mailHandler()
    {
        $recipient = '';
        $SendList = [];
        $DeSend = [];
        $EmpData = $this->EmpMaster_model->getEmpEmails();

        foreach ($EmpData as $emp) {
            if ($emp['E_mail'] != '') {
                $to = $emp['E_mail'];
                $subject = $emp['Emp_Name_Int'];
                $message = $this->mailbody($subject);

                $send_status = $this->emailconfig_lib->send_mail($to, $subject, $message);
                if ($send_status) {
                    $SendList[] = $emp['E_mail']; 
                } else {
                    $DeSend[] = $emp['E_mail'];
                }


            }

        }

       


        $this->response(['status' => true, 'Send List' => $SendList, 'DeSend List' => $DeSend ], REST_Controller::HTTP_OK);


    }


    public function mailbody($data) {
        return "<!DOCTYPE html>
        <html><head>
        <title>Test Email</title>
        <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            }
            .email-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            }
            .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777777;
            }
        </style>
        </head>
        <body>
        <div class='email-container'>
            <h2>Hello, $data </h2>
            <p>This is a test email sent from our system.</p>
            <p>If you received this message, it means everything is working correctly!</p>
            <a href='#' class='button'>Visit Our Website</a>
            <div class='footer'>
            <p>Thank you for using our service.</p>
            <p>&copy; 2025 Your Company Name</p>
            </div>
        </div>
        </body>
        </html>";
    }

}