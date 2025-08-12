<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Shift_Allocation extends CI_Controller
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
        
        $Year = $currentYear = date("Y"); 
        $Month = $currentMonth = date("F");

        $data['title'] = "Shift Allocation | HRM System";
        $data['data_set'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        // $data['data_roster'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');
        // $data['data_roster'] = $this->Db_model->getfilteredData("select RosterCode,RosterName from tbl_rosterpatternweeklyhd where CurrentYear = '2025'");
        // $data['data_roster'] = $this->Db_model->getfilteredData("select RosterCode,RosterName from tbl_rosterpatternweeklyhd WHERE (CurrentYear = '".$Year."' AND MonthType='Weekly') OR (CurrentYear = '".$Year."' AND MonthType='".$Month."');");
        // $data['data_roster'] = $this->Db_model->getfilteredData("SELECT RosterCode, RosterName FROM tbl_rosterpatternweeklyhd WHERE ((CurrentYear = '".$Year."' AND MonthType = 'Weekly') OR (CurrentYear = '".$Year."' AND MonthType = '".$Month."') OR (CurrentYear = '2025' AND  MonthType = 'June')) AND RosterCode > 'RS0594';");
        $data['data_roster'] = $this->Db_model->getfilteredData("SELECT RosterCode, RosterName FROM tbl_rosterpatternweeklyhd WHERE ((CurrentYear = '".$Year."' AND MonthType = 'Weekly') OR (CurrentYear = '".$Year."'));");


        $this->load->view('Attendance/Shift_Allocation/index', $data);
    }

    public function dropdown()
    {

        $cat = $this->input->post('cmb_cat');
// You have made no changes to sa
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
     * Insert Data
     */

    public function shift_allocation()
    {

        $cat = $this->input->post('cmb_cat');

        //*** Employee Filters
        //*** By Employee
        if ($cat == "Employee") {
            $cat2 = $this->input->post('txt_nic');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$cat2' and Status = 1 and Active_process=1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        //*** By Department
        if ($cat == "Department") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Dep_ID='$cat2' and Status = 1 and Active_process=1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        //*** By Designation
        if ($cat == "Designation") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Des_ID='$cat2' and Status = 1 and Active_process=1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        //*** By Employee_Group
        if ($cat == "Employee_Group") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Grp_ID='$cat2' and Status = 1 and Active_process=1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        //*** By Company
        if ($cat == "Company") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2' and Status = 1 and Active_process=1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        // print_r($EmpData);
        // die;

        //        $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='3316'";

        $roster = $this->input->post('cmb_roster');
        $from_date = $this->input->post('txt_from_date');
        $to_date = $this->input->post('txt_to_date');

        $d1 = new DateTime($from_date);
        $d2 = new DateTime($to_date);

        $interval = $d2->diff($d1)->days;

        $dtl = $this->Db_model->getfilteredData("select MonthType from tbl_rosterpatternweeklyhd where RosterCode = '$roster'");
        
        if ($dtl[0]->MonthType == 'Weekly') {
            // weekly
            for ($x = 0; $x <= $interval; $x++) {

                /*
                 * Get Day Type in weekly roster
                 */
    
                $Current_date = "";
                $num = date("N", strtotime($from_date));
    
                switch ($num) {
                    //**********If $Num = 1 Day is Monday
                    case 1:
                        $Current_date = "Monday";
                        break;
                    case 2:
                        $Current_date = "Tuesday";
                        break;
                    case 3:
                        $Current_date = "Wednesday";
                        break;
                    case 4:
                        $Current_date = "Thursday";
                        break;
                    case 5:
                        $Current_date = "Friday";
                        break;
                    case 6:
                        $Current_date = "Saturday";
                        break;
                    case 7:
                        $Current_date = "Sunday";
                        break;
                    default:
                        break;
                }
    
                $dtl = $this->Db_model->getfilteredData("select COUNT(RosterCode) AS Row from tbl_rosterpatternweeklydtl where DayName = '$Current_date' and RosterCode = '$roster'");
                // var_dump($dtl[0]->Row);
                // echo $dtl[0]->Row;
                for ($n = 0; $n < $dtl[0]->Row; $n++) {
                    // echo "2233";
                    $var = $from_date;
                    $date = str_replace('/', '-', $var);
                    $from_date = date('Y-m-d', strtotime($date));
    
                    $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$from_date' ");
                    $year = date("Y");
    
                    $Count = count($EmpData);
    
                    for ($i = 0; $i < $Count; $i++) {
    
                        if ($n == 0) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                                AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][0]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][0]->DayName;
                            $FromTime = $ros['i'][0]->FromTime;
                            $ToTime = $ros['i'][0]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][0]->ShiftType;
                            $ShiftGap = $ros['i'][0]->ShiftGap;
                            $ShiftCutoff = $ros['i'][0]->FHDSessionEndTime;
                            $DayType = $ros['i'][0]->DayType;
                            $Next_Day = $ros['i'][0]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 2
                            );
                             $HasR = $this->Db_model->getfilteredData("SELECT 
                                                            COUNT(EmpNo) AS HasRow
                                                        FROM
                                                            tbl_individual_roster
                                                        WHERE
                                                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                            if ($HasR[0]->HasRow == 1) {
                                $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                            } else {
                                $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                                $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                            }
                            // $this->Db_model->insertData("tbl_individual_roster", $dataArray);
    
                            // echo "0";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 1) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][1]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][1]->DayName;
                            $FromTime = $ros['i'][1]->FromTime;
                            $ToTime = $ros['i'][1]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][1]->ShiftType;
                            $ShiftGap = $ros['i'][1]->ShiftGap;
                            $ShiftCutoff = $ros['i'][1]->FHDSessionEndTime;
                            $DayType = $ros['i'][1]->DayType;
                            $Next_Day = $ros['i'][1]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 2
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "1";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 2) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][2]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][2]->DayName;
                            $FromTime = $ros['i'][2]->FromTime;
                            $ToTime = $ros['i'][2]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][2]->ShiftType;
                            $ShiftGap = $ros['i'][2]->ShiftGap;
                            $ShiftCutoff = $ros['i'][2]->FHDSessionEndTime;
                            $DayType = $ros['i'][2]->DayType;
                            $Next_Day = $ros['i'][2]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            // if ($Next_Day == 1) {
                            //     //                    $to_date = strtotime($from_date . '+1 day');
                            //     $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            // } else {
                                $to_date_sh = $from_date;
                            // }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 2
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "2";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 3) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][3]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][3]->DayName;
                            $FromTime = $ros['i'][3]->FromTime;
                            $ToTime = $ros['i'][3]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][3]->ShiftType;
                            $ShiftGap = $ros['i'][3]->ShiftGap;
                            $ShiftCutoff = $ros['i'][3]->FHDSessionEndTime;
                            $DayType = $ros['i'][3]->DayType;
                            $Next_Day = $ros['i'][3]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 2
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "3";
                            //                var_dump($to_date);die;
    
                        }
    
                    }
    
    
                }
                /*
                 * Check If Allocated Shift in Individual Roster Table
                
                 */
               
    
                $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
                // }
    
                // die;
    
                /*
                 * Get Holiday Days
                 */
    
    
            }
        }else{
            // monthly
            for ($x = 0; $x <= $interval; $x++) {

                /*
                 * Get Day Type in weekly roster
                 */
    
                $Current_date = "";
                $num = date("N", strtotime($from_date));
    
                switch ($num) {
                    //**********If $Num = 1 Day is Monday
                    case 1:
                        $Current_date = "Monday";
                        break;
                    case 2:
                        $Current_date = "Tuesday";
                        break;
                    case 3:
                        $Current_date = "Wednesday";
                        break;
                    case 4:
                        $Current_date = "Thursday";
                        break;
                    case 5:
                        $Current_date = "Friday";
                        break;
                    case 6:
                        $Current_date = "Saturday";
                        break;
                    case 7:
                        $Current_date = "Sunday";
                        break;
                    default:
                        break;
                }
                $var = $from_date;
                $date = str_replace('/', '-', $var);
                // echo $date;
                // die;
    
                //change 
                $dtl = $this->Db_model->getfilteredData("select COUNT(RosterCode) AS Row from tbl_rosterpatternweeklydtl_monthly where DayName = '$Current_date' and RosterCode = '$roster' and Date = '$date'");
                // var_dump($dtl[0]->Row);
                // echo $dtl[0]->Row;
                for ($n = 0; $n < $dtl[0]->Row; $n++) {
                    // echo "2233";
                    $var = $from_date;
                    $date = str_replace('/', '-', $var);
                    $from_date = date('Y-m-d', strtotime($date));
    
                    $Holiday = $this->Db_model->getfilteredData("select count(Hdate) as HasRow from tbl_holidays where Hdate = '$from_date' ");
                    $year = date("Y");
    
                    $Count = count($EmpData);
    
                    for ($i = 0; $i < $Count; $i++) {
    
                        if ($n == 0) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl_monthly tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                                AND tr.Date = '$from_date'"); //change date    //$from_date variable hold the calendar date. Then find the day name through the date and match it to the day name using query to find correct shift details
    
                            $ShiftCode = $ros['i'][0]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][0]->DayName;
                            $FromTime = $ros['i'][0]->FromTime;
                            $ToTime = $ros['i'][0]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][0]->ShiftType;
                            $ShiftGap = $ros['i'][0]->ShiftGap;
                            $ShiftCutoff = $ros['i'][0]->FHDSessionEndTime;
                            $DayType = $ros['i'][0]->DayType;
                            $Next_Day = $ros['i'][0]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 1
                            );
                            
                            
                             $HasR = $this->Db_model->getfilteredData("SELECT 
                                                            COUNT(EmpNo) AS HasRow
                                                        FROM
                                                            tbl_individual_roster
                                                        WHERE
                                                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                            if ($HasR[0]->HasRow == 1) {
                                $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                            } else {
                                $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                                $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                            }
                            // $this->Db_model->insertData("tbl_individual_roster", $dataArray);
    
                            // echo "0";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 1) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl_monthly tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][1]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][1]->DayName;
                            $FromTime = $ros['i'][1]->FromTime;
                            $ToTime = $ros['i'][1]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][1]->ShiftType;
                            $ShiftGap = $ros['i'][1]->ShiftGap;
                            $ShiftCutoff = $ros['i'][1]->FHDSessionEndTime;
                            $DayType = $ros['i'][1]->DayType;
                            $Next_Day = $ros['i'][1]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 1
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "1";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 2) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl_monthly tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][2]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][2]->DayName;
                            $FromTime = $ros['i'][2]->FromTime;
                            $ToTime = $ros['i'][2]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][2]->ShiftType;
                            $ShiftGap = $ros['i'][2]->ShiftGap;
                            $ShiftCutoff = $ros['i'][2]->FHDSessionEndTime;
                            $DayType = $ros['i'][2]->DayType;
                            $Next_Day = $ros['i'][2]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 1
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "2";
                            //                var_dump($to_date);die;
    
                        } elseif ($n == 3) {
                            $ros['i'] = $this->Db_model->getfilteredData("SELECT 
                                tr.ShiftCode,
                                tr.DayName,
                                tr.ShiftType,
                                ts.FromTime,
                                ts.ToTime,
                                ts.DayType,
                                ts.ShiftGap,
                                ts.FHDSessionEndTime,
                                ts.NextDay
                                FROM
                                    tbl_rosterpatternweeklydtl_monthly tr
                                        INNER JOIN
                                    tbl_shifts ts ON ts.ShiftCode = tr.ShiftCode
                                WHERE
                                tr.RosterCode = '$roster'
                            AND tr.DayName = '$Current_date'");
    
                            $ShiftCode = $ros['i'][3]->ShiftCode;
                            //Week Days  MON | TUE
                            $DayName = $ros['i'][3]->DayName;
                            $FromTime = $ros['i'][3]->FromTime;
                            $ToTime = $ros['i'][3]->ToTime;
                            //Shift Type DU | EX
                            $ShiftType = $ros['i'][3]->ShiftType;
                            $ShiftGap = $ros['i'][3]->ShiftGap;
                            $ShiftCutoff = $ros['i'][3]->FHDSessionEndTime;
                            $DayType = $ros['i'][3]->DayType;
                            $Next_Day = $ros['i'][3]->NextDay;
                            if ($ShiftCutoff == '00:00:00') {
                                $ShiftCutoff = '14:00:00';
                            }
    
                            //            var_dump($Next_Day);die;
    
                            $DayStatus = 'AB';
                            if ($ShiftType == "EX") {
                                $NoPay = 0;
                                $DayStatus = 'EX';
                            }
                            if ($Holiday[0]->HasRow == 1) {
                                $ShiftType = 'EX';
                                //**** Day status is Holiday | Late | Early Departure | AB | PR ******
                                $DayStatus = 'HD';
                                $NoPay = 0;
                            } else {
                                $NoPay = 1;
                            }
    
                            $EmpGrp = $EmpData[$i]->EmpNo;
                            $Group_Data = $this->Db_model->getfilteredData("SELECT Grp_ID from tbl_empmaster where EmpNo = $EmpGrp");
                            $GroupID = $Group_Data[0]->Grp_ID;
    
    
                            $Group_Grace = $this->Db_model->getfilteredData("SELECT GracePeriod FROM tbl_emp_group where Grp_ID = $GroupID");
                            $GracePeriod = $Group_Grace[0]->GracePeriod;
                            //                var_dump($GracePeriod);die;
                            //               echo '<pre>' . var_export($Group_Data, true) . '</pre>'; die;
                            //            var_dump($EmpData);die;
                            //            var_dump($Group_Data);die;
    
    
    
    
    
                            if ($Next_Day == 1) {
                                //                    $to_date = strtotime($from_date . '+1 day');
                                $to_date_sh = date('Y-m-d H:i:s', strtotime($from_date . ' +1 day'));
                            } else {
                                $to_date_sh = $from_date;
                            }
    
    
    
                            $Em = $EmpData[$i]->EmpNo;
                            $dataArray = array(
                                'RYear' => $year,
                                'EmpNo' => $EmpData[$i]->EmpNo,
                                'ShiftCode' => $ShiftCode,
                                'ShiftDay' => $DayName,
                                'Day_Type' => $DayType,
                                'ShiftIndex' => 1,
                                'FDate' => $from_date,
                                'FTime' => $FromTime,
                                'TDate' => $to_date_sh,
                                'TTime' => $ToTime,
                                'ShType' => $ShiftType,
                                'HDSession' => $ShiftCutoff,
                                'DayStatus' => $DayStatus,
                                'GapHrs' => $ShiftGap,
                                'GracePrd' => $GracePeriod,
                                'nopay' => $NoPay,
                                // 'RSType' => 1
                            );
    
                            $HasR = $this->Db_model->getfilteredData("SELECT 
                            COUNT(EmpNo) AS HasRow
                        FROM
                            tbl_individual_roster
                        WHERE
                            EmpNo = '$Em' AND FDate = '$from_date' AND ShiftCode = '$ShiftCode'");
                        if ($HasR[0]->HasRow == 1) {
                        $this->session->set_flashdata('error_message', 'Already Shift Allocated');
                        } else {
                        $this->Db_model->insertData("tbl_individual_roster", $dataArray);
                        $this->session->set_flashdata('success_message', 'Shift Allocation Processed successfully');
                        }
                            // echo "3";
                            //                var_dump($to_date);die;
    
                        }
    
                    }
    
    
                }
                /*
                 * Check If Allocated Shift in Individual Roster Table
                
                 */
               
    
                $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
                // }
    
                // die;
    
                /*
                 * Get Holiday Days
                 */
    
    
            }
        }

        redirect('/Attendance/Shift_Allocation');
    }
}
