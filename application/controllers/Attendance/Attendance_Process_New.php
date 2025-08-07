<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_Process_New extends CI_Controller
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
        $this->load->model('Db_model', '', true);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Attendance Process | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");

        $this->load->view('Attendance/Attendance_Process/index', $data);
    }

    public function re_process()
    {
        $data['title'] = "Attendance Process | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');

        $data['sh_employees'] = $this->Db_model->getfilteredData("SELECT
                                                                    tbl_empmaster.EmpNo
                                                                FROM
                                                                    tbl_empmaster
                                                                        LEFT JOIN
                                                                    tbl_individual_roster ON tbl_individual_roster.EmpNo = tbl_empmaster.EmpNo
                                                                    where tbl_individual_roster.EmpNo is null AND tbl_empmaster.status=1 and Active_process=1");

        $this->load->view('Attendance/Attendance_REProcess/index', $data);
    }

    /*
     * Insert Data
     */



    public function emp_attendance_process()
    {

        // date_default_timezone_set('Asia/Colombo');
        /*
         * Get Employee Data
         * Emp no , EPF No, Roster Type, Roster Pattern Code, Status
         */
        //        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Enroll_No, EPFNO,RosterCode, Status  FROM  tbl_empmaster where status=1");
        // $dtEmp['EmpData'] = $this->Db_model->getfilteredData("select * from tbl_individual_roster where Is_processed = 0");

        $month = $this->input->post('cmb_month');
        if (empty($month)) {
            $this->session->set_flashdata('error_message', 'Please Select Month');
            redirect('/Attendance/Attendance_Process_New');
        } else {
            date_default_timezone_set('Asia/Colombo');
            $year = date("Y");
            $HasRow = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) AS HasRow FROM tbl_individual_roster WHERE EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year");
            if (!empty($HasRow)) {


                $dtEmp['EmpData'] = $this->Db_model->getfilteredData("select * from tbl_individual_roster where EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year");

                $AfterShift = 0;

        $AfterShift = 0;

        if (!empty($dtEmp['EmpData'])) {

            for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {
                $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;

                $FromDate = $dtEmp['EmpData'][$x]->FDate;
                $ToDate = $dtEmp['EmpData'][$x]->TDate;
                //Check If From date less than to Date
                if ($FromDate <= $ToDate) {
                    $settings = $this->Db_model->getfilteredData("SELECT tbl_setting.Group_id,tbl_setting.Ot_m,tbl_setting.Ot_e,tbl_setting.Ot_d_Late,
                    tbl_setting.Late,tbl_setting.Ed,tbl_setting.Min_time_t_ot_m,tbl_setting.Min_time_t_ot_e,
                    tbl_setting.late_Grs_prd,tbl_setting.`Round`,tbl_setting.Hd_d_from,tbl_setting.Dot_f_holyday,tbl_setting.Dot_f_offday
                     FROM tbl_setting INNER JOIN tbl_emp_group ON tbl_setting.Group_id = tbl_emp_group.Grp_ID
                     INNER JOIN tbl_empmaster ON tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID WHERE tbl_empmaster.EmpNo = '$EmpNo'");
                    $ApprovedExH = 0;
                    $DayStatus = "not";
                    $ID_Roster = '';
                    $InDate = '';
                    $InTime = '';
                    $OutDate = '';
                    $OutTime = '';

                    $from_date = '';
                    $from_time = '';
                    $to_date = '';
                    $to_time = '';
                    $Day_Type = 0;
                    $lateM = '';
                    $ED = '';
                    $DayStatus = '';
                    $AfterShiftWH = '';
                    $BeforeShift = '';
                    $DOT = '';
                    $Late_Status = 0;
                    $NetLateM = 0;


                    // **************************************************************************************//
                    // tbl_individual_roster eken shift details tika gannawa
                    $ShiftDetails['shift'] = $this->Db_model->getfilteredData("select `ID_Roster`,`ShType`,`ShiftDay`,`FDate`,`FTime`,`TDate`,`TTime`,`GracePrd`,`HDSession` from tbl_individual_roster where FDate = '$FromDate' AND EmpNo = '$EmpNo' ");
                    $ID_Roster = $ShiftDetails['shift'][0]->ID_Roster;
                    $shift_type = $ShiftDetails['shift'][0]->ShType;
                    $shift_day = $ShiftDetails['shift'][0]->ShiftDay;
                    $from_date = $ShiftDetails['shift'][0]->FDate;
                    $from_time = $ShiftDetails['shift'][0]->FTime;
                    $to_date = $ShiftDetails['shift'][0]->TDate;
                    $to_time = $ShiftDetails['shift'][0]->TTime;
                    $GracePrd = $ShiftDetails['shift'][0]->GracePrd;
                    $cutofftime = $ShiftDetails['shift'][0]->HDSession;


                    //duty dawasaska samnyen yana widiya
                    if ($shift_type == "DU") {
                        $InDate = '';
                        $InTime = '';
                        $OutDate = '';
                        $OutTime = '';


                        $lateM = '';
                        $ED = '';
                        $DayStatus = '';
                        $AfterShiftWH = '';
                        $DOT = '';
                        // Get the CheckIN
                        $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                        tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                        $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                        $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                        // Get the CheckOut
                        $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                        tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '12:00:00') ");
                        $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                        $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;

                        // Out Ekak nethnm check nextday(1st nextDay)
                        if ($FromDate != $to_date) {
                            // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                            $newDate = $to_date;

                            // Get the CheckOut in the nextDay (before 9am)
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'11:59:00' AND Status='1' ");//update the 9 to 11.59 
                            // $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                            // tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' "); old-code
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                        }

                        if ($InTime != '' && $OutTime != '') {
                            $fromtime = $InDate . " " . $InTime;
                            $totime = $OutDate . " " . $OutTime;
                            $timestamp1 = strtotime($fromtime);
                            $timestamp2 = strtotime($totime);
                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                            $time_difference_minutes = $time_difference_seconds / 60;
                            if ($time_difference_minutes < 60) {
                                $OutDate = '';
                                $OutTime = '';
                            }
                            //ms wela thiyenne out time ekada balanw
                            $fromtime = $to_date . " " . $to_time;
                            $deduct_fromtime = strtotime($fromtime . " -2 hour");
                            $plus_fromtime = strtotime($fromtime . " +2 hour");
                            $ct = $InDate . " " . $InTime;
                            $check_time = strtotime($ct);
                            if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                $OutDate = $InDate;
                                $OutTime = $InTime;
                                $InDate = '';
                                $InTime = '';
                            }
                        }



                        $lateM = 0;
                        // $BeforeShift = 0;
                        $Late_Status = 0;
                        $NetLateM = 0;
                        $ED = 0;
                        $EDF = 0;
                        $Att_Allowance = 1;
                        $Nopay = 0;
                        $AfterShiftWH = 0;
                        $lateM = 0; //late minutes
                        // $ED = 0; //ED minutes
                        $iCalcHaffT = 0;

                        if ($InTime != $OutTime && $shift_type == 'DU' && $OutTime != '') {
                            // date samanam
                            $iCalcHaffED = 0;
                            $iCalcHaff = 0;
                            if ($settings[0]->Ed == 1) {

                                $fromtime = $to_date . " " . $to_time;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($totime);
                                $timestamp2 = strtotime($fromtime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                $iCalcHaffED = round($time_difference_minutes, 2);
                                $ED = $iCalcHaffED;

                                // kalin gihhilanm haff day ekak thiynwda balanna
                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                    if ($cutofftime != '00:00:00') {

                                        $fromtime = $from_date . " " . $cutofftime;
                                        $totime = $OutDate . " " . $OutTime;
                                        $timestamp1 = strtotime($totime);
                                        $timestamp2 = strtotime($fromtime);
                                        $time_difference_seconds = ($timestamp2 - $timestamp1);
                                        $time_difference_minutes = $time_difference_seconds / 60;
                                        $iCalcHaff = round($time_difference_minutes, 2);
                                        $DayStatus = 'HFD';
                                    }
                                    if ($iCalcHaff <= 0) {
                                        // $iCalcHaff = 0;
                                        $ED = 0;
                                    } else {
                                        $ED = $iCalcHaff;
                                    }
                                }
                            }
                        }

                        $ApprovedExH = 0;
                        $SH_EX_OT = 0;
                        $icalData = 0;
                        if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'DU' && $OutTime != "00:00:00") {

                            // group eke evening ot thiyenawanan
                            if ($settings[0]->Ot_e == 1) {

                                //min time to ot eka hada gannawa group setting table eken
                                $min_time_to_ot = $settings[0]->Min_time_t_ot_e;
                                $dateTime = new DateTime($to_time);
                                $dateTime->add(new DateInterval('PT' . $min_time_to_ot . 'M'));
                                $shift_evning = $dateTime->format('H:i:s');

                                if ($shift_evning < $OutTime) {
                                    $fromtime = $to_date . " " . $to_time;
                                    $totime = $OutDate . " " . $OutTime;
                                    $timestamp1 = strtotime($fromtime);
                                    $timestamp2 = strtotime($totime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $icalData = round($time_difference_minutes, 2);
                                }
                            }

                            // Out wunma passe OT
                            if ($icalData >= 0) {
                                $AfterShiftWH = $icalData;
                            } else {
                                $AfterShiftWH = 0;
                            }

                            // **************************************************************************************//
                            // kalin giya ewa (ED)


                        }


                        // New Late with HFD
                        $iCalclate = 0;
                        $iCalc = 0;

                        if ($InTime != '' && $InTime != $OutTime && $shift_type == 'DU') {

                            if ($settings[0]->Late == 1) {

                                $late_grass_period = $settings[0]->late_Grs_prd;
                                $dateTime = new DateTime($from_time);
                                $dateTime->add(new DateInterval('PT' . $late_grass_period . 'M'));
                                $late_from_time_with_grsprd = $dateTime->format('H:i:s');

                                $fromtime = $from_date . " " . $late_from_time_with_grsprd;
                                $totime = $InDate . " " . $InTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                $iCalclate = round($time_difference_minutes, 2);
                                $lateM = $iCalclate;
                                // kalin gihhilanm haff day ekak thiynwda balanna
                                $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                    if ($cutofftime != '00:00:00') {
                                        $fromtime = $from_date . " " . $cutofftime;
                                        $totime = $InDate . " " . $InTime;
                                        $timestamp1 = strtotime($fromtime);
                                        $timestamp2 = strtotime($totime);
                                        $time_difference_seconds = ($timestamp2 - $timestamp1);
                                        $time_difference_minutes = $time_difference_seconds / 60;
                                        $iCalc = round($time_difference_minutes, 2);
                                        $DayStatus = 'HFD';
                                    }
                                    if ($iCalc < 0) {
                                        $lateM = 0;
                                    } else {
                                        $lateM = $iCalc;
                                    }
                                }
                                // $lateM += $iCalc;
                                // if ($iCalc < 1) {
                                //     $lateM = 0;
                                // }
                            }
                        }

                        $AfterShiftWH = round($AfterShiftWH, 2);
                        $lateM = round($lateM, 2);
                        if ($settings[0]->Ot_d_Late == 1) {
                            $deduction = ($AfterShiftWH - $lateM);

                            if ($deduction < 0) {
                                $lateM = abs($deduction);
                            }
                            if ($deduction == 0) {
                                $lateM = 0;
                                $AfterShiftWH = 0;
                            }
                            if ($deduction > 0) {
                                $AfterShiftWH = abs($deduction);
                            }
                        }
                    }

                    if ($shift_type == "EX") {

                        //holiday walata double ot ynwd balnw
                        if ($settings[0]->Dot_f_holyday == 1) {

                            $InDate = '';
                            $InTime = '';
                            $OutDate = '';
                            $OutTime = '';


                            $lateM = '';
                            $ED = '';
                            $DayStatus = '';
                            $AfterShiftWH = '';
                            $DOT = '';
                            // Get the CheckIN
                            $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                            $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                            $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                            // Get the CheckOut
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '13:00:00') ");
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;


                            // Out Ekak nethnm check nextday(1st nextDay)
                            if ($FromDate != $to_date) {
                                // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                $newDate = $to_date;

                                // Get the CheckOut in the nextDay (before 9am)
                                $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' AND Status='1' ");
                                $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            }

                            if ($InTime != '' && $OutTime != '') {
                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                if ($time_difference_minutes < 60) {
                                    $OutDate = '';
                                    $OutTime = '';
                                }
                                //ms wela thiyenne out time ekada balanw
                                $fromtime = $to_date . " " . $to_time;
                                $deduct_fromtime = strtotime($fromtime . " -2 hour");
                                $plus_fromtime = strtotime($fromtime . " +2 hour");
                                $ct = $InDate . " " . $InTime;
                                $check_time = strtotime($ct);
                                if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                    $OutDate = $InDate;
                                    $OutTime = $InTime;
                                    $InDate = '';
                                    $InTime = '';
                                }
                            }

                            $icaldot = 0;
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'EX' && $OutTime != "00:00:00") {
                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                $icaldot = round($time_difference_minutes, 2);
                            }
                            if ($icaldot >= 0) {
                                $DOT = $icaldot;
                            } else {
                                $DOT = 0;
                            }

                            //   dot naththan samanya process eka 
                        } else {
                            $InDate = '';
                            $InTime = '';
                            $OutDate = '';
                            $OutTime = '';


                            $lateM = '';
                            $ED = '';
                            $DayStatus = '';
                            $AfterShiftWH = '';
                            // Get the CheckIN
                            $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                            $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                            $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                            // Get the CheckOut
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '13:00:00') ");
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;


                            // Out Ekak nethnm check nextday(1st nextDay)
                            if ($FromDate != $to_date) {
                                // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                $newDate = $to_date;

                                // Get the CheckOut in the nextDay (before 9am)
                                $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' AND Status='1' ");
                                $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            }
                            if ($InTime != '' && $OutTime != '') {
                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                if ($time_difference_minutes < 60) {
                                    $OutDate = '';
                                    $OutTime = '';
                                }
                                //ms wela thiyenne out time ekada balanw
                                $fromtime = $to_date . " " . $to_time;
                                $deduct_fromtime = strtotime($fromtime . " -2 hour");
                                $plus_fromtime = strtotime($fromtime . " +2 hour");
                                $ct = $InDate . " " . $InTime;
                                $check_time = strtotime($ct);
                                if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                    $OutDate = $InDate;
                                    $OutTime = $InTime;
                                    $InDate = '';
                                    $InTime = '';
                                }
                            }

                            $lateM = 0;
                            // $BeforeShift = 0;
                            $Late_Status = 0;
                            $NetLateM = 0;
                            $ED = 0;
                            $EDF = 0;
                            $Att_Allowance = 1;
                            $Nopay = 0;
                            $AfterShiftWH = 0;
                            $lateM = 0; //late minutes
                            // $ED = 0; //ED minutes
                            $iCalcHaffT = 0;

                            if ($InTime != $OutTime && $shift_type == 'DU' && $OutTime != '') {
                                // date samanam
                                $iCalcHaffED = 0;
                                $iCalcHaff = 0;
                                if ($settings[0]->Ed == 1) {

                                    $fromtime = $to_date . " " . $to_time;
                                    $totime = $OutDate . " " . $OutTime;
                                    $timestamp1 = strtotime($totime);
                                    $timestamp2 = strtotime($fromtime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $iCalcHaffED = round($time_difference_minutes, 2);
                                    $ED = $iCalcHaffED;
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                        if ($cutofftime != '00:00:00') {

                                            $fromtime = $from_date . " " . $cutofftime;
                                            $totime = $OutDate . " " . $OutTime;
                                            $timestamp1 = strtotime($totime);
                                            $timestamp2 = strtotime($fromtime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalcHaff = round($time_difference_minutes, 2);
                                            $DayStatus = 'HFD';
                                        }
                                        if ($iCalcHaff <= 0) {
                                            $ED = 0;
                                        } else {
                                            $ED = $iCalcHaff;
                                        }
                                    }
                                }
                            }

                            $ApprovedExH = 0;
                            $SH_EX_OT = 0;
                            $icalData = 0;
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'EX' && $OutTime != "00:00:00") {

                                // group eke evening ot thiyenawanan
                                if ($settings[0]->Ot_e == 1) {

                                    //min time to ot eka hada gannawa group setting table eken
                                    $min_time_to_ot = $settings[0]->Min_time_t_ot_e;
                                    $dateTime = new DateTime($to_time);
                                    $dateTime->add(new DateInterval('PT' . $min_time_to_ot . 'M'));
                                    $shift_evning = $dateTime->format('H:i:s');

                                    if ($shift_evning < $OutTime) {
                                        $fromtime = $to_date . " " . $to_time;
                                        $totime = $OutDate . " " . $OutTime;
                                        $timestamp1 = strtotime($fromtime);
                                        $timestamp2 = strtotime($totime);
                                        $time_difference_seconds = ($timestamp2 - $timestamp1);
                                        $time_difference_minutes = $time_difference_seconds / 60;
                                        $icalData = round($time_difference_minutes, 2);
                                    }
                                }

                                // Out wunma passe OT
                                if ($icalData >= 0) {
                                    $AfterShiftWH = $icalData;
                                } else {
                                    $AfterShiftWH = 0;
                                }

                                // **************************************************************************************//
                                // kalin giya ewa (ED)


                            }


                            // New Late with HFD
                            $iCalclate = 0;
                            $iCalc = 0;
                            if ($InTime != '' && $InTime != $OutTime && $shift_type == 'EX' || $OutTime != '' && $shift_type == 'EX') {


                                if ($settings[0]->Late == 1) {

                                    $late_grass_period = $settings[0]->late_Grs_prd;
                                    $dateTime = new DateTime($from_time);
                                    $dateTime->add(new DateInterval('PT' . $late_grass_period . 'M'));
                                    $late_from_time_with_grsprd = $dateTime->format('H:i:s');

                                    $fromtime = $from_date . " " . $late_from_time_with_grsprd;
                                    $totime = $InDate . " " . $InTime;
                                    $timestamp1 = strtotime($fromtime);
                                    $timestamp2 = strtotime($totime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $iCalclate = round($time_difference_minutes, 2);
                                    $lateM = $iCalclate;
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                        if ($cutofftime != '00:00:00') {
                                            $fromtime = $from_date . " " . $cutofftime;
                                            $totime = $InDate . " " . $InTime;
                                            $timestamp1 = strtotime($fromtime);
                                            $timestamp2 = strtotime($totime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalc = round($time_difference_minutes, 2);
                                            $DayStatus = 'HFD';
                                        }
                                    }
                                    if ($iCalc < 0) {
                                        $lateM = 0;
                                    } else {
                                        $lateM = $iCalc;
                                    }
                                }
                            }

                            $AfterShiftWH = round($AfterShiftWH, 2);
                            $lateM = round($lateM, 2);
                            if ($settings[0]->Ot_d_Late == 1) {
                                $deduction = ($AfterShiftWH - $lateM);

                                if ($deduction < 0) {
                                    $lateM = abs($deduction);
                                }
                                if ($deduction == 0) {
                                    $lateM = 0;
                                    $AfterShiftWH = 0;
                                }
                                if ($deduction > 0) {
                                    $AfterShiftWH = abs($deduction);
                                }
                            }
                        }
                    }

                    if ($shift_type == "OFF") {


                        //holiday walata double ot ynwd balnw
                        if ($settings[0]->Dot_f_offday == 1) {

                            $InDate = '';
                            $InTime = '';
                            $OutDate = '';
                            $OutTime = '';


                            $lateM = '';
                            $ED = '';
                            $DayStatus = '';
                            $AfterShiftWH = '';
                            $DOT = '';
                            // Get the CheckIN
                            $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                            $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                            $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                            // Get the CheckOut
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '13:00:00') ");
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;


                            // Out Ekak nethnm check nextday(1st nextDay)
                            if ($FromDate != $to_date) {
                                // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                $newDate = $to_date;

                                // Get the CheckOut in the nextDay (before 9am)
                                $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' AND Status='1' ");
                                $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            }

                            if ($InTime != '' && $OutTime != '') {
                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                if ($time_difference_minutes < 60) {
                                    $OutDate = '';
                                    $OutTime = '';
                                }
                                //ms wela thiyenne out time ekada balanw
                                $fromtime = $to_date . " " . $to_time;
                                $deduct_fromtime = strtotime($fromtime . " -2 hour");
                                $plus_fromtime = strtotime($fromtime . " +2 hour");
                                $ct = $InDate . " " . $InTime;
                                $check_time = strtotime($ct);
                                if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                    $OutDate = $InDate;
                                    $OutTime = $InTime;
                                    $InDate = '';
                                    $InTime = '';
                                }
                            }

                            $icaldot = 0;
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'OFF' && $OutTime != "00:00:00") {

                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                $icaldot = round($time_difference_minutes, 2);
                            }
                            if ($icaldot >= 0) {
                                $DOT = $icaldot;
                            } else {
                                $DOT = 0;
                            }

                            //   dot naththan samanya process eka 
                        } 
                        else {

                            $InDate = '';
                            $InTime = '';
                            $OutDate = '';
                            $OutTime = '';


                            $lateM = '';
                            $ED = '';
                            $DayStatus = '';
                            $AfterShiftWH = '';
                            // Get the CheckIN
                            $dt_in_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='0' OR AttTime <'14:00:00') ");
                            $InDate = $dt_in_Records['dt_Records'][0]->AttDate;
                            $InTime = $dt_in_Records['dt_Records'][0]->INTime;

                            // Get the CheckOut
                            $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from 
                            tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND (Status='1' OR AttTime > '13:00:00') ");
                            $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                            $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;


                            // Out Ekak nethnm check nextday(1st nextDay)
                            if ($FromDate != $to_date) {
                                // $newDate = date('Y-m-d', strtotime($FromDate . ' +1 day'));
                                $newDate = $to_date;

                                // Get the CheckOut in the nextDay (before 9am)
                                $dt_out_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as OutTime,Enroll_No,AttDate from 
                                tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='$newDate' AND AttTime <'09:00:00' AND Status='1' ");
                                $OutDate = $dt_out_Records['dt_out_Records'][0]->AttDate;
                                $OutTime = $dt_out_Records['dt_out_Records'][0]->OutTime;
                            }
                            if ($InTime != '' && $OutTime != '') {
                                $fromtime = $InDate . " " . $InTime;
                                $totime = $OutDate . " " . $OutTime;
                                $timestamp1 = strtotime($fromtime);
                                $timestamp2 = strtotime($totime);
                                $time_difference_seconds = ($timestamp2 - $timestamp1);
                                $time_difference_minutes = $time_difference_seconds / 60;
                                if ($time_difference_minutes < 60) {
                                    $OutDate = '';
                                    $OutTime = '';
                                }
                                //ms wela thiyenne out time ekada balanw
                                $fromtime = $to_date . " " . $to_time;
                                $deduct_fromtime = strtotime($fromtime . " -2 hour");
                                $plus_fromtime = strtotime($fromtime . " +2 hour");
                                $ct = $InDate . " " . $InTime;
                                $check_time = strtotime($ct);
                                if ($deduct_fromtime <= $check_time && $check_time <= $plus_fromtime) {
                                    $OutDate = $InDate;
                                    $OutTime = $InTime;
                                    $InDate = '';
                                    $InTime = '';
                                }
                            }

                            $lateM = 0;
                            // $BeforeShift = 0;
                            $Late_Status = 0;
                            $NetLateM = 0;
                            $ED = 0;
                            $EDF = 0;
                            $Att_Allowance = 1;
                            $Nopay = 0;
                            $AfterShiftWH = 0;
                            $lateM = 0; //late minutes
                            // $ED = 0; //ED minutes
                            $iCalcHaffT = 0;

                            if ($InTime != $OutTime && $shift_type == 'DU' && $OutTime != '') {
                                // date samanam
                                $iCalcHaffED = 0;
                                $iCalcHaff = 0;
                                if ($settings[0]->Ed == 1) {

                                    $fromtime = $to_date . " " . $to_time;
                                    $totime = $OutDate . " " . $OutTime;
                                    $timestamp1 = strtotime($totime);
                                    $timestamp2 = strtotime($fromtime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $iCalcHaffED = round($time_difference_minutes, 2);
                                    $ED = $iCalcHaffED;
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                        if ($cutofftime != '00:00:00') {

                                            $fromtime = $from_date . " " . $cutofftime;
                                            $totime = $OutDate . " " . $OutTime;
                                            $timestamp1 = strtotime($totime);
                                            $timestamp2 = strtotime($fromtime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalcHaff = round($time_difference_minutes, 2);
                                            $DayStatus = 'HFD';
                                        }
                                        if ($iCalcHaff <= 0) {
                                            $ED = 0;
                                        } else {
                                            $ED = $iCalcHaff;
                                        }
                                    }
                                }
                            }

                            $ApprovedExH = 0;
                            $SH_EX_OT = 0;
                            $icalData = 0;
                            if ($OutTime != '' && $InTime != $OutTime && $InTime != '' && $shift_type == 'OFF' && $OutTime != "00:00:00") {

                                // group eke evening ot thiyenawanan
                                if ($settings[0]->Ot_e == 1) {

                                    //min time to ot eka hada gannawa group setting table eken
                                    $min_time_to_ot = $settings[0]->Min_time_t_ot_e;
                                    $dateTime = new DateTime($to_time);
                                    $dateTime->add(new DateInterval('PT' . $min_time_to_ot . 'M'));
                                    $shift_evning = $dateTime->format('H:i:s');

                                    if ($shift_evning < $OutTime) {
                                        $fromtime = $to_date . " " . $to_time;
                                        $totime = $OutDate . " " . $OutTime;
                                        $timestamp1 = strtotime($fromtime);
                                        $timestamp2 = strtotime($totime);
                                        $time_difference_seconds = ($timestamp2 - $timestamp1);
                                        $time_difference_minutes = $time_difference_seconds / 60;
                                        $icalData = round($time_difference_minutes, 2);
                                    }
                                }

                                // Out wunma passe OT
                                if ($icalData >= 0) {
                                    $AfterShiftWH = $icalData;
                                } else {
                                    $AfterShiftWH = 0;
                                }

                                // **************************************************************************************//

                            }


                            // New Late with HFD
                            $iCalclate = 0;
                            $iCalc = 0;
                            if ($InTime != '' && $InTime != $OutTime && $shift_type == 'OFF' || $OutTime != '' && $shift_type == 'OFF') {


                                if ($settings[0]->Late == 1) {

                                    $late_grass_period = $settings[0]->late_Grs_prd;
                                    $dateTime = new DateTime($from_time);
                                    $dateTime->add(new DateInterval('PT' . $late_grass_period . 'M'));
                                    $late_from_time_with_grsprd = $dateTime->format('H:i:s');

                                    $fromtime = $from_date . " " . $late_from_time_with_grsprd;
                                    $totime = $InDate . " " . $InTime;
                                    $timestamp1 = strtotime($fromtime);
                                    $timestamp2 = strtotime($totime);
                                    $time_difference_seconds = ($timestamp2 - $timestamp1);
                                    $time_difference_minutes = $time_difference_seconds / 60;
                                    $iCalclate = round($time_difference_minutes, 2);
                                    $lateM = $iCalclate;
                                    // kalin gihhilanm haff day ekak thiynwda balanna
                                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                                    if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                                        if ($cutofftime != '00:00:00') {
                                            $fromtime = $from_date . " " . $cutofftime;
                                            $totime = $InDate . " " . $InTime;
                                            $timestamp1 = strtotime($fromtime);
                                            $timestamp2 = strtotime($totime);
                                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                                            $time_difference_minutes = $time_difference_seconds / 60;
                                            $iCalc = round($time_difference_minutes, 2);
                                            $DayStatus = 'HFD';
                                        }
                                    }
                                    if ($iCalc < 0) {
                                        $lateM = 0;
                                    } else {
                                        $lateM = $iCalc;
                                    }
                                }
                            }

                            $AfterShiftWH = round($AfterShiftWH, 2);
                            $lateM = round($lateM, 2);
                            if ($settings[0]->Ot_d_Late == 1) {
                                $deduction = ($AfterShiftWH - $lateM);

                                if ($deduction < 0) {
                                    $lateM = abs($deduction);
                                }
                                if ($deduction == 0) {
                                    $lateM = 0;
                                    $AfterShiftWH = 0;
                                }
                                if ($deduction > 0) {
                                    $AfterShiftWH = abs($deduction);
                                }
                            }
                        }
                    }





                    // **************************************************************************************//
                    if ($InTime == $OutTime || $OutTime == null || $OutTime == '') {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $Day_Type = 0.5;
                    }

                    /*
                     * If In Available & Out Missing
                     */
                    if ($InTime != '' && $InTime == $OutTime) {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $OutTime = "00:00:00";
                        $Day_Type = 0.5;
                    }

                    // If Out Available & In Missing
                    if ($OutTime != '' && $OutTime == $InTime) {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $OutTime = "00:00:00";
                        $Day_Type = 0.5;
                    }

                    // If In Available & Out Missing
                    if ($InTime != '' && $OutTime == '') {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $Day_Type = 0.5;
                    }

                    // If Out Available & In Missing
                    if ($OutTime != '' && $InTime == '') {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $Day_Type = 0.5;
                    }
                    // **************************************************************************************//

                    if ($OutTime == "00:00:00") {
                        $DayStatus = 'MS';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $OutTime = "00:00:00";
                        $Day_Type = 0.5;
                    }

                    if ($InTime != '' && $InTime != $OutTime && $OutTime != '' && ($InTime != '00:00:00' && $OutTime != '00:00:00')) {
                        $Nopay = 0;
                        $DayStatus = 'PR';
                        $Nopay_Hrs = 0;
                        $Day_Type = 1;
                    }

                    // **************************************************************************************//
                    $Nopay_Hrs = 0;
                    // Nopay
                    if ($InTime == '' && $OutTime == '' && $shift_type == 'DU') {
                        $DayStatus = 'AB';
                        $Nopay = 1;
                        $Nopay_Hrs = (((strtotime($to_time) - strtotime($from_time))) / 60);
                        $Day_Type = 1;
                        // if ($InTime == '' && $OutTime == '' && $shift_type == 'EX') {
                        //     $Nopay = 0;
                        //     $Nopay_Hrs = 0;
                        //     $DayStatus = 'EX';
                        // }
                    }
                    if ($InTime == '' && $OutTime == '' && $shift_type == 'EX') {
                        $DayStatus = 'AB';
                        $Nopay = 1;
                        $Nopay_Hrs = (((strtotime($to_time) - strtotime($from_time))) / 60);
                        $Day_Type = 1;
                        // if ($InTime == '' && $OutTime == '' && $shift_type == 'EX') {
                        //     $Nopay = 0;
                        //     $Nopay_Hrs = 0;
                        //     $DayStatus = 'EX';
                        // }
                    }
                    if ($InTime == '' && $OutTime == '' && $shift_type == 'OFF') {
                        $DayStatus = 'OFF';
                        $Nopay = 1;
                        $Nopay_Hrs = (((strtotime($to_time) - strtotime($from_time))) / 60);
                        $Day_Type = 1;
                        // if ($InTime == '' && $OutTime == '' && $shift_type == 'EX') {
                        //     $Nopay = 0;
                        //     $Nopay_Hrs = 0;
                        //     $DayStatus = 'EX';
                        // }
                    }



                    // ===================Start Short Leave

                    // **********************************************Short Leave****************************************//
                    // Get the BreakkIN
                    $dt_Breakin_Records['dt_Records'] = $this->Db_model->getfilteredData("select min(AttTime) as INTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND Status='3' ");
                    $BreakInRecords = $dt_Breakin_Records['dt_Records'][0]->AttDate;
                    $BreakInDate = $dt_Breakin_Records['dt_Records'][0]->AttDate;
                    $BreakInTime = $dt_Breakin_Records['dt_Records'][0]->INTime;
                    $BreakInRec = 1;

                    // Get the BreakOut
                    $dt_Breakout_Records['dt_out_Records'] = $this->Db_model->getfilteredData("select max(AttTime) as OutTime,Enroll_No,AttDate from tbl_u_attendancedata where Enroll_No='$EmpNo' and AttDate='" . $FromDate . "' AND Status='4' ");
                    $BreakOutDate = $dt_Breakout_Records['dt_out_Records'][0]->AttDate;
                    $BreakOutTime = $dt_Breakout_Records['dt_out_Records'][0]->OutTime;
                    $BreakOutRec = 0;
                    $BreakOutRecords = $dt_Breakout_Records['dt_out_Records'][0]->AttDate;

                    // // ShortLeave thani eka [(After)atharameda]
                    if ($BreakInTime != null && $BreakOutTime != null) {
                        
                        $BreakInTime = $dt_Breakin_Records['dt_Records'][0]->INTime;
                        $BreakOutTime = $dt_Breakout_Records['dt_out_Records'][0]->OutTime;

                        //Late(Short)
                        $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                        if (!empty($ShortLeave[0]->Is_Approve)) {
                            $SHFtime = $ShortLeave[0]->from_time;
                            $SHTtime = $ShortLeave[0]->to_time;

                            $BreakOutTimeSrt = strtotime($BreakOutTime);
                            $SHToTimeSrt = strtotime($SHTtime);

                            $iCalcShortLTIntv = ($BreakOutTimeSrt - $SHToTimeSrt) / 60;
                            $DayStatus = 'SL';
                            if ($iCalcShortLTIntv <= 0) {
                                // welawta ewilla

                            } else if ($iCalcShortLTIntv >= 0) {
                                // welatwa ewilla ne(short leave & haffDay ektath passe late)
                                $lateM = $iCalcHaffT + $iCalcShortLTIntv;
                            }
                        }

                        // ED(Short)
                        if (!empty($ShortLeave[0]->Is_Approve)) {
                            $SHFtime = $ShortLeave[0]->from_time;
                            $SHTtime = $ShortLeave[0]->to_time;

                            $BreakInTimeSrt = strtotime($BreakInTime);
                            $SHFromTimeSrt = strtotime($SHFtime);

                             $iCalcShortLTIntvED = ($SHFromTimeSrt - $BreakInTimeSrt) / 60;
                            $DayStatus = 'SL';

                            if ($iCalcShortLTIntvED < 0) {
                                // ee welwta hari ee welwen passe hari gihinm
                                $ED = 0;
                            } else if ($iCalcShortLTIntvED >= 0) {
                                // kalin gihinm
                                // $ED = $EDF + $iCalcShortLTIntvED;
                                $ED = $iCalcShortLTIntvED;
                            }
                            // echo $ED;
                        }
                    } else {
                        // Hawasa ShortLeave thiynwam
                        $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                        if (!empty($ShortLeave[0]->Is_Approve)) {
                            // echo "   okkk";
                            // if ($ShortLeave[0]->to_time >= $totime) {

                            $SHFtime = $ShortLeave[0]->from_time;
                            $SHTtime = $ShortLeave[0]->to_time;

                            // if (($OutTime > '17:30:00') || ($OutTime < '18:00:00')) {
                            // echo $InTime . '-' . $OutTime . ' || ' . $EmpNo;
                            // echo "<br>";
                            // echo $FromDate;
                            // echo "<br>";
                            $OutTimeSrt = strtotime($OutTime);
                            $SHFromTimeSrt = strtotime($SHFtime);

                            $iCalcShortLTED = ($SHFromTimeSrt - $OutTimeSrt) / 60;
                            // echo $iCalcShortLTED;
                            if ($iCalcShortLTED > 0) {
                                $ED = 0;
                                $ED = $iCalcShortLTED;
                                $DayStatus = 'SL';
                            } else {
                                $ED = 0;
                                $DayStatus = 'SL';
                                // echo "2";
                            }
                            // } else {
                            // }
                            // }
                        }

                        // Morning In Time ekata kalin short leave thiywam
                        $ShortLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $EmpNo AND tbl_shortlive.Date = '$FromDate' ");
                        if (!empty($ShortLeave[0]->Is_Approve)) {
                            $SHFtime = $ShortLeave[0]->from_time;
                            $SHTtime = $ShortLeave[0]->to_time;

                            $InTimeSrt = strtotime($InTime);
                            $SHToTimeSrt = strtotime($SHTtime);

                            $iCalcShortLT = ($InTimeSrt - $SHToTimeSrt) / 60;

                            if ($SHFtime <= $fromtime) {
                                if ($iCalcShortLT <= 0) {
                                    // welawta ewilla
                                    $lateM = 0;
                                    $Late_Status = 0;
                                    $DayStatus = 'SL';
                                } else {
                                    // welatwa ewilla ne(short leave ektath passe late /haffDay ne )
                                    $lateM = $iCalcShortLT;
                                    $DayStatus = 'SL';

                                    // echo "2gg";
                                }
                            }
                        }
                        // **********************************************Short Leave****************************************//

                        // $$$$$$$$$$$$$$$$$$$$$$$//
                        // **************************************************************************************//

                    }





                    if ($shift_type == "OFF") {
                        $DayStatus = 'OFF';
                        $Late_Status = 0;
                        $Nopay = 0;
                        $InRecords = $FromDate;
                        $OutDate = $FromDate;
                        $InTime = "00:00:00";
                        $OutTime = "00:00:00";
                    }



                    $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$FromDate' ");
                    if ($Holiday[0]->HasRow == 1) {
                        $DayStatus = 'HD';
                        $Nopay = 0;
                        $Nopay_Hrs = 0;
                        $Att_Allowance = 0;
                        $Day_Type = 0;
                    }
                    $Leave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='1' AND Is_Approve = '1' ");
                    if (!empty($Leave[0]->Is_Approve)) {
                        $Nopay = 0;
                        $DayStatus = 'LV';
                        $Nopay_Hrs = 0;
                        $Att_Allowance = 0;
                        $Day_Type = 1;
                        if ($InTime != '' && $InTime != $OutTime && $OutTime != '') {
                            $Nopay = 0;
                            $DayStatus = 'LV-PR';
                            $Nopay_Hrs = 0;
                            $Day_Type = 1;
                        }
                    }

                    $halfd_late = 0;
                    $HaffDayaLeave = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry where EmpNo = $EmpNo and Leave_Date = '$FromDate' AND Leave_Count='0.5' AND Is_Approve = '1' ");
                    if (!empty($HaffDayaLeave[0]->Is_Approve)) {

                        if ($InTime == '' && $OutTime == '' && $shift_type == 'DU') {

                            $fromtime = $from_date . " " . $cutofftime;
                            $totime = $from_date . " " . $from_time;
                            $timestamp1 = strtotime($totime);
                            $timestamp2 = strtotime($fromtime);
                            $time_difference_seconds = ($timestamp2 - $timestamp1);
                            $time_difference_minutes = $time_difference_seconds / 60;
                            $halfd_late = round($time_difference_minutes, 2);
                            $DayStatus = 'HFD-AB';
                            $lateM = $halfd_late;
                        }
                    }

                    if ($lateM >= 0) {
                        $lateM;
                    } else {
                        $lateM = 0;
                    }

                    if ($ED >= 0) {
                        $ED;
                    } else {
                        $ED = 0;
                    }


                    // echo $ID_Roster;
                    // echo "<br/>";
                    // echo $EmpNo;
                    // echo "<br/>";
                    // echo $FromDate;
                    // echo "<br/>";
                    // echo "from date-" . $from_date;
                    // echo "<br/>";
                    // echo "from time-" . $from_time;
                    // echo "<br/>";
                    // echo "in date-" . $InDate;
                    // echo "<br/>";
                    // echo "in time-" . $InTime;
                    // echo "<br/>";
                    // echo "<br/>";
                    // echo "to date-" . $to_date;
                    // echo "<br/>";
                    // echo "to time-" . $to_time;
                    // echo "<br/>";
                    // echo "out date-" . $OutDate;
                    // echo "<br/>";
                    // echo "out time-" . $OutTime;
                    // echo "<br/>";
                    // echo "Late " . $lateM;
                    // echo "<br/>";
                    // echo "ED " . $ED;
                    // echo "<br/>";
                    // echo "DayStatus " . $DayStatus;
                    // echo "<br/>";
                    // echo "OT " . $AfterShiftWH;
                    // echo "<br/>";
                    // echo "dot" . $DOT;
                    // echo "<br/>";
                    // // // echo "in 3-" . $InmoTime3;
                    // // // echo "<br/>";
                    // // // echo "out 3-" . $OutDate3;
                    // // // echo "<br/>";
                    // // // echo "out 3-" . $OutTime3;
                    // // // echo "<br/>";
                    // // // echo "workhours1-" . $workhours1;
                    // // // echo "<br/>";
                    // // // echo "workhours2-" . $workhours2;
                    // // // echo "<br/>";
                    // // // echo "workhours3-" . $workhours3;
                    // // // echo "<br/>";
                    // // // echo "workhours3-" . $workhours;
                    // // // echo "<br/>";
                    // // // echo "dot1-" . $DOT1;
                    // // // echo "<br/>";
                    // // // echo "dot2-" . $DOT2;
                    // // // echo "<br/>";
                    // // // echo "dot3-" . $DOT3;
                    // // // echo "<br/>";
                    // // // echo "dot-" . $DOT;
                    // // // echo "<br/>";
                    // // // echo "out" . $OutTime;
                    // // // echo "<br/>";
                    // // // echo "outd-" . $OutDate;
                    // echo "<br/>";
                    // echo "<br/>";
                    // echo "<br/>";
                    // echo "<br/>";
                    // die;
                     $data_arr = array("InRec" => 1, "InDate" => $FromDate, "InTime" => $InTime, "OutRec" => 1,"Day_Type" => $Day_Type, "OutDate" => $OutDate, "OutTime" => $OutTime, "nopay" => $Nopay, "Is_processed" => 1, "DayStatus" => $DayStatus, "BeforeExH" => $BeforeShift, "AfterExH" => $AfterShiftWH, "LateSt" => $Late_Status, "LateM" => $lateM, "EarlyDepMin" => $ED, "NetLateM" => $NetLateM, "ApprovedExH" => $ApprovedExH, "nopay_hrs" => $Nopay_Hrs, "Att_Allow" => $Att_Allowance,"DOT" => $DOT);
                     $whereArray = array("ID_roster" => $ID_Roster);
                    $result = $this->Db_model->updateData("tbl_individual_roster", $data_arr, $whereArray);
                }
            }
            // }
            $this->session->set_flashdata('success_message', 'Attendance Process successfully');
            redirect('/Attendance/Attendance_Process_New');
        } else {
            $this->session->set_flashdata('success_message', 'Attendance Process successfully');
            redirect('/Attendance/Attendance_Process_New');
        }
            }
        $this->session->set_flashdata('success_message', 'Attendance Process successfully');
        redirect('/Attendance/Attendance_Process_New');
        }
    }
}
