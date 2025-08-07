<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class Leave_Approve extends CI_Controller
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
        $data['title'] = "Leave Apply | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_leave'] = $this->Db_model->getfilteredData("SELECT 
                                                                        lv_typ.Lv_T_ID,
                                                                        lv_typ.leave_name
                                                                    FROM
                                                                        tbl_leave_allocation lv_al
                                                                        right join
                                                                        tbl_leave_types lv_typ on lv_al.Lv_T_ID = lv_typ.Lv_T_ID
                                                                        where EmpNo='$Emp'");
        $this->load->view('Leave_Transaction/Leave_Approve/index', $data);
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
     * Search Employees by cat
     */

    public function search_employee()
    {


        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");


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

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    le.LV_ID,
                                                                    le.EmpNo,
                                                                    em.Emp_Full_Name,
                                                                    lt.leave_name,
                                                                    le.Apply_Date,
                                                                    le.month,
                                                                    le.Year,
                                                                    le.Is_pending,
                                                                    le.Leave_Date,
                                                                    le.Reason,
                                                                    le.Leave_Count
                                                                FROM
                                                                    tbl_leave_entry le
                                                                        INNER JOIN
                                                                    tbl_empmaster em ON em.EmpNo = le.EmpNo
                                                                        INNER JOIN
                                                                    tbl_leave_types lt ON lt.Lv_T_ID = le.Lv_T_ID
                                                                WHERE
                                                                    le.Is_pending = 1 and Is_Cancel=0 and Is_Sup_AD_APP =1 {$filter}");

        $this->load->view('Leave_Transaction/Leave_Approve/search_data', $data);
    }

    /*
     * Approve Leave request
     */

    public function approve($ID)
    {

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_pending' => 0,
            'Is_Approve' => 1,
            'Approved_by' => $Emp,
        );

        $LTYpe = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID = $ID");
        $Emp_LV = $LTYpe[0]->EmpNo;

        $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
        $Emp_No = $Emp_Data[0]->EmpNo;
        $leave_type = $Emp_Data[0]->Lv_T_ID;

        //Get Employee Contact Details

        $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
        $Tel = $Emp_cont_Data[0]->Tel_mobile;
        $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;


        //***Get leave date by Leave ID 
        $Leave_data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID and EmpNo=$Emp_No");

        $from_date = $Leave_data[0]->Leave_Date;

        // echo $ID.''. $leave_type;
        // die;

        /*
         * Update Individual Roster Table Is Leave status and Leave Type
         */
        //Start
        $Roster_ID = $this->Db_model->getfilteredData("select * from tbl_individual_roster where EmpNo ='$Emp_No' and Fdate = '$from_date' ");
        // $rostID = $Roster_ID1[0]->ID_Roster;
        // print_r($Roster_ID1);
        // echo $from_date.''. $from_date;
        // die;

        $DayStatus = 'LV'; //****** IF Apply Leave Update Individual Roster DayStatus As 'LV'
        $data_RS = array("Lv_T_ID" => $leave_type, "Is_Leave" => 1, "nopay" => 0, "DayStatus" => $DayStatus, 'Is_processed' => 1, "Att_Allow" => 0);
        $whereArray = array("ID_Roster" => $Roster_ID[0]->ID_Roster);
        $results = $this->Db_model->updateData("tbl_individual_roster", $data_RS, $whereArray);

        $whereArr = array("LV_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);

        // $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Grp_ID,tbl_empmaster.Emp_Full_Name,tbl_empmaster.E_mail FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$Emp_LV'");
        // $groupid = $empname1[0]->Grp_ID;
        // $employee_email = $empname1[0]->E_mail;
        // $empname = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Sup_ID FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$groupid' ");
        // $supid = $empname[0]->Sup_ID;

        // $supplier_n = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Emp_Full_Name FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$supid' ");
        // $supplier_name = $supplier_n[0]->Emp_Full_Name;
        
        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry WHERE tbl_leave_entry.LV_ID = '$ID'");
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
            $mail->Subject = "VFT Cloud: Leave Approved";

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
                            <h1>Leave Approved</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-body">
                            <h2>Dear ' . $empname1[0]->username . ',</h2>
                            <p>Your leave request has been approved.</p>
                        <p class="pg1"><a href="https://webx.hrislkonline.com/Leave_Transaction/Leave_Request/" class="button">View Approved Leave</a></p>
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
                            // $this->email->to($employee_email);
                            // $this->email->message("Leave Request Approved By '$supplier_name'");
                            // $this->email->subject("Leave Request");
                            // if ($this->email->send()) {
                            //     echo "Email sent successfully.";
                            // } else {
                            //     echo $this->email->print_debugger();
                            // }
        //End
        //****** Send message to leave request employee
        /*
         * SMS Server configuration
         */
        // $sender = "HRM SYSTEM";
        // $recipient = $Tel;
        // $message = 'System Response : ' . $Emp_Fullname . ' ' . 'Your Leave Request on' . ' ' . $from_date . ' ' . 'is Approved';

        // $url = 'http://127.0.0.1:9333/ozeki?';
        // $url .= "action=sendMessage";
        // $url .= "&login=admin";
        // $url .= "&password=abc123";
        // $url .= "&recepient=" . urlencode($recipient);
        // $url .= "&messageData=" . urlencode($message);
        // $url .= "&sender=" . urlencode($sender);
        // file($url);




        $this->session->set_flashdata('success_message', 'Leave Approved successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Approve");
    }

    public function edit_lv($ID)
    {

        $data['title'] = "Leave Apply | HRM System";

        $this->load->view('Leave_Transaction/Leave_Edit/index', $data);



        //        $currentUser = $this->session->userdata('login_user');
        //        $Emp = $currentUser[0]->EmpNo;
        //
        //        $data = array(
        //            'Is_pending' => 0,
        //            'Is_Approve' => 1,
        //            'Approved_by' => $Emp,
        //        );
        //
        //
        //        $Emp_Data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
        //        $Emp_No = $Emp_Data[0]->EmpNo;
        //        
        //        //Get Employee Contact Details
        //       
        //        $Emp_cont_Data = $this->Db_model->getfilteredData(" select EmpNo,Emp_Full_Name,Tel_mobile from tbl_empmaster where EmpNo=$Emp_No");
        //        $Tel = $Emp_cont_Data[0]->Tel_mobile;
        //        $Emp_Fullname = $Emp_cont_Data[0]->Emp_Full_Name;
        //                
        //
        //        //***Get leave date by Leave ID 
        //        $Leave_data = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID and EmpNo=$Emp_No");
        //
        //        $from_date = $Leave_data[0]->Leave_Date;
        //
        //        /*
        //         * Update Individual Roster Table Is Leave status and Leave Type
        //         */
        //        //Start
        //        $Roster_ID = $this->Db_model->getfilteredData("select ID_Roster from tbl_individual_roster where EmpNo ='$Emp_No' and Fdate = '$from_date' ");
        //        $DayStatus = 'LV'; //****** IF Apply Leave Update Individual Roster DayStatus As 'LV'
        //        $data_RS = array("Lv_T_ID" => $leave_type, "Is_Leave" => 1, "nopay" => 0, "DayStatus" => $DayStatus, 'Is_processed' => 1);
        //        $whereArray = array("ID_Roster" => $Roster_ID[0]->ID_Roster);
        //        $results = $this->Db_model->updateData("tbl_individual_roster", $data_RS, $whereArray);
        //
        //        $whereArr = array("LV_ID" => $ID);
        //        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);
        //        //End
        //
        //        //****** Send message to leave request employee
        //        /*
        //         * SMS Server configuration
        //         */
        //        $sender = "HRM SYSTEM";
        //        $recipient = $Tel;
        //        $message = 'System Response : ' . $Emp_Fullname .' '. 'Your Leave Request on'. ' '.$from_date.' '. 'is Approved';
        //
        //        $url = 'http://127.0.0.1:9333/ozeki?';
        //        $url .= "action=sendMessage";
        //        $url .= "&login=admin";
        //        $url .= "&password=abc123";
        //        $url .= "&recepient=" . urlencode($recipient);
        //        $url .= "&messageData=" . urlencode($message);
        //        $url .= "&sender=" . urlencode($sender);
        //        file($url);
        //
        //
        //
        //
        //        $this->session->set_flashdata('success_message', 'Leave Approved successfully');
        //        redirect(base_url() . "Leave_Transaction/Leave_Approve");
    }

    //sms
    public function sms()
    {

        // $sender = "Name";
        // $recipient = $user_details[$x]->contact_no;
        // $message = 'Dear Customer';

        // $url = 'http://127.0.0.1:9333/ozeki?';
        // $url .= "action=sendMessage";
        // $url .= "&login=admin";
        // $url .= "&password=abc123";
        // $url .= "&recepient=" . urlencode($recipient);
        // $url .= "&messageData=" . urlencode($message);
        // $url .= "&sender=" . urlencode($sender);
        // file($url);
    }

    /*
     * Reject Leave request
     */

    public function noti($ID)
    {


        //        var_dump($ID);die;

        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_Display' => 0
        );

        $whereArr = array("ID" => $ID);
        $result = $this->Db_model->updateData("tbl_notifications", $data, $whereArr);

        //        $this->session->set_flashdata('success_message', 'Leave Reject successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Approve");
    }

    public function reject($ID)
    {


        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_pending' => 0,
            'Is_Approve' => 0,
            'Is_Cancel' => 1,
            'Approved_by' => $Emp,
        );

        $whereArr = array("LV_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);



        //        -------- Leave Allocation Update
        date_default_timezone_set('Asia/Colombo');
        $date = date_create();
        $year = date("Y");

        $LTYpe = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID = $ID");
        $Emp_LV = $LTYpe[0]->EmpNo;
        $LeaveType = $LTYpe[0]->Lv_T_ID;
        $tbl_leave_count = $LTYpe[0]->Leave_Count;

        $Balance_Usd = $this->Db_model->getfilteredData("select Balance,Used,Lv_T_ID from tbl_leave_allocation where EmpNo=$Emp_LV and Year=$year and Lv_T_ID=$LeaveType ");
        //                    var_dump($Balance_Usd);die;
        $Day_type = $tbl_leave_count;
        $Balance = $Balance_Usd[0]->Balance + $Day_type;



        $Used = $Balance_Usd[0]->Used - $Day_type;
        if($Used < 0){
            $Used = 0;  
        }
        $Lv_T_ID = $Balance_Usd[0]->Lv_T_ID;

        $data_arr = array("Balance" => $Balance, "Used" => $Used);

        $whereArray = array("EmpNo" => $Emp_LV, "Lv_T_ID" => $Lv_T_ID);
        $result = $this->Db_model->updateData("tbl_leave_allocation", $data_arr, $whereArray);

        $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Grp_ID,tbl_empmaster.Emp_Full_Name,tbl_empmaster.E_mail FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$Emp_LV'");
        $groupid = $empname1[0]->Grp_ID;
        $employee_email = $empname1[0]->E_mail;
        $empname = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Sup_ID FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$groupid' ");
        $supid = $empname[0]->Sup_ID;
       
        $supplier_n = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Emp_Full_Name FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$supid' ");
        $supplier_name = $supplier_n[0]->Emp_Full_Name;
        // $config = array(
        //                         'protocol' => 'smtp',
        //                         'smtp_host' => 'mail.hrislkonline.com',
        //                         'smtp_user' => 'noreply@webx.hrislkonline.com',
        //                         'smtp_pass' => 'wxK]LSft*ED}',
        //                         'smtp_port' => 587,
        //                         'charset' => 'utf-8',
        //                         'mailtype' => 'html',
        //                          'wordwrap' => TRUE,
        //                         'newline' => "\r\n",
        //                     );

        //                     $this->load->library("email");
        //                     $this->email->initialize($config);

        //                     $this->email->from("noreply@webx.hrislkonline.com");
        //                     $this->email->to($employee_email);
        //                     $this->email->message("Leave Request Reject By '$supplier_name'");
        //                     $this->email->subject("Leave Request");

        //                     if ($this->email->send()) {
        //                         echo "Success";
        //                     } else {
        //                         echo "Failed: " . $this->email->print_debugger();
        //                     }
                            
                            
       
        //  $config = array(
        //                         'protocol' => 'smtp',
        //                         'smtp_host' => 'mail.hrislkonline.com',
        //                         'smtp_user' => 'noreply@webx.hrislkonline.com',
        //                         'smtp_pass' => 'wxK]LSft*ED}',
        //                         'smtp_port' => 587,
        //                         'charset' => 'utf-8',
        //                         'mailtype' => 'html',
        //                         'wordwrap' => TRUE,
        //                         'newline' => "\r\n", // Use 'tls' if required by the server; if issues persist, try 'ssl' or omit this line.
        //                     );
        //                     $this->load->library('email', $config); // Load email with config
        //                     $this->email->from('mail@vfthris.com');
        //                     $this->email->to($employee_email);
        //                     $this->email->message("Leave Request Rejected By '$supplier_name'");
        //                     $this->email->subject("Leave Request");
        //                     if ($this->email->send()) {
        //                         echo "Email sent successfully.";
        //                     } else {
        //                         echo $this->email->print_debugger();
        //                     }

        $advData = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry WHERE tbl_leave_entry.LV_ID = '$ID'");
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
            $mail->Subject = "VFT Cloud: Leave Rejected";

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
                            <h1>Leave Rejected</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-body">
                            <h2>Dear ' . $empname1[0]->username . ',</h2>
                            <p>Your leave request has been rejected. Please contact the HR department for further details.</p>
                        <p class="pg1"><a href="https://webx.hrislkonline.com/Leave_Transaction/Leave_Request/" class="button">View Rejected Leave</a></p>
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

        $this->session->set_flashdata('success_message', 'Leave Reject successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Approve");
    }

    public function reject_Ind($ID) {

        $leave_entry_data= $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID=$ID");
        $Is_Approve = $leave_entry_data[0]->Is_Approve;
        $Is_Cancel = $leave_entry_data[0]->Is_Cancel;

        if($Is_Approve == 1){
            $this->session->set_flashdata('error_message', 'Leave Already Approved');
            redirect(base_url() . "Leave_Transaction/Leave_Request");
        }elseif($Is_Cancel == 1){
            $this->session->set_flashdata('error_message', 'Leave Already Cancelled');
            redirect(base_url() . "Leave_Transaction/Leave_Request");
        }else{
        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $data = array(
            'Is_pending' => 0,
            'Is_Approve' => 0,
            'Is_Cancel' => 1,
            'Approved_by' => $Emp,
        );

        $whereArr = array("LV_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_leave_entry", $data, $whereArr);

        #Leave Allocation Update
        date_default_timezone_set('Asia/Colombo');
        $date = date_create();
        $year = date("Y");
        $LTYpe = $this->Db_model->getfilteredData("select * from tbl_leave_entry where LV_ID = $ID");
        $Emp_LV = $LTYpe[0]->EmpNo;
        $LeaveType = $LTYpe[0]->Lv_T_ID;
        $Balance_Usd = $this->Db_model->getfilteredData("select Balance,Used,Lv_T_ID from tbl_leave_allocation where EmpNo=$Emp_LV and Year=$year and Lv_T_ID=$LeaveType ");
        $Day_type = 1;
        $Balance = $Balance_Usd[0]->Balance + $Day_type;



        $Used = $Balance_Usd[0]->Used + $Day_type;
        $Lv_T_ID = $Balance_Usd[0]->Lv_T_ID;

        $data_arr = array("Balance" => $Balance, "Used" => $Used);

        $whereArray = array("EmpNo" => $Emp_LV, "Lv_T_ID" => $Lv_T_ID);
        $result = $this->Db_model->updateData("tbl_leave_allocation", $data_arr, $whereArray);

        $this->session->set_flashdata('success_message', 'Leave Reject successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Request");
        }
    }
}
