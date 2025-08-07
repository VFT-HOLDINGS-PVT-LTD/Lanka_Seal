<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class View_Short_Leave extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index()
    {
        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data['title'] = "View Short Leave | HRM System";
        // $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        // $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        // $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        // $data['data_grp'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        // $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_set_att'] = $this->Db_model->getfilteredData("select * from tbl_shortlive inner join tbl_empmaster on tbl_empmaster.EmpNo = tbl_shortlive.EmpNo where tbl_shortlive.Is_pending='1' and tbl_shortlive.Approved_by = $Emp  order by ID desc");


        $this->load->view('Leave_Transaction/View_Short_Leave/index', $data);
    }

    public function ajax_Status($id)
    {
        // echo $id;
        $data_arr = array("Is_pending" => 0, "Is_Approve" => 0, "Is_Cancel" => 1);
        $whereArray = array("ID" => $id);
        $result = $this->Db_model->updateData("tbl_shortlive", $data_arr, $whereArray);
    }

    public function ajax_Status_Aprove($id)
    {
        // echo $id;
        $data_arr = array("Is_pending" => 0, "Is_Approve" => 1, "Is_Cancel" => 0);
        $whereArray = array("ID" => $id);
        $result = $this->Db_model->updateData("tbl_shortlive", $data_arr, $whereArray);
        // $config = array(
//                                 'protocol' => 'smtp',
//                                 'smtp_host' => 'mail.hrislkonline.com',
//                                 'smtp_user' => 'noreply@webx.hrislkonline.com',
//                                 'smtp_pass' => 'wxK]LSft*ED}',
//                                 'smtp_port' => 587,
//                                 'charset' => 'utf-8',
//                                 'mailtype' => 'html',
//                                 'wordwrap' => TRUE,
//                                 'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
//                             );
//                             $this->load->library('email', $config); // Load email with config
//                             $this->email->from('mail@vfthris.com');
//                             $this->email->to($employee_email);
//                             $this->email->message("Short Leave Request Approve");
//                             $this->email->subject("Leave Request");
//                             if ($this->email->send()) {
//                                 echo "Email sent successfully.";
//                             } else {
//                                 echo $this->email->print_debugger();
//                             }

        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE tbl_shortlive.ID = '$id'");
        $advEmp = $advData[0]->EmpNo;
        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.Enroll_No = '$advEmp'");

        $Year = date("Y");
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.hrislkonline.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@webx.hrislkonline.com';
            $mail->Password = 'wxK]LSft*ED}';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient settings
            $mail->setFrom('mail@vfthris.com', 'VFT Cloud');
            $mail->addAddress($empname1[0]->E_mail); // Replace with dynamic email
            $mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "VFT Cloud: Short Leave Approved";

            // Dynamic HTML content
            $htmlContent = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 10px;
                background-color: #f9f9f9;
            }
            table {
                width: 100%;
            }
            .email-container {
                width: 100%;
                background-color: #ffffff;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
            }
            .email-header {
                background-image: url("https://webx.hrislkonline.com/assets/images/login-bg.jpg");
                background-size: cover;
                background-position: center;
                color: white;
                padding: 40px 20px;
                text-align: center;
                border-radius: 10px 10px 0 0;
            }
            .email-header h1 {
                margin-top: 10px;
                font-size: 28px;
            }
            .email-body {
                padding: 20px;
                color: #333333;
            }
            .email-footer {
                background-color: #f1f1f1;
                text-align: center;
                padding: 10px 0;
                font-size: 12px;
                color: #777777;
                border-radius: 0 0 10px 10px;

            }
                .pg1 {
                    color: white;
        }
            .button, a:visited {
                background-color: #001a67; 
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;
                margin-top: 5px;
                text-decoration: none;
                display: inline-block;
            }
                .pg1 {
                    color: white;
        }
            @media only screen and (max-width: 600px) {
                .email-container {
                    width: 100%;
                    padding: 10px;
                }
            }
            .header img {
                max-width: 176px;
                display: block; /* Ensure the image is centered */
                margin: 0 auto; /* Center the image */
                border-radius: 10px;
                padding: 15px;
            }
            
        </style>
    </head>
    <body><div class="container">
        <table class="email-container" role="presentation">
            <tr class="header">
                <td>
                    <img src="https://webx.hrislkonline.com/assets/images/company/logowebx.png" alt="Logo">
                                    <hr> <!-- Added horizontal line -->

                </td>
            </tr>
            <tr>
                <td class="email-header">
                    <h1>Short Leave Approved</h1>
                </td>
            </tr>
            <tr>
                <td class="email-body">
                    <h2>Dear ' . $empname1[0]->username . ',</h2>
                    <p>Your short leave request has been approved.</p>
                    <p class="pg1"><a href="https://webx.hrislkonline.com/Leave_Transaction/Short_Leave_Request/" class="button">View Approved Short Leave</a></p>
                </td>
            </tr>
            <tr>
                <td class="email-footer">
                    <p>If you have any questions, feel free to <a href="https://support.vftholdings.lk/Open_ticket">contact us</a>.</p>
                    <p>&copy; <span id="current-year">' . $Year . '</span> VFT HOLDINGS (PVT) LTD | ALL RIGHTS RESERVED</p>
                </td>
            </tr>
            <tr>
            <td> <br/>  </td>
            </tr>
        </table>
        </div>

        <script>
            document.getElementById("current-year").textContent = new Date().getFullYear();
        </script>
    </body>
    </html>


    ';

            $mail->Body = $htmlContent;

            // Send email
            if ($mail->send()) {
                echo "Email sent successfully.";
            } else {
                echo "Email not sent.";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        $this->session->set_flashdata('success_message', 'successfully');
        redirect(base_url() . "Leave_Transaction/View_Short_Leave");
    }

    public function delete($ID)
    {

        // echo $ID;

        $table = "tbl_shortlive";
        $where = 'id';

        // echo json_encode(array("status" => TRUE));

        // $currentUser = $this->session->userdata('login_user');
        // $Emp = $currentUser[0]->EmpNo;

        // $data = array(
        //     'Is_pending' => 0,
        //     'Is_Approve' => 0,
        //     'Is_Cancel' => 1,
        //     'Approved_by' => $Emp,
        // );

        // $whereArr = array("id" => $ID);
        // $result = $this->Db_model->updateData("tbl_salary_advance", $data, $whereArr);
// $config = array(
//                                 'protocol' => 'smtp',
//                                 'smtp_host' => 'mail.hrislkonline.com',
//                                 'smtp_user' => 'noreply@webx.hrislkonline.com',
//                                 'smtp_pass' => 'wxK]LSft*ED}',
//                                 'smtp_port' => 587,
//                                 'charset' => 'utf-8',
//                                 'mailtype' => 'html',
//                                 'wordwrap' => TRUE,
//                                 'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
//                             );
//                             $this->load->library('email', $config); // Load email with config
//                             $this->email->from('mail@vfthris.com');
//                             $this->email->to($employee_email);
//                             $this->email->message("Short Leave Request Reject");
//                             $this->email->subject("Leave Request");
//                             if ($this->email->send()) {
//                                 echo "Email sent successfully.";
//                             } else {
//                                 echo $this->email->print_debugger();
//                             }
        $this->session->set_flashdata('success_message', 'Reject successfully');
        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE tbl_shortlive.ID = '$ID'");
        $advEmp = $advData[0]->EmpNo;
        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.Enroll_No = '$advEmp'");

        $Year = date("Y");
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.hrislkonline.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreply@webx.hrislkonline.com';
            $mail->Password = 'wxK]LSft*ED}';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient settings
            $mail->setFrom('mail@vfthris.com', 'VFT Cloud');
            $mail->addAddress($empname1[0]->E_mail); // Replace with dynamic email
            $mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "VFT Cloud: Short Leave Rejected";

            // Dynamic HTML content
            $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Email</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        background-color: #f9f9f9;
                    }
                    table {
                        width: 100%;
                    }
                    .email-container {
                        width: 100%;
                        background-color: #ffffff;
                        margin: 0 auto;
                        padding: 20px;
                        border-radius: 10px;
                    }
                    .email-header {
                        background-image: url("https://webx.hrislkonline.com/assets/images/login-bg.jpg");
                        background-size: cover;
                        background-position: center;
                        color: white;
                        padding: 40px 20px;
                        text-align: center;
                        border-radius: 10px 10px 0 0;
                    }
                    .email-header h1 {
                        margin-top: 10px;
                        font-size: 28px;
                    }
                    .email-body {
                        padding: 20px;
                        color: #333333;
                    }
                    .email-footer {
                        background-color: #f1f1f1;
                        text-align: center;
                        padding: 10px 0;
                        font-size: 12px;
                        color: #777777;
                        border-radius: 0 0 10px 10px;

                    }
                        .pg1 {
                            color: white;
                }
                    .button, a:visited {
                        background-color: #001a67; 
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        display: inline-block;
                        margin-top: 5px;
                        text-decoration: none;
                        display: inline-block;
                    }
                        .pg1 {
                            color: white;
                }
                    @media only screen and (max-width: 600px) {
                        .email-container {
                            width: 100%;
                            padding: 10px;
                        }
                    }
                    .header img {
                        max-width: 176px;
                        display: block; /* Ensure the image is centered */
                        margin: 0 auto; /* Center the image */
                        border-radius: 10px;
                        padding: 15px;
                    }
                    
                </style>
            </head>
            <body><div class="container">
                <table class="email-container" role="presentation">
                    <tr class="header">
                        <td>
                            <img src="https://webx.hrislkonline.com/assets/images/company/logowebx.png" alt="Logo">
                                            <hr> <!-- Added horizontal line -->

                        </td>
                    </tr>
                    <tr>
                        <td class="email-header">
                            <h1>Short Leave Rejected</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-body">
                            <h2>Dear ' . $empname1[0]->username . ',</h2>
                            <p>Your short leave request has been rejected. Please contact the HR department for further details.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-footer">
                            <p>If you have any questions, feel free to <a href="https://support.vftholdings.lk/Open_ticket">contact us</a>.</p>
                            <p>&copy; <span id="current-year">' . $Year . '</span> VFT HOLDINGS (PVT) LTD | ALL RIGHTS RESERVED</p>
                        </td>
                    </tr>
                    <tr>
                    <td> <br/>  </td>
                    </tr>
                </table>
                </div>

                <script>
                    document.getElementById("current-year").textContent = new Date().getFullYear();
                </script>
            </body>
            </html>


            ';

            $mail->Body = $htmlContent;

            // Send email
            if ($mail->send()) {
                echo "Email sent successfully.";
            } else {
                echo "Email not sent.";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        $this->Db_model->delete_by_id($ID, $where, $table);
        $this->session->set_flashdata('success', 'Short Leave Rejected Successfully.');
        redirect(base_url() . "Leave_Transaction/View_Short_Leave");
    }




}
