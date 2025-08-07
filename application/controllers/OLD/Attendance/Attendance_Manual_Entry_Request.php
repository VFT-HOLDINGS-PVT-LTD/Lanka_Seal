<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
// C:\xampp\htdocs\webx_v1\vendor\phpmailer\phpmailer\src\PHPMailer.php
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class Attendance_Manual_Entry_Request extends CI_Controller
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

        $data['title'] = "Attendance Manual Entry | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_grp'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $this->load->view('Attendance/Attendance_Manual_Entry_Request/index', $data);
    }

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

        if ($cat == "Department") {
            $query = $this->Db_model->get_dropdown_dep();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Dep_ID . "'>" . $row->Dep_Name . "</option>";
            }
        }
        if ($cat == "Designation") {
            $query = $this->Db_model->get_dropdown_des();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Des_ID . "'>" . $row->Desig_Name . "</option>";
            }
        }
        if ($cat == "Employee_Group") {
            $query = $this->Db_model->get_dropdown_group();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Grp_ID . "'>" . $row->EmpGroupName . "</option>";
            }
        }

        if ($cat == "Company") {
            $query = $this->Db_model->get_dropdown_comp();
            echo '<option value="" default>-- Select --</option>';
            foreach ($query->result() as $row) {
                echo "<option value='" . $row->Cmp_ID . "'>" . $row->Company_Name . "</option>";
            }
        }
    }

    /*
     * Search Employee Manual Attendance Entry
     */

    public function emp_manual_entry()
    {


        $emp = $this->input->post("txt_employee");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $comp = $this->input->post("cmb_comp");

        $att_date = $this->input->post("att_date");
        $in_time = $this->input->post("in_time");
        // $out_time = $this->input->post("out_time");
        $out_time = "00:00:00";
        $reason = $this->input->post("txt_reason");
        $satus = $this->input->post('employee_status');


        if ($satus == 'Active') {
            $st = "0";
        }
        // else{
        //     $st = "1";
        // }
        if ($satus == 'Inactive') {
            $st = "1";
        }
        $EmpData = $this->Db_model->getfilteredData("select EmpNo,Enroll_No from tbl_empmaster where EmpNo ='$emp' or Emp_Full_Name='$emp_name' ");

        $EnrollNo = $EmpData[0]->Enroll_No;

        $EmpG = $this->Db_model->getfilteredData("select Grp_ID from tbl_empmaster where EmpNo = $emp ");
//        var_dump($EmpG);
        $grpID = $EmpG[0]->Grp_ID;
        $Sup_Data = $this->Db_model->getfilteredData("select Sup_ID from tbl_emp_group where Grp_ID =$grpID; ");

        $Sup_ID = $Sup_Data[0]->Sup_ID;

        $data = array(
            'Att_Date' => $att_date,
            'In_Time' => $in_time,
            'Out_Time' => $out_time,
            'Enroll_No' => $EnrollNo,
            'Reason' => $reason,
            'Status' => $st,
            'App_Sup_User' => $Sup_ID,
            'Is_App_Sup_User' => 0,
        );

        $this->Db_model->insertData('tbl_manual_entry', $data);

        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Grp_ID,tbl_empmaster.Emp_Full_Name FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$emp'");
        $groupid = $empname1[0]->Grp_ID;
        $empname = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Sup_ID FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$groupid' ");
        $supid = $empname[0]->Sup_ID;
        $supemail = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$supid' ");

        // $config = array(
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'mail.hrislkonline.com',
        //     'smtp_user' => 'noreply@webx.hrislkonline.com',
        //     'smtp_pass' => 'wxK]LSft*ED}',
        //     'smtp_port' => 587,
        //     'charset' => 'utf-8',
        //     'mailtype' => 'html',
        //     'wordwrap' => TRUE,
        //     'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
        // );
        // $this->load->library('email', $config); // Load email with config
        // $this->email->from('mail@vfthris.com');
        // $this->email->to($supemail[0]->E_mail);
        // $this->email->message('Employee ' . $empname1[0]->Emp_Full_Name . '(' . $emp . ') Manual Entry Request ');
        // $this->email->subject("Manual Entry Request");
        // if ($this->email->send()) {
        //     echo "Email sent successfully.";
        // } else {
        //     echo $this->email->print_debugger();
        // }

        // $data = array(
        //     'AttDate' => $att_date,
        //     'AttTime' => $in_time,
        //     'AttDateTimeStr' => "0000-00-00 00:00:00",
        //     'Enroll_No' => $EnrollNo,
        //     'AttPlace' => "null",
        //     'Status' => $st,
        //     'verify_type' => "0",
        //     'EventName' => "null",
        // );

        // $this->Db_model->insertData('tbl_u_attendancedata', $data);
        $Year = date("Y");
        $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'mail.hrislkonline.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply@webx.hrislkonline.com';
    $mail->Password   = 'wxK]LSft*ED}';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender and recipient settings
    $mail->setFrom('mail@vfthris.com', 'VFT Cloud');
    $mail->addAddress($supemail[0]->E_mail); // Replace with dynamic email
    $mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "VFT Cloud: Manual attendance request";

    // Dynamic HTML content
    $htmlContent = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
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
                <h1>Manual Attendance Request</h1>
            </td>
        </tr>
        <tr>
            <td class="email-body">
                <h2>Dear '.$supemail[0]->username.',</h2>
                <p>Employee <strong>' . $empname1[0]->Emp_Full_Name . '</strong> (Employee ID: <strong>' . $emp . '</strong>) has requested manual entry.</p>
            <p>Please review the manual entry request and take the necessary action.</p>
            <p class="pg1"><a href="https://webx.hrislkonline.com/Attendance/Attendance_Manual_Entry_SAPP" class="button">View Manual Entry Request</a></p>
            </td>
        </tr>
        <tr>
            <td class="email-footer">
                <p>If you have any questions, feel free to <a href="https://support.vftholdings.lk/Open_ticket">contact us</a>.</p>
                <p>&copy; <span id="current-year">'.$Year.'</span> VFT HOLDINGS (PVT) LTD | ALL RIGHTS RESERVED</p>
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
        $this->session->set_flashdata('success_message', 'Manual Entry added successfully');

        redirect(base_url() . "Attendance/Attendance_Manual_Entry_Request");
    }

}
