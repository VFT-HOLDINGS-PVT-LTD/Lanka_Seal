<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class Attendance_Manual_Entry_SAPP extends CI_Controller
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
        $this->load->model('api_models/EmailQueue_model', 'EmailQueue', true);

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

        $this->load->view('Attendance/Attendance_Manual_View_SUP/index', $data);
    }

    public function search_employee()
    {

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;
        // Filter Data by categories
        $filter = '';


        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " AND  le.Leave_Date between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  le.Leave_Date  between '$from_date' and '$to_date'";
            }
        }

        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " AND em.EmpNo = '$emp'";
            } else {
                $filter .= " AND em.EmpNo = '$emp'";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " AND em.Emp_Full_Name= '$emp_name'";
            } else {
                $filter .= " AND em.Emp_Full_Name = '$emp_name'";
            }
        }

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data['data_set'] = $this->Db_model->getfilteredData("select  `M_ID`,`EmpNo`,`Emp_Full_Name`,`Att_Date`,`In_Time`,`tbl_manual_entry`.`Status`,`Reason` from tbl_manual_entry
inner join tbl_empmaster
on tbl_empmaster.EmpNo = tbl_manual_entry.Enroll_No where Is_App_Sup_User =0 and Is_Cancel=0 and App_Sup_User = $Emp
  {$filter}");

        $this->load->view('Attendance/Attendance_Manual_View_SUP/search_data', $data);
    }


    public function approve($ID)
    {

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_App_Sup_User' => 1,
            'Admin_App_ID' => $Emp,
            'Is_Admin_App_ID' => 1,
        );


        $whereArr = array("M_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_manual_entry", $data, $whereArr);

        $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_manual_entry WHERE `M_ID`=$ID");
        $EnrollNo = $data[0]->Enroll_No;
        $in_time = $data[0]->In_Time;
        $att_date = $data[0]->Att_Date;
        $st = $data[0]->Status;

        $data = array(
            'AttDate' => $att_date,
            'AttTime' => $in_time,
            'AttDateTimeStr' => "0000-00-00 00:00:00",
            'Enroll_No' => $EnrollNo,
            'AttPlace' => "null",
            'Status' => $st,
            'verify_type' => "0",
            'EventName' => "null",
        );

        // echo json_encode($data);

        $this->Db_model->insertData('tbl_u_attendancedata', $data);

        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_manual_entry WHERE tbl_manual_entry.M_ID = '$ID'");
        $advEnroll_No = $advData[0]->Enroll_No;
        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.Enroll_No = '$advEnroll_No'");

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
            $mail->Subject = "VFT Cloud: Attendance Manual Entry Approved";

            // Dynamic HTML content
            $htmlContent = '<!DOCTYPE html>
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
                <h1>Attendance Manual Entry Approved</h1>
            </td>
        </tr>
        <tr>
            <td class="email-body">
                <h2>Dear ' . $empname1[0]->username . ',</h2>
                <p>Your attendance manual entry request has been approved.</p>
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


        $this->session->set_flashdata('success_message', 'Manual Entry Approved Successfully');
        redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");

    }

    public function ajax_StatusReject($id)
    {
        // echo $id;
        $data_arr = array("App_Sup_User" => 0, "Is_App_Sup_User" => 0, "Admin_App_ID" => 0, "Is_Admin_App_ID" => 0, "Is_Cancel" => 1);
        $whereArray = array("M_ID" => $id);
        $result = $this->Db_model->updateData("tbl_manual_entry", $data_arr, $whereArray);

        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_manual_entry WHERE tbl_manual_entry.M_ID = '$id'");
        $advEnroll_No = $advData[0]->Enroll_No;
        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.Enroll_No = '$advEnroll_No'");

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
            $mail->Subject = "VFT Cloud: Attendance Manual Entry Rejected";

            // Dynamic HTML content
            $htmlContent = '<!DOCTYPE html>
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
                <h1>Attendance Manual Entry rejected</h1>
            </td>
        </tr>
        <tr>
            <td class="email-body">
                <h2>Dear ' . $empname1[0]->username . ',</h2>
                <p>Your attendance manual entry request has been rejected.</p>
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


        $this->session->set_flashdata('success_message', 'Manual Entry Rejected successfully');
        redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");
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


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $comp = $this->input->post("cmb_comp");

        $att_date = $this->input->post("att_date");
        $in_time = $this->input->post("in_time");
        $out_time = $this->input->post("out_time");
        $reason = $this->input->post("txt_reason");


        $EmpData = $this->Db_model->getfilteredData("select EmpNo,Enroll_No from tbl_empmaster where EmpNo ='$emp' or Emp_Full_Name='$emp_name' ");



        $EnrollNo = $EmpData[0]->Enroll_No;





        $data = array(
            'Att_Date' => $att_date,
            'In_Time' => $in_time,
            'Out_Time' => $out_time,
            'Enroll_No' => $EnrollNo,
            'Reason' => $reason
        );

        $this->Db_model->insertData('tbl_manual_entry', $data);
        $this->session->set_flashdata('success_message', 'Manual Entry added successfully');

        redirect(base_url() . "Attendance/Attendance_Manual_Entry");
    }

    public function approveAll()
    {
        $ids = $this->input->post('ids');

        echo json_encode($ids);

        if (!empty($ids)) {
            foreach ($ids as $ID) {
                // Approve the leave request with the given ID
                // Your code to approve the leave request
                $currentUser = $this->session->userdata('login_user');
                $Emp = $currentUser[0]->EmpNo;

                $data = array(
                    'Is_App_Sup_User' => 1,
                    'Admin_App_ID' => $Emp,
                    'Is_Admin_App_ID' => 1,
                );


                // $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_manual_entry where M_ID=$ID");
                // $Emp_No = $Emp_Data[0]->EmpNo;

                // //Get Employee Contact Details

                // $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
                // $Tel = $Emp_cont_Data[0]->Tel_mobile;
                // $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;


                //***Get leave date by Leave ID 
                // $Leave_data = $this->Db_model->getfilteredData("select * from tbl_manual_entry where M_ID=$ID and EmpNo=$Emp_No");
                $whereArr = array("M_ID" => $ID);
                $result = $this->Db_model->updateData("tbl_manual_entry", $data, $whereArr);


                $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_manual_entry WHERE `M_ID`=$ID");
                $EnrollNo = $data[0]->Enroll_No;
                $in_time = $data[0]->In_Time;
                $att_date = $data[0]->Att_Date;
                $st = $data[0]->Status;

                $data = array(
                    'AttDate' => $att_date,
                    'AttTime' => $in_time,
                    'AttDateTimeStr' => "0000-00-00 00:00:00",
                    'Enroll_No' => $EnrollNo,
                    'AttPlace' => "null",
                    'Status' => $st,
                    'verify_type' => "0",
                    'EventName' => "null",
                );

                // echo json_encode($data);

                $this->Db_model->insertData('tbl_u_attendancedata', $data);

                $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_manual_entry WHERE tbl_manual_entry.M_ID = '$ID'");
                $advEmp = $advData[0]->Enroll_No;
                $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.Enroll_No = '$advEmp'");

                $Year = date("Y");
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    // $mail->isSMTP();
                    // $mail->Host = 'mail.hrislkonline.com';
                    // $mail->SMTPAuth = true;
                    // $mail->Username = 'noreply@webx.hrislkonline.com';
                    // $mail->Password = 'wxK]LSft*ED}';
                    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    // $mail->Port = 587;

                    // // Sender and recipient settings
                    // $mail->setFrom('mail@vfthris.com', 'VFT Cloud');
                    // $mail->addAddress($supemail[0]->E_mail); // Replace with dynamic email
                    // $mail->addReplyTo('noreply@webx.hrislkonline.com', 'No Reply');

                    // Email content
                    // $mail->isHTML(true);
                    $mail->Subject = "VFT Cloud: Attendance Manual Entry Approved";

                    $mailSubject = $mail->Subject;

                    // Dynamic HTML content
                    $htmlContent = '<!DOCTYPE html>
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
                                <h1>Attendance Manual Entry Approved</h1>
                            </td>
                        </tr>
                        <tr>
                            <td class="email-body">
                                <h2>Dear ' . $empname1[0]->username . ',</h2>
                                <p>Your attendance manual entry request has been approved.</p>
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

                    $mailData = [
                        'reciver_id' => $advEmp,
                        'reciver_email' => $empname1[0]->E_mail,
                        'mail_status' => 0,
                        'mail_subject' => $mailSubject,
                        'mail_body' => $htmlContent
                    ];


                    $mailResult = $this->EmailQueue->insertMail($mailData);
                    // Send email
                    if ($mail->send()) {
                        echo "Email sent successfully.";
                    } else {
                        echo "Email not sent.";
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
            // Redirect or give a success message
            $this->session->set_flashdata('success_message', 'Leave Approved successfully');
            redirect(base_url() . "Attendance/Attendance_Manual_Entry_SAPP");
        } else {
            // Handle the case where no IDs are provided
            // Redirect or give an error message
            redirect('path/to/error/page');
        }
    }

}
