<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
class Salary_Advance_req extends CI_Controller {

    public function __construct() {
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

    public function index() {


        $data['title'] = "Salary Advance Request | HRM SYSTEM";
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $Basic_sal = $this->Db_model->getfilteredData("select ((60/100)*(Basic_Salary)) as Basic_Allowed from tbl_empmaster where EmpNo=$Emp");

        $Basic = $Basic_sal[0]->Basic_Allowed;


        $Salary_advance = $this->Db_model->getfilteredData("select Amount from tbl_salary_advance where EmpNo=$Emp and Month=MONTH(CURDATE())");

    

        

        if (empty($Salary_advance[0]->Amount)) {
            $sal_ad = 0;
        } else {
            $sal_ad = $Salary_advance[0]->Amount;
        }

//        var_dump($sal_ad);

        $Allow_ad = ($Basic) - $sal_ad;

//        var_dump($Allow_ad);die;

        $data['sal_advace'] = $Allow_ad;


        $data['Sal_Advance'] = $this->Db_model->getfilteredData("select tbl_empmaster.EmpNo,((60/100)*(tbl_empmaster.Basic_Salary)) as Basic_Allowed,tbl_empmaster.Basic_Salary ,tbl_salary_advance.Amount, tbl_salary_advance.Month  from tbl_empmaster
                                                                inner join
                                                                tbl_salary_advance on tbl_salary_advance.EmpNo = tbl_empmaster.EmpNo
                                                                where tbl_salary_advance.EmpNo = $Emp and tbl_salary_advance.Month=MONTH(CURDATE())");



        $this->load->view('Payroll/Req_Salary_Advance/index', $data);
    }

    public function dropdown() {

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

    public function insert_data1() {

        $Emp = $this->input->post('txt_employee');
        $Request_date = $this->input->post('txt_date');

        $advance = $this->input->post('txt_advance');
        $year = date("Y");
        $month = date("m");

//        var_dump($month);die;

        $Count = count($Emp);

        $SalPrecentage = $this->Db_model->getfilteredData("select (60/100)*(Basic_Salary+Incentive) as totsal from tbl_empmaster where EmpNo=$Emp");

        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_salary_advance where EmpNo=$Emp and Year=$year and month=$month");

//        if ($advance > $SalPrecentage[0]->totsal) {
//            $this->session->set_flashdata('error_message', 'Employee cannot apply more than salary precentage (60%)');
//        } 
        if ($HasRow[0]->HasRow > 0) {
            $this->session->set_flashdata('error_message', 'Employee already applied salary advance');
        } else {
            for ($i = 0; $i < $Count; $i++) {
                $data = array(
                    array(
                        'EmpNo' => $Emp,
                        'Amount' => $advance,
                        'Request_Date' => $Request_date,
                        'Year' => $year,
                        'Month' => $month,
                        'Is_pending' => 1,
                ));
                $this->db->insert_batch('tbl_salary_advance', $data);
                $this->session->set_flashdata('success_message', 'New Salary advance added successfully');
            }
        }
        redirect('/Payroll/Salary_Advance_req/index');
    }

    public function insert_data()
    {

        $Emp = $this->input->post('txt_employee');
        $Request_date = $this->input->post('txt_date');

        $advance = $this->input->post('txt_advance');
        $year = date("Y");
        $month = date("m");

        // echo $Emp;
        // echo "<br/>";
        // echo $Request_date;
        // echo "<br/>";
        // echo $advance;
        // echo "<br/>";

//        var_dump($month);die;
        $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$Emp'";
        $EmpData = $this->Db_model->getfilteredData($string);

        $Count = count($EmpData);

        // echo $Count;

        $SalPrecentage = $this->Db_model->getfilteredData("select (60/100)*(Basic_Salary+Incentive) as totsal from tbl_empmaster where EmpNo=$Emp");

        $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_salary_advance where EmpNo=$Emp and Year=$year and month=$month");
        $EmpG = $this->Db_model->getfilteredData("select Grp_ID from tbl_empmaster where EmpNo = $Emp ");
        //        var_dump($EmpG);
        $grpID = $EmpG[0]->Grp_ID;
        $Sup_Data = $this->Db_model->getfilteredData("select Sup_ID from tbl_emp_group where Grp_ID =$grpID; ");

        $Sup_ID = $Sup_Data[0]->Sup_ID;
        if ($advance > $SalPrecentage[0]->totsal) {
            // redirect('Payroll/Salary_Advance_req/index');
            $this->session->set_flashdata('error_message', 'Employee cannot apply more than salary precentage (60%)');
            redirect(base_url() . 'Pay/Salary_Advance_req');
        } else {
            if ($HasRow[0]->HasRow > 0) {
                $this->session->set_flashdata('error_message', 'Employee already applied salary advance');
                // $this->load->view('Payroll/Req_Salary_Advance/index');
                echo "Employee already applied salary advance";
            } else {
                for ($i = 0; $i < $Count; $i++) {
                    $data = array(
                        array(
                            'EmpNo' => $Emp,
                            'Amount' => $advance,
                            'Request_Date' => $Request_date,
                            'Year' => $year,
                            'Month' => $month,
                            'Is_pending' => 1,
                            'Sup_AD_APP' => $Sup_ID,
                            'Is_Sup_AD_APP' => 0
                        ));
                    $this->db->insert_batch('tbl_salary_advance', $data);
                }
                // redirect('Payroll/Salary_Advance_req/index');
                $empname1 = $this->Db_model->getfilteredData("SELECT tbl_empmaster.Grp_ID,tbl_empmaster.Emp_Full_Name FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$Emp'");
                $groupid = $empname1[0]->Grp_ID;
                $empname = $this->Db_model->getfilteredData("SELECT tbl_emp_group.Sup_ID FROM tbl_emp_group WHERE tbl_emp_group.Grp_ID = '$groupid' ");
                $supid = $empname[0]->Sup_ID;
                $supemail = $this->Db_model->getfilteredData("SELECT tbl_empmaster.E_mail,tbl_empmaster.username FROM tbl_empmaster WHERE tbl_empmaster.EmpNo = '$supid' ");
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
                    $mail->Subject = "VFT Cloud: Salary advance request";
    
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
                        <h1>Salary Advance Request</h1>
                    </td>
                </tr>
                <tr>
                    <td class="email-body">
                        <h2>Dear ' . $supemail[0]->username . ',</h2>
                        <p>Employee <strong>' . $empname1[0]->Emp_Full_Name . '</strong> (Employee ID: <strong>' . $Emp . '</strong>) has requested salary advance.</p>
                    <p>Please review the salary advance request and take the necessary action.</p>
                    <p class="pg1"><a href="https://webx.hrislkonline.com/Pay/Salary_Advance_Sup_Approve/" class="button">View Salary Advance Request</a></p>
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
                $this->session->set_flashdata('success_message', 'New Salary advance added successfully');
                redirect(base_url() . 'Pay/Salary_Advance_req');
                // echo "New Salary advance added successfully";
            }
        }


    }

}
