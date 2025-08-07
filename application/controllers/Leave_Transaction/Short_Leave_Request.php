<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class Short_Leave_Request extends CI_Controller
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
        $data['title'] = "Leave Entry | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_leave'] = $this->Db_model->getData('Lv_T_ID,leave_name,leave_entitle', 'tbl_leave_types');
        $data['data_set_att'] = $this->Db_model->getfilteredData("select * from tbl_shortlive inner join tbl_empmaster on tbl_empmaster.EmpNo = tbl_shortlive.EmpNo where tbl_empmaster.EmpNo=$Emp order by ID desc");

        $this->load->view('Leave_Transaction/Short_Leave_Request/index', $data);

        // $this->load->view('Leave_Transaction/Short_Leave_Entry/index', $data);

    }

    /*
     * Check Leave Balance
     */

    public function check_Leave()
    {


        $cat = $this->input->post('cmb_cat2');

        $query = $this->Db_model->getfilteredData("select Used, Balance from tbl_leave_allocation where EmpNo='" . $cat . "' ");

        $query;
    }

    /*
     * Dependent Dropdown
     */

    public function dropdown()
    {

        $cat = $this->input->post('cmb_cat');

        if ($cat == "Employee") {
            $query = $this->Db_model->get_dropdown();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {

                echo "<option value='" . $row->EmpNo . "'>" . $row->Emp_Full_Name . "</option>";
            }
        }
    }

    /*
     * Insert Leave Data
     */
    // sl 2kak thiynwam
    public function insert_data()
    {

        $currentUser = $this->session->userdata('login_user');
        // $ApproveUser = $currentUser[0]->EmpNo;

        $Emp = $this->input->post('txt_employee');
        $date1 = $this->input->post('att_date');
        $from_time = $this->input->post('in_time');
        $to_time = $this->input->post('out_time');

        date_default_timezone_set('Asia/Colombo');
        $date = new DateTime();
        $timestamp1 = date_format($date, 'Y-m-d');

        $orderdate1 = explode('/', $date1);
        $year1 = $orderdate1[0];

        $month2 = $orderdate1[1];

        $date = $date1;
        $H = explode("/", $date);
        $month = $H[1];


        // $Monthonly = date('Y/m/d');
        // $M = explode("/", $Monthonly);
        // $Month1 = $M[1];

        // $leaveentity = $this->Db_model->getfilteredData("SELECT * FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Grp_ID = tbl_empmaster.Grp_ID WHERE tbl_empmaster.EmpNo = '$Emp' ");
        // $shortleaveDate = $useentity["sh"][0]->Date;
        // if (empty($useentity["sh"])) {

        $dateTime = date('Y/m/d h:i:s', time());
        $useentity["sh"] = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE `EmpNo` = '$Emp' AND `Month`='$month'");

        // old code
        // if (!empty($useentity["sh"])) {
        //     // echo "thiywa2";
        //     foreach ($useentity["sh"] as $data) {
        //         // echo json_encode($data);
        //         // echo "1";
        //         // thiynwanam
        //         if ($data != null) {
        //             $shortleaveDate = $data->Date;
        //             $ID = $data->ID;
        //             $MonthData = $data->Month;

        //             $this->session->set_flashdata('error_message', 'Already Have a Short Leave');
        //             redirect('Leave_Transaction/Short_Leave_Entry/index');

        //         } else {

        //             $data = array(
        //                 'EmpNo' => $Emp,
        //                 'from_time' => $from_time,
        //                 'to_time' => $to_time,
        //                 'Date' => $date1,
        //                 'Month' => $month,
        //                 'used' => 1,
        //                 // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
        //                 'balance' => '0',
        //                 'Apply_Date' => $dateTime,
        //                 'Is_pending' => '1',
        //                 'Is_Approve' => '0',
        //             );
        //             $this->Db_model->insertData('tbl_shortlive', $data);
        //             $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
        //             redirect('Leave_Transaction/Short_Leave_Entry/index');
        //         }
        //     }
        // } else {
        //     // echo "nee2";
        //     // echo "<br>";
        //     $data = array(
        //         'EmpNo' => $Emp,
        //         'from_time' => $from_time,
        //         'to_time' => $to_time,
        //         'Date' => $date1,
        //         'Month' => $month,
        //         'used' => 1,
        //         // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
        //         'balance' => '0',
        //         'Apply_Date' => $dateTime,
        //         'Is_pending' => '1',
        //         'Is_Approve' => '0',
        //     );
        //     $this->Db_model->insertData('tbl_shortlive', $data);
        //     $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
        //     redirect('Leave_Transaction/Short_Leave_Entry/index');

        // }


        $shortLeave = $this->Db_model->getfilteredData("SELECT count(ID) as ID FROM tbl_shortlive WHERE `EmpNo` = '$Emp' AND `Month`='$month'");
        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Grp_ID,tbl_empmaster.Emp_Full_Name FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$Emp'");
        $groupid = $empname1[0]->Grp_ID;
        $empname = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Sup_ID FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$groupid' ");
        $supid = $empname[0]->Sup_ID;
        $supemail = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username,tbl_empmaster.Enroll_No FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$supid' ");
        $ApproveUser = $supemail[0]->Enroll_No;
        // echo json_encode($shortLeave);
        // die;

        if ($shortLeave[0]->ID == 2) {
            // echo "thiywa2";
            // foreach ($useentity["sh"] as $data) {
            // echo json_encode($data);
            // echo "1";
            // thiynwanam
            // if ($data != null) {
            //     $shortleaveDate = $data->Date;
            //     $ID = $data->ID;
            //     $MonthData = $data->Month;

            //     $this->session->set_flashdata('error_message', 'Already Have a Short Leave');
            //     redirect('Leave_Transaction/Short_Leave_Entry/index');

            // } else {


            // }
            // }
            $this->session->set_flashdata('error_message', 'Already Have a Short Leave');
            redirect('Leave_Transaction/Short_Leave_Request/index');
        } else {
            $data = array(
                'EmpNo' => $Emp,
                'from_time' => $from_time,
                'to_time' => $to_time,
                'Date' => $date1,
                'Month' => $month,
                'used' => 1,
                // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
                'balance' => '0',
                'Apply_Date' => $dateTime,
                'Is_pending' => '1',
                'Is_Approve' => '0',
                'Approved_by' => $ApproveUser
            );
            $this->Db_model->insertData('tbl_shortlive', $data);
           
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
                $mail->addAddress($supemail[0]->E_mail); // Replace with dynamic email
                $mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "VFT Cloud: Short Leave Request";

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
                                                <h1>Short Leave Request</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="email-body">
                                                <h2>Dear ' . $supemail[0]->username . ',</h2>
                                                <p>Employee <strong>' . $empname1[0]->Emp_Full_Name . '</strong> (Employee ID: <strong>' . $Emp . '</strong>) has short leave requested.</p>
                                            <p>Please review the short leave request and take the necessary action.</p>
                                            <p class="pg1"><a href="https://webx.hrislkonline.com/Leave_Transaction/View_Short_Leave/" class="button">View Short Leave Request</a></p>
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

            $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
            redirect('Leave_Transaction/Short_Leave_Request/index');
            // echo "nee2";
            // echo "<br>";
            // $data = array(
            //     'EmpNo' => $Emp,
            //     'from_time' => $from_time,
            //     'to_time' => $to_time,
            //     'Date' => $date1,
            //     'Month' => $month,
            //     'used' => 1,
            //     // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
            //     'balance' => '0',
            //     'Apply_Date' => $dateTime,
            //     'Is_pending' => '1',
            //     'Is_Approve' => '0',
            // );
            // $this->Db_model->insertData('tbl_shortlive', $data);
            // $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
            // redirect('Leave_Transaction/Short_Leave_Entry/index');



        }

    }


    // public function insert_data()
    // {

    //     $currentUser = $this->session->userdata('login_user');
    //     $ApproveUser = $currentUser[0]->EmpNo;
    //     $Emp = $this->input->post('txt_employee');
    //     $date1 = $this->input->post('att_date');
    //     $from_time = $this->input->post('in_time');
    //     $to_time = $this->input->post('out_time');

    //     date_default_timezone_set('Asia/Colombo');
    //     $date = new DateTime();
    //     $timestamp1 = date_format($date, 'Y-m-d');

    //     $orderdate1 = explode('/', $date1);
    //     $year1 = $orderdate1[0];

    //     $month2 = $orderdate1[1];

    //     $date = $date1;
    //     $H = explode("/", $date);
    //     $month = $H[1];


    //     // $Monthonly = date('Y/m/d');
    //     // $M = explode("/", $Monthonly);
    //     // $Month1 = $M[1];

    //     // $leaveentity = $this->Db_model->getfilteredData("SELECT * FROM tbl_emp_group INNER JOIN tbl_empmaster ON tbl_emp_group.Grp_ID = tbl_empmaster.Grp_ID WHERE tbl_empmaster.EmpNo = '$Emp' ");
    //     // $shortleaveDate = $useentity["sh"][0]->Date;
    //     // if (empty($useentity["sh"])) {

    //     $dateTime = date('Y/m/d h:i:s', time());
    //     $useentity["sh"] = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE `EmpNo` = '$Emp' AND `Month`='$month'");

    //     if (!empty($useentity["sh"])) {
    //         // echo "thiywa2";
    //         foreach ($useentity["sh"] as $data) {
    //             // echo json_encode($data);
    //             // echo "1";
    //             // thiynwanam
    //             if ($data != null) {
    //                 $shortleaveDate = $data->Date;
    //                 $ID = $data->ID;
    //                 $MonthData = $data->Month;

    //                 $this->session->set_flashdata('error_message', 'Already Have a Short Leave');
    //                 redirect('Leave_Transaction/Short_Leave_Request/index');

    //             } else {

    //                 $data = array(
    //                     'EmpNo' => $Emp,
    //                     'from_time' => $from_time,
    //                     'to_time' => $to_time,
    //                     'Date' => $date1,
    //                     'Month' => $month,
    //                     'used' => 1,
    //                     // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
    //                     'balance' => '0',
    //                     'Apply_Date' => $dateTime,
    //                     'Is_pending' => '1',
    //                     'Is_Approve' => '0',
    //                 );
    //                 $this->Db_model->insertData('tbl_shortlive', $data);
    //                 // $config = array(
    //                 //             'protocol' => 'smtp',
    //                 //             'smtp_host' => 'mail.hrislkonline.com',
    //                 //             'smtp_user' => 'noreply@webx.hrislkonline.com',
    //                 //             'smtp_pass' => 'wxK]LSft*ED}',
    //                 //             'smtp_port' => 587,
    //                 //             'charset' => 'utf-8',
    //                 //             'mailtype' => 'html',
    //                 //             'wordwrap' => TRUE,
    //                 //             'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
    //                 //         );
    //                 //         $this->load->library('email', $config); // Load email with config
    //                 //         $this->email->from('mail@vfthris.com');
    //                 //         $this->email->to($employee_email);
    //                 //         $this->email->message("Employee (' . $Emp . ') Short Leave Request ");
    //                 //         $this->email->subject("Short Leave Request");
    //                 //         if ($this->email->send()) {
    //                 //             echo "Email sent successfully.";
    //                 //         } else {
    //                 //             echo $this->email->print_debugger();
    //                 //         }
    //                 $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
    //                 redirect('Leave_Transaction/Short_Leave_Request/index');
    //             }
    //         }
    //     } else {
    //         // echo "nee2";
    //         // echo "<br>";
    //         $data = array(
    //             'EmpNo' => $Emp,
    //             'from_time' => $from_time,
    //             'to_time' => $to_time,
    //             'Date' => $date1,
    //             'Month' => $month,
    //             'used' => 1,
    //             // 'balance' => $leaveentity[0]->NosLeaveForMonth - 1,
    //             'balance' => '0',
    //             'Apply_Date' => $dateTime,
    //             'Is_pending' => '1',
    //             'Is_Approve' => '0',
    //         );
    //         $this->Db_model->insertData('tbl_shortlive', $data);
    //         // $config = array(
    //         //                     'protocol' => 'smtp',
    //         //                     'smtp_host' => 'mail.hrislkonline.com',
    //         //                     'smtp_user' => 'noreply@webx.hrislkonline.com',
    //         //                     'smtp_pass' => 'wxK]LSft*ED}',
    //         //                     'smtp_port' => 587,
    //         //                     'charset' => 'utf-8',
    //         //                     'mailtype' => 'html',
    //         //                     'wordwrap' => TRUE,
    //         //                     'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
    //         //                 );
    //         //                 $this->load->library('email', $config); // Load email with config
    //         //                 $this->email->from('mail@vfthris.com');
    //         //                 $this->email->to($employee_email);
    //         //                 $this->email->message("Employee (' . $Emp . ') Short Leave Request ");
    //         //                 $this->email->subject("Short Leave Request");
    //         //                 if ($this->email->send()) {
    //         //                     echo "Email sent successfully.";
    //         //                 } else {
    //         //                     echo $this->email->print_debugger();
    //         //                 }
    //         $this->session->set_flashdata('success_message', 'Employee Short Leave Added');
    //         redirect('Leave_Transaction/Short_Leave_Request/index');

    //     }



    // }
}
