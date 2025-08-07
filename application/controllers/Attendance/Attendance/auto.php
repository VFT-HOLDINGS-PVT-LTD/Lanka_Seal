<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Auto_Process_New extends CI_Controller
{

    // public function __construct()
    // {
    //     parent::__construct();
    //     if (!($this->session->userdata('login_user'))) {
    //         redirect(base_url() . "");
    //     }
    //     /*
    //      * Load Database model
    //      */
    //     $this->load->model('Db_model', '', TRUE);
    // }

    // /*
    //  * Index page
    //  */

    // public function index()
    // {

    //     $data['title'] = "Attendance Process | HRM System";
    //     $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
    //     $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
    //     $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');



    //     $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT 
    //                                                                 tbl_empmaster.EmpNo
    //                                                             FROM
    //                                                                 tbl_empmaster
    //                                                                     LEFT JOIN
    //                                                                 tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
    //                                                                 where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");


    //     $this->load->view('Attendance/Attendance_Process/index', $data);
    // }

    /*
     * Insert Data
     */
    // public function Test(){
    //     date_default_timezone_set('Asia/Colombo');

    //     $from_date = date('Y-m-01'); // First day of the current month
    //     $to_date = date('Y-m-t'); // Last day of the current month

    //     $query = "UPDATE tbl_individual_roster SET Is_processed = 0 WHERE FDate BETWEEN '".$from_date."' AND '".$to_date."';";

    //     // Run the custom query
    //     $result = $this->Db_model->getUpdateData($query);

    //     if ($result) {
    //         echo "Update successful!";
    //     } else {
    //         echo "Update failed!";
    //     }
    // }

    public function emp_attendance_process()
    {
        $this->load->model('Db_model', '', TRUE);
        date_default_timezone_set('Asia/Colombo');

        // Setting up the date range for the current month
        $from_date = date('Y-m-01');
        $to_date = date('Y-m-t');

        // Reset the processed flag for this month's data
        $this->Db_model->getUpdateData("UPDATE tbl_individual_roster SET Is_processed = 0 WHERE FDate BETWEEN '$from_date' AND '$to_date'");

        // Get autorun settings
        $autorunSettings = $this->get_autorun_settings('initialize_run');
        $autorunSettings2 = $this->get_autorun_settings('shift_allocation_run');

        // If both autorun flags are 0, proceed with the process
        if ($autorunSettings[0]->status_flag == 0 && $autorunSettings2[0]->status_flag == 0) {
            // Set the attendance process run flag to 1
            $this->Db_model->updateData('tbl_autorun_settings', ['status_flag' => '1'], ['status_flag_name' => 'attendance_process_run']);

            // Get employee data for those who need processing
            $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_individual_roster WHERE Is_processed = 0");

            if (!empty($dtEmp['EmpData'])) {
                foreach ($dtEmp['EmpData'] as $emp) {
                    $EmpNo = $emp->EmpNo;
                    $FromDate = $emp->FDate;
                    $ToDate = $emp->TDate;

                    // Initialize default values
                    $InTime = $OutTime = $leave_type = $DayStatus = 'MS';
                    $Nopay = $Nopay_Hrs = $AfterShiftWH = $ED = $lateM = 0;

                    // Skip if the date is invalid
                    if ($FromDate > $ToDate)
                        continue;

                    // Get attendance data (In and Out times)
                    $attendance = $this->get_employee_attendance($EmpNo, $FromDate);
                    $InTime = $attendance[0]->INTime ?? '00:00:00';
                    $OutTime = $attendance[0]->OutTime ?? '00:00:00';

                    // Get shift details
                    $shiftDetails = $this->get_shift_details($EmpNo, $FromDate);
                    $SHFT = $shiftDetails[0]->FTime;
                    $SHTT = $shiftDetails[0]->TTime;
                    $GracePrd = $shiftDetails[0]->GracePrd;

                    // OT Details
                    $OTDetails = $this->get_overtime_details($shiftDetails[0]->ShiftDay, $shiftDetails[0]->ShType);
                    $AfterShift = $OTDetails[0]->AfterShift ?? 0;
                    $MinAS = $OTDetails[0]->MinAS ?? 0;

                    // Handle missing attendance (InTime or OutTime)
                    $this->handle_missing_attendance($attendance, $InTime, $OutTime);

                    // Handle the shift day (OFF or EX)
                    $Day = $this->get_off_day($EmpNo, $FromDate);

                    if ($Day != 'OFF') {
                        // Handle overtime calculations
                        $AfterShiftWH = $this->calculate_overtime($OutTime, $SHTT, $AfterShift, $MinAS);

                        // Check for different attendance statuses
                        if ($InTime == $OutTime || empty($OutTime)) {
                            $DayStatus = 'MS';
                            $Nopay = $Nopay_Hrs = 0;
                        } elseif ($InTime == '' && $OutTime == '') {
                            $DayStatus = 'AB';
                            $Nopay = 1;
                            $Nopay_Hrs = (strtotime($SHTT) - strtotime($SHFT)) / 60;
                        }
                    } else {
                        // Handle days off or exempt
                        $DayStatus = 'OFF';
                    }

                    // Prepare data for updating the attendance record
                    $data_arr = [
                        "InRec" => 1,
                        "InDate" => $FromDate,
                        "InTime" => $InTime,
                        "OutRec" => 1,
                        "OutDate" => $FromDate,
                        "OutTime" => $OutTime,
                        "nopay" => $Nopay,
                        "Is_processed" => 1,
                        "DayStatus" => $DayStatus,
                        "AfterExH" => $AfterShiftWH,
                        "LateSt" => 0,
                        "LateM" => $lateM,
                        "Lv_T_ID" => $leave_type,
                        "EarlyDepMin" => $ED,
                        "NetLateM" => 0,
                        "ApprovedExH" => 0,
                        "nopay_hrs" => $Nopay_Hrs,
                        "Att_Allow" => 1,
                    ];

                    // Update the attendance status in the roster table
                    $this->update_attendance_status($emp, $data_arr);
                }
            }

            // Update autorun settings flag
            $this->Db_model->updateData('tbl_autorun_settings', ['status_flag' => '0'], ['status_flag_name' => 'attendance_process_run']);
        }

        // Send email notification
        if ($this->send_email_notification('attendance_process', 'This is a test email for attendance_process', 'pasinduramesh277@gmail.com')) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
        }
    }

    // Function to fetch autorun settings
    public function get_autorun_settings($flag_name)
    {
        return $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='$flag_name'");
    }

    // Function to get employee attendance (InTime and OutTime)
    public function get_employee_attendance($EmpNo, $FromDate)
    {
        return $this->Db_model->getfilteredData("SELECT MIN(AttTime) AS INTime, MAX(AttTime) AS OutTime, AttDate 
                                              FROM tbl_u_attendancedata 
                                              WHERE Enroll_No='$EmpNo' AND AttDate='$FromDate'");
    }

    // Function to get shift details
    public function get_shift_details($EmpNo, $FromDate)
    {
        return $this->Db_model->getfilteredData("SELECT ID_roster, ShiftCode, ShType, ShiftDay, Day_Type, FTime, TTime, GracePrd 
                                              FROM tbl_individual_roster 
                                              WHERE Is_processed=0 AND EmpNo='$EmpNo' AND FDate='$FromDate'");
    }

    // Function to get overtime details
    public function get_overtime_details($Shift_Day, $ShiftType)
    {
        return $this->Db_model->getfilteredData("SELECT AfterShift, MinAS FROM tbl_ot_pattern_dtl 
                                              WHERE DayCode = '$Shift_Day' AND DUEX = '$ShiftType'");
    }

    // Function to check and handle missing attendance (InTime or OutTime)
    public function handle_missing_attendance($attendance, &$InTime, &$OutTime)
    {
        // Handle missing InTime
        if (empty($attendance[0]->INTime)) {
            $InTime = '00:00:00'; // Default InTime
        } else {
            $InTime = $attendance[0]->INTime;
        }

        // Handle missing OutTime
        if (empty($attendance[0]->OutTime)) {
            $OutTime = '00:00:00'; // Default OutTime
        } else {
            $OutTime = $attendance[0]->OutTime;
        }
    }

    // Function to get the off day (if any)
    public function get_off_day($EmpNo, $FromDate)
    {
        $OFFDAY = $this->Db_model->getfilteredData("SELECT `ShType` FROM tbl_individual_roster WHERE FDate = '$FromDate' AND EmpNo='$EmpNo'");
        return $OFFDAY[0]->ShType ?? 'OFF';
    }

    // Function to calculate overtime
    public function calculate_overtime($OutTime, $SHTT, $AfterShift, $MinAS)
    {
        $OutTimeSrt = strtotime($OutTime);
        $SHEndTime = strtotime($SHTT);
        $iCalcOut = ($OutTimeSrt - $SHEndTime) / 60;

        if ($AfterShift == 1) {
            return $iCalcOut - $MinAS;
        }

        return $iCalcOut;
    }

    // Function to update attendance status
    public function update_attendance_status($emp, $data_arr)
    {
        $this->Db_model->updateData("tbl_individual_roster", $data_arr, ["ID_roster" => $emp->ID_roster]);
    }

    // Function to send email notification
    public function send_email_notification($subject, $message, $to)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'mail.vfthris.com',
            'smtp_user' => 'mail@vfthris.com',
            'smtp_pass' => 'Wlm7?Ux7g[s1',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'charset' => 'utf-8',
            'mailtype' => 'html',
            'newline' => "\r\n",
            'wordwrap' => TRUE
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->from('mail@vfthris.com', 'Your Name');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }

}
?>