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
        $this->load->model('Db_model', '', TRUE);
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
        $data['autorun_settings'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='attendance_process_run'");
        $data['autorun_settings2'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='shift_allocation_run'");
        $data['autorun_settings3'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_autorun_settings WHERE status_flag_name='initialize_run'");

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
        date_default_timezone_set('Asia/Colombo');

        $empData = $this->Db_model->getfilteredData("SELECT * FROM tbl_individual_roster WHERE Is_processed = 0");

        if (empty($empData)) {
            $this->session->set_flashdata('success_message', 'Attendance Process successfully');
            redirect('/Attendance/Attendance_Process_New');
            return;
        }

        foreach ($empData as $emp) {
            $EmpNo = $emp->EmpNo;
            $FromDate = $emp->FDate;
            $ToDate = $emp->TDate;

            if ($FromDate > $ToDate) {
                continue;
            }

            // Get shift data once
            $shiftData = $this->Db_model->getfilteredData(
                "SELECT ShType, ShiftCode, ShiftDay, FTime, TTime, ID_roster, Day_Type, GracePrd 
            FROM tbl_individual_roster 
            WHERE Is_processed = 0 AND EmpNo = '$EmpNo' AND FDate = '$FromDate' LIMIT 1"
            );
            if (empty($shiftData))
                continue;
            $shift = $shiftData[0];

            // Get attendance times
            $attIn = $this->Db_model->getfilteredData("SELECT MIN(AttTime) AS INTime, AttDate FROM tbl_u_attendancedata WHERE Enroll_No='$EmpNo' AND AttDate='$FromDate' LIMIT 1");
            $attOut = $this->Db_model->getfilteredData("SELECT MAX(AttTime) AS OutTime, AttDate FROM tbl_u_attendancedata WHERE Enroll_No='$EmpNo' AND AttDate='$FromDate' LIMIT 1");

            $InTime = !empty($attIn[0]->INTime) ? $attIn[0]->INTime : '';
            $InDate = !empty($attIn[0]->AttDate) ? $attIn[0]->AttDate : '';
            $OutTime = !empty($attOut[0]->OutTime) ? $attOut[0]->OutTime : '';
            $OutDate = !empty($attOut[0]->AttDate) ? $attOut[0]->AttDate : '';

            $Day = $shift->ShType;
            $SHFT = $shift->FTime;
            $SHTT = $shift->TTime;
            $Shift_Day = $shift->ShiftDay;
            $ShiftType = $shift->ShType;
            $ID_Roster = $shift->ID_roster;
            $DayType = $shift->Day_Type;
            $GracePrd = $shift->GracePrd;

            // Get OT pattern info
            $otPattern = $this->Db_model->getfilteredData(
                "SELECT AfterShift, MinAS FROM tbl_ot_pattern_dtl RIGHT JOIN tbl_empmaster ON tbl_ot_pattern_dtl.OTCode = tbl_empmaster.OTCode WHERE tbl_ot_pattern_dtl.DayCode='$Shift_Day' AND tbl_ot_pattern_dtl.DUEX='$ShiftType' LIMIT 1"
            );
            $AfterShift = !empty($otPattern[0]->AfterShift) ? $otPattern[0]->AfterShift : 0;
            $MinAS = !empty($otPattern[0]->MinAS) ? $otPattern[0]->MinAS : 0;

            // Fetch leave data once
            $leaveData = $this->getLeaveData($EmpNo, $FromDate);
            $halfDayLeave = $this->getHalfDayLeaveData($EmpNo, $FromDate);
            $shortLeave = $this->getShortLeaveData($EmpNo, $FromDate);
            $appointDate = $this->getAppointmentDate($EmpNo);
            $holidayCheck = $this->getHolidayCheck($FromDate);

            // Determine Day Status
            $dayStatusData = $this->determineDayStatus(
                $InTime,
                $OutTime,
                $Day,
                $FromDate,
                $ToDate,
                $OutDate,
                $SHFT,
                $SHTT,
                $ShiftType,
                $Shift_Day,
                $GracePrd,
                $AfterShift,
                $MinAS,
                $leaveData,
                $halfDayLeave,
                $shortLeave,
                $appointDate,
                $holidayCheck
            );

            // Prepare update data array
            $data_arr = [
                "InRec" => 1,
                "InDate" => $FromDate,
                "InTime" => $InTime,
                "OutRec" => 1,
                "OutDate" => $OutDate,
                "OutTime" => $OutTime,
                "nopay" => $dayStatusData['nopay'],
                "Is_processed" => 1,
                "DayStatus" => $dayStatusData['DayStatus'],
                "AfterExH" => $dayStatusData['AfterShiftWH'],
                "LateSt" => $dayStatusData['Late_Status'],
                "LateM" => $dayStatusData['lateM'],
                "Lv_T_ID" => $dayStatusData['leave_type'],
                "EarlyDepMin" => $dayStatusData['ED'],
                "NetLateM" => $dayStatusData['NetLateM'],
                "ApprovedExH" => $dayStatusData['ApprovedExH'],
                "nopay_hrs" => $dayStatusData['nopay_hrs'],
                "Att_Allow" => $dayStatusData['Att_Allowance']
            ];

            $this->Db_model->updateData("tbl_individual_roster", $data_arr, ["ID_roster" => $ID_Roster]);
        }

        $this->session->set_flashdata('success_message', 'Attendance Process successfully');
        redirect('/Attendance/Attendance_Process_New');
    }

    /**
     * Calculate minutes difference between two time strings.
     */
    private function getMinutesDifference($startTime, $endTime, $allowNegative = false)
    {
        if (!$this->isValidTime($startTime) || !$this->isValidTime($endTime)) {
            return 0;
        }
        $diff = (strtotime($endTime) - strtotime($startTime)) / 60;
        if (!$allowNegative && $diff < 0) {
            return 0;
        }
        return $diff;
    }

    /**
     * Simple time validation
     */
    private function isValidTime($time)
    {
        return preg_match('/^\d{2}:\d{2}:\d{2}$/', $time);
    }

    /**
     * Get full leave data for the employee and date
     */
    private function getLeaveData($empNo, $date)
    {
        return $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry WHERE EmpNo = $empNo AND Leave_Date = '$date' AND Leave_Count='1' AND Is_Cancel = '0' LIMIT 1");
    }

    /**
     * Get half-day leave data
     */
    private function getHalfDayLeaveData($empNo, $date)
    {
        return $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_entry WHERE EmpNo = $empNo AND Leave_Date = '$date' AND Leave_Count='0.5' LIMIT 1");
    }

    /**
     * Get short leave data
     */
    private function getShortLeaveData($empNo, $date)
    {
        return $this->Db_model->getfilteredData("SELECT * FROM tbl_shortlive WHERE EmpNo = $empNo AND Date = '$date' LIMIT 1");
    }

    /**
     * Get appointment date for employee
     */
    private function getAppointmentDate($empNo)
    {
        $result = $this->Db_model->getfilteredData("SELECT ApointDate FROM tbl_empmaster WHERE EmpNo = '$empNo' LIMIT 1");
        return !empty($result) ? $result[0]->ApointDate : null;
    }

    /**
     * Check if a date is a holiday
     */
    private function getHolidayCheck($date)
    {
        $result = $this->Db_model->getfilteredData("SELECT COUNT(Hdate) as HasRow FROM tbl_holidays WHERE Hdate = '$date'");
        return !empty($result) ? $result[0]->HasRow : 0;
    }

    /**
     * Determines day status and related metrics
     * Returns an associative array with keys: DayStatus, nopay, AfterShiftWH, Late_Status, lateM, leave_type, ED, NetLateM, ApprovedExH, nopay_hrs, Att_Allowance
     */
    private function determineDayStatus(
        $InTime,
        $OutTime,
        $Day,
        $FromDate,
        $ToDate,
        $OutDate,
        $SHFT,
        $SHTT,
        $ShiftType,
        $Shift_Day,
        $GracePrd,
        $AfterShift,
        $MinAS,
        $leaveData,
        $halfDayLeave,
        $shortLeave,
        $appointDate,
        $holidayCheck
    ) {
        $DayStatus = 'OFF';
        $nopay = 0;
        $AfterShiftWH = 0;
        $Late_Status = 0;
        $lateM = 0;
        $leave_type = 0;
        $ED = 0;
        $NetLateM = 0;
        $ApprovedExH = 0;
        $nopay_hrs = 0;
        $Att_Allowance = 1;

        // Handle OFF or EX day
        if ($Day === 'OFF' || $Day === 'EX') {
            if ($OutTime && !$InTime) {
                $InTime = '08:30:00'; // Default in time for off/ex day
            }
            if ($InTime && $OutTime) {
                $AfterShiftWH = round($this->getMinutesDifference($InTime, $OutTime, true));
                $DayStatus = 'EX';
            }

            if ($appointDate && $appointDate > $FromDate) {
                $DayStatus = '';
                $nopay = 0;
                $nopay_hrs = 0;
            }

            return compact('DayStatus', 'nopay', 'AfterShiftWH', 'Late_Status', 'lateM', 'leave_type', 'ED', 'NetLateM', 'ApprovedExH', 'nopay_hrs', 'Att_Allowance');
        }

        // Handle normal DU day or others
        if (empty($InTime) && empty($OutTime) && $Day === 'DU') {
            $DayStatus = 'AB';
            $nopay = 1;
            $nopay_hrs = $this->getMinutesDifference($SHFT, $SHTT);

            if ($appointDate && $appointDate > $FromDate) {
                $DayStatus = '';
                $nopay = 0;
                $nopay_hrs = 0;
            }

            return compact('DayStatus', 'nopay', 'AfterShiftWH', 'Late_Status', 'lateM', 'leave_type', 'ED', 'NetLateM', 'ApprovedExH', 'nopay_hrs', 'Att_Allowance');
        }

        if ($InTime == $OutTime || empty($InTime) || empty($OutTime)) {
            $DayStatus = 'MS';
            $nopay = 0;
            $nopay_hrs = 0;
            $Late_Status = 0;
            $OutTime = $OutTime ?: '00:00:00';

            return compact('DayStatus', 'nopay', 'AfterShiftWH', 'Late_Status', 'lateM', 'leave_type', 'ED', 'NetLateM', 'ApprovedExH', 'nopay_hrs', 'Att_Allowance');
        }

        // Present day calculations
        $DayStatus = 'PR';

        // Calculate After Shift work hours (OT)
        if ($ToDate == $OutDate && $OutTime != "00:00:00" && $AfterShift == 1) {
            $AfterShiftWH = max(0, $this->getMinutesDifference($SHTT, $OutTime) - $MinAS);
        } elseif ($ToDate == $OutDate && $OutTime != "00:00:00") {
            $AfterShiftWH = max(0, $this->getMinutesDifference($SHTT, $OutTime));
        }

        // Early Departure (ED)
        if ($ToDate == $OutDate && $OutTime < $SHTT && $Day == 'DU') {
            $EDF = $this->getMinutesDifference($OutTime, $SHTT);

            if (!empty($halfDayLeave) && !empty($halfDayLeave[0]->Is_Approve)) {
                $iCalcHaffED = $this->getMinutesDifference($OutTime, "14:00:00");

                $ED = $iCalcHaffED > 0 ? $iCalcHaffED : 0;
            } else {
                $ED = $EDF;
            }
        }

        // Late minutes
        $lateM = max(0, $this->getMinutesDifference($SHFT, $InTime) - $GracePrd);

        // Half day leave adjustments for late and ED
        if (!empty($halfDayLeave) && !empty($halfDayLeave[0]->Is_Approve)) {
            $SHAfternoonStart = "14:00:00";

            if (strtotime($InTime) > strtotime($SHAfternoonStart)) {
                $lateM = max(0, $this->getMinutesDifference($SHAfternoonStart, $InTime));
                $DayStatus = 'HFD';
            }

            $ED = max(0, $this->getMinutesDifference($OutTime, $SHAfternoonStart));
        }

        // Holiday check
        if ($holidayCheck == 1) {
            $DayStatus = 'HD';
            $nopay = 0;
            $nopay_hrs = 0;
            $Att_Allowance = 0;
        }

        // Full day leave
        if (!empty($leaveData) && !empty($leaveData[0]->Is_Approve)) {
            $DayStatus = 'LV';
            $leave_type = $leaveData[0]->Lv_T_ID;
            $nopay = 0;
            $nopay_hrs = 0;
            $Att_Allowance = 0;
        }

        // Short leave
        if (!empty($shortLeave) && !empty($shortLeave[0]->Is_Approve)) {
            $fromTime = $shortLeave[0]->from_time;
            $toTime = $shortLeave[0]->to_time;

            // Evening short leave
            if ($fromTime >= '17:30:00' && $OutTime > '17:30:00' && $OutTime < '18:00:00') {
                $ED = max(0, $this->getMinutesDifference($OutTime, $fromTime));
                $DayStatus = 'SL';
            }

            // Morning short leave
            if ($fromTime <= '10:00:00') {
                if (strtotime($InTime) <= strtotime($toTime)) {
                    $lateM = 0;
                    $DayStatus = 'SL';
                } else {
                    $lateM = $this->getMinutesDifference($toTime, $InTime);
                    $DayStatus = 'SL';
                }
            }
        }

        // Half day absent with approved half day leave
        if (!empty($halfDayLeave) && !empty($halfDayLeave[0]->Is_Approve) && empty($InTime) && empty($OutTime)) {
            $DayStatus = 'HFDAB';
            $nopay = 0.5;
            $nopay_hrs = 0;
        }

        return compact('DayStatus', 'nopay', 'AfterShiftWH', 'Late_Status', 'lateM', 'leave_type', 'ED', 'NetLateM', 'ApprovedExH', 'nopay_hrs', 'Att_Allowance');
    }


}
?>