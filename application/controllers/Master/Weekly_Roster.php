<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Weekly_Roster extends CI_Controller
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

        $data['title'] = "Weekly Roster Pattern | HRM System";
        // $data['data_set_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_set_shift'] = $this->Db_model->getfilteredData("SELECT ShiftCode,ShiftName,FromTime,ToTime,NextDay,DayType,FHDSessionEndTime,SHDSessionStartTime,ShiftGap FROM tbl_shifts ;");

        // $data['data_set'] = $this->Db_model->getData('RosterCode,RosterName,MonthType,CurrentYear,Data', 'tbl_rosterpatternweeklyhd');
        $data['data_set'] = $this->Db_model->getfilteredData("select RosterCode,RosterName,MonthType,CurrentYear,Data from tbl_rosterpatternweeklyhd ;");

        // $data['data_set_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');

        // $last_record = $this->Db_model->get_last_record('tbl_rosterpatternweeklyhd','RosterCode');
        // $last_id_numeric = intval(substr($last_record->RosterCode, 2)); // Extract numeric part
        // $next_id_numeric = $last_id_numeric + 1; // Increment the numeric part
        // $data['serial1'] = "RS" . str_pad($next_id_numeric, 4, '0', STR_PAD_LEFT); // Format the next I

        $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Roster'));
        $serial = "RS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
        $data['serial'] = ++$serial;

        // // Call fetch_dates to get the dates
        // $datesOfThisMonth = $this->fetch_dates();

        // // Pass the dates to the view along with other data
        // $data['datesOfThisMonth'] = $datesOfThisMonth;


        // $data['datesOfThisMonth'] = $this->getDatesOfThisMonth();
        // $serial = 1;  // Add any additional data you need

        $this->load->view('Master/Weekly_Roster/index', $data, $serial);
        //        $this->load->view('Master/Weekly_Roster/index', $data, $serial);
    }

    // public function index2(){

    //     // Fetch dates and pass them to the view
    //     $MonthType = $this->input->post('txt_MType');
    //     $currentYear = date('Y');
    //     $currentMonth = date_parse($MonthType)['month'];
    //     $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    //     $datesOfThisMonth = [];
    //     for ($day = 1; $day <= $daysInMonth; $day++) {
    //         $datesOfThisMonth[] = date('Y-m-d', mktime(0, 0, 0, $currentMonth, $day, $currentYear));
    //     }
    //     $data['datesOfThisMonth'] = $datesOfThisMonth;

    //     // Load the view with data
    //     // $this->load->view('Master/Weekly_Roster/index', $data);
    //     $this->load->view('Master/Weekly_Roster/months', $data);
    // }

    // private function getDatesOfThisMonth()
    // {
    //     $currentMonth = date('n');
    //     $daysInMonth = date('t');

    //     $datesOfThisMonth = [];
    //     for ($day = 1; $day <= $daysInMonth; $day++) {
    //         $datesOfThisMonth[] = date('Y-m-d', mktime(0, 0, 0, $currentMonth, $day));
    //     }

    //     return $datesOfThisMonth;
    // }

    /*
     * Insert Data View(Next)
     */

    public function insert_dataNew()
    {
        $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Roster'));
        $serial = "RS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
        $data['serial'] = ++$serial;
        $data['title'] = "Monthly Roster Pattern | HRM System";
        // $data['data_set_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_set_shift'] = $this->Db_model->getfilteredData("SELECT ShiftCode,ShiftName,FromTime,ToTime,NextDay,DayType,FHDSessionEndTime,SHDSessionStartTime,ShiftGap FROM tbl_shifts");



        $RosterCode = $this->input->post('txtRoster_Code');
        // $RosterName = $this->input->post('txtRoster_Name');
        $MonthType = $this->input->post('txt_MType');

        $cat = $this->input->post('cmb_cat');

        if ($cat == "Individual Employee") {
            $RosterName = $this->input->post('txt_nic');
            // $RosterName = "null";
            // $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$Data'";
            // $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "OnlyGroup") {
            $Data = $this->input->post('cmb_cat');
            $RosterName = $this->input->post('txtRoster_Name');
        }

        $currentYear = date('Y');
        $currentMonth = date_parse($MonthType)['month'];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $datesOfThisMonth = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = mktime(0, 0, 0, $currentMonth, $day, $currentYear);
            $dateString = date('Y-m-d', $date);
            $dayName = date('l', $date); // Get the full name of the day
            $datesOfThisMonth[] = array('date' => $dateString, 'day' => $dayName);
        }
        // Return JSON data

        $data['datesOfThisMonth'] = $datesOfThisMonth;
        $data['RosterCode'] = $RosterCode;
        $data['RosterName'] = $RosterName;
        $data['MonthType'] = $MonthType;
        $data['Data'] = $cat;


        // Load the view with data
        $this->load->view('Master/Weekly_Roster/months', $data);
    }

    public function insert_data()
    {
        // Decode incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract the main roster details
        $rosterCode = $data['rosterCode'];
        $monthType = $data['monthType'];
        $rosterName = $data['rosterName'];
        $rosterData = $data['rosterData'];
        $CurrentYear = date("Y");

        // Prepare data for the header table
        $dataToInsert = array(
            'RosterCode' => $rosterCode,
            'RosterName' => $rosterName,
            'MonthType' => $monthType,
            'CurrentYear' => $CurrentYear,
            'Data' => $rosterData
        );

        // Insert into the header table
        $result = $this->Db_model->insertData('tbl_rosterpatternweeklyhd', $dataToInsert);

        if ($result) {
            // Insert each date entry into the detail table
            foreach ($data['dates'] as $date) {
                $dateData = array(
                    'RosterCode' => $rosterCode,
                    'RosterName' => $rosterName,
                    'ShiftCode' => $date['shType'],
                    'DayName' => $date['dType'],
                    'Date' => $date['todayType'],
                    'ShiftType' => $date['SType']
                );

                // Insert into the detail table
                $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl_monthly', $dateData);

                if (!$result) {
                    // Rollback or handle error appropriately if insertion fails
                    echo json_encode(array('status' => 'error', 'message' => 'Error inserting date data'));
                    return;
                }
            }

            // Update serial number after successful inserts
            $serial = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'RS'));
            if (!empty($serial)) {
                $serialNo = $serial[0]->serial + 1;
                $serialUpdateData = array("serial" => $serialNo);
                $whereArr = array("code" => "RS");

                $updateResult = $this->Db_model->updateData('tbl_serials', $serialUpdateData, $whereArr);

                if (!$updateResult) {
                    echo json_encode(array('status' => 'error', 'message' => 'Error updating serial number'));
                    return;
                }
            }

            // Success response
            echo json_encode(array('status' => 'success', 'message' => 'Data successfully inserted'));
        } else {
            // Error response for header insertion failure
            echo json_encode(array('status' => 'error', 'message' => 'Error inserting header data'));
        }
    }


    // In your Codnighter controller
    // public function insert_data()
    // {
    //     $data = json_decode(file_get_contents("php://input"), true);

    //     $rosterCode = $data['rosterCode'];
    //     $monthType = $data['monthType'];
    //     $rosterName = $data['rosterName'];
    //     $rosterData = $data['rosterData'];

    //     // $dates = json_encode($data['dates']);
    //     $CurrentYear = date("Y");

    //     // Prepare data for insertion into the database
    //     $dataToInsert = array(
    //         'RosterCode' => $rosterCode,
    //         'RosterName' => $rosterName,
    //         'MonthType' => $monthType,
    //         'CurrentYear' => $CurrentYear,
    //         'Data' => $rosterData
    //     );

    //     // Insert basic information into the database
    //     $result = $this->Db_model->insertData('tbl_rosterpatternweeklyhd', $dataToInsert);

    //     if ($result) {
    //         // Insert each date entry into the database
    //         foreach ($data['dates'] as $date) {
    //             $dateData = array(
    //                 'RosterCode' => $rosterCode,
    //                 'RosterName' => $rosterName,
    //                 'ShiftCode' => $date['shType'],
    //                 'DayName' => $date['dType'],
    //                 'Date' => $date['todayType'],
    //                 'ShiftType' => $date['SType']
    //             );
    //             $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $dateData);

    //             if (!$result) {
    //                 echo "Error inserting date data into the database";
    //                 break; // Stop further iteration if an error occurs
    //             }
    //         }
    //         $serial = $this->Db_model->getData('serial', 'tbl_serials');
    //             $serialNo = $serial[0]->serial + 1;
    //             $data = array("serial" => $serialNo);

    //             $whereArr = array("code" => "RS");
    //             $result = $this->Db_model->updateData('tbl_serials', $data, $whereArr);

    //             echo "Success";
    //             // $this->load->view('Master/Weekly_Roster/index', $data);
    //     } else {
    //         echo "Error inserting basic information into the database";
    //     }

    // }

    public function updateAttView()
    {
        $data['title'] = "Monthly Roster Pattern | HRM System";

        $id = $this->input->get('id'); // Get the 'id' parameter from the request

        // Execute the SQL query to get all relevant data by joining the two tables
        $data['emp_roster'] = $this->Db_model->getfilteredData("SELECT tbl_rosterpatternweeklydtl_monthly.ShiftCode,tbl_rosterpatternweeklydtl_monthly.Date,tbl_rosterpatternweeklydtl_monthly.DayName,tbl_shifts.FromTime,tbl_shifts.ToTime,tbl_rosterpatternweeklydtl_monthly.ShiftType,tbl_shifts.ShiftName FROM tbl_rosterpatternweeklydtl_monthly INNER JOIN tbl_shifts ON tbl_rosterpatternweeklydtl_monthly.ShiftCode = tbl_shifts.ShiftCode WHERE tbl_rosterpatternweeklydtl_monthly.RosterCode = '" . $id . "'");
        $data['data_set_shift'] = $this->Db_model->getData('ShiftCode,ShiftName', 'tbl_shifts');
        $data['data_set'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');
        $data['roster'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_rosterpatternweeklyhd WHERE RosterCode='" . $id . "'");
        $data['RosterCode'] = $data['roster'][0]->RosterCode;
        $data['MonthType'] = $data['roster'][0]->MonthType;
        $data['RosterName'] = $data['roster'][0]->RosterName;
        $data['Data'] = $data['roster'][0]->Data;

        $MonthType1 = $data['roster'][0]->MonthType;

        $currentYear = date('Y');
        $currentMonth = date_parse($MonthType1)['month'];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $datesOfThisMonth = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = mktime(0, 0, 0, $currentMonth, $day, $currentYear);
            $dateString = date('Y-m-d', $date);
            $dayName = date('l', $date); // Get the full name of the day
            $datesOfThisMonth[] = array('date' => $dateString, 'day' => $dayName);
        }

        $data['datesOfThisMonth'] = $datesOfThisMonth;
        $data['MonthType'] = $MonthType1;

        // echo $data['datesOfThisMonth'] = json_encode($datesOfThisMonth);

        // echo $data['datesOfThisMonth'][0]['date'];
        // Load the view and pass the data to it
        $this->load->view('Master/Weekly_Roster/update', $data);
    }

    public function update_data2()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Echo and display the data values
        // echo "<pre>";
        // // print_r($data);
        // echo "</pre>";
        // echo $data['rosterCode'];
        $CurrentYear = date("Y");


        $rosterCode = $data['rosterCode'];
        $rosterName = $data['rosterName'];
        $monthType = $data['monthType'];
        $cat = $data['rosterData'];
        // $dates = json_encode($data['dates']);

        // echo $rosterCode. ' ' . $rosterName. ' ' . $monthType. ' ' . $dates;

        // Prepare data for insertion into the database
        $dataToInsert = array(
            'RosterCode' => $rosterCode,
            'RosterName' => $rosterName,
            'MonthType' => $monthType,
            'CurrentYear' => $CurrentYear,
            'Data' => $cat

        );

        // Insert basic information into the database
        $this->Db_model->getfilteredDelete("DELETE FROM tbl_rosterpatternweeklyhd WHERE RosterCode = '" . $rosterCode . "'");
        $result = $this->Db_model->insertData('tbl_rosterpatternweeklyhd', $dataToInsert);
        $this->Db_model->getfilteredDelete("DELETE FROM tbl_rosterpatternweeklydtl WHERE RosterCode = '" . $rosterCode . "'");

        // if ($result) {
        // Insert each date entry into the database
        foreach ($data['dates'] as $date) {
            $dateData = array(
                'RosterCode' => $rosterCode,
                'RosterName' => $rosterName,
                'ShiftCode' => $date['shType'],
                'DayName' => $date['dType'],
                'Date' => $date['todayType'],
                'ShiftType' => $date['SType']
            );
            $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $dateData);



            if (!$result) {
                echo "Error inserting date data into the database";
                break; // Stop further iteration if an error occurs
            }
        }
        // $serial = $this->Db_model->getData('serial', 'tbl_serials');
        //     $serialNo = $serial[0]->serial + 1;
        //     $data = array("serial" => $serialNo);

        //     $whereArr = array("code" => "RS");
        //     $result = $this->Db_model->updateData('tbl_serials', $data, $whereArr);

        echo "Success";
        // $this->load->view('Master/Weekly_Roster/index', $data);
        // } else {
        //     echo "Error inserting basic information into the database";
        // }

    }
    
       public function update_data()
        {
            // Decode JSON input
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                echo "Invalid input data";
                return;
            }
        
            // print_r($data);
        
            // die;
        
            // Extract and sanitize incoming data
            $rosterCode  = trim($data['rosterCode'] ?? '');
            $rosterName  = trim($data['rosterName'] ?? '');
            $monthType   = trim($data['monthType'] ?? '');
            $cat         = trim($data['rosterData'] ?? '');
            $dates       = $data['dates'] ?? [];
            $currentYear = date("Y");
        
            if (!$rosterCode || !$rosterName || !$monthType || empty($dates)) {
                echo "Missing required fields";
                return;
            }
        
            // Prepare header data
            $headerData = [
                'RosterCode'   => $rosterCode,
                'RosterName'   => $rosterName,
                'MonthType'    => $monthType,
                'CurrentYear'  => $currentYear,
                'Data'         => $cat
            ];
        
            // Begin database update
            // Clear old header and detail records
            $this->Db_model->getfilteredDelete("DELETE FROM tbl_rosterpatternweeklyhd WHERE RosterCode = '{$rosterCode}'");
            $this->Db_model->getfilteredDelete("DELETE FROM tbl_rosterpatternweeklydtl_monthly WHERE RosterCode = '{$rosterCode}'");
        
            // Insert new header
            $insertHeader = $this->Db_model->insertData('tbl_rosterpatternweeklyhd', $headerData);
            if (!$insertHeader) {
                echo "Error inserting header information";
                return;
            }
        
            // Insert detail rows
            foreach ($dates as $date) {
                $shiftCode  = $date['shType']    ?? '';
                $dayName    = $date['dType']     ?? '';
                $dateValue  = $date['todayType'] ?? '';
                $shiftType  = $date['SType']     ?? '';
        
                if (!$shiftCode || !$dayName || !$dateValue || !$shiftType) {
                    continue; // Skip incomplete records
                }
        
                $detailData = [
                    'RosterCode' => $rosterCode,
                    'RosterName' => $rosterName,
                    'ShiftCode'  => $shiftCode,
                    'DayName'    => $dayName,
                    'Date'       => $dateValue,
                    'ShiftType'  => $shiftType
                ];
        
                $insertDetail = $this->Db_model->insertData('tbl_rosterpatternweeklydtl_monthly', $detailData);
                if (!$insertDetail) {
                    echo "Error inserting detail record for date: {$dateValue}";
                    return;
                }
            }
        
            echo "Success";
        }


    // public function insert_data()
    // {

    //     // Receive data sent via POST
    //     $rosterCode = $this->input->post('rosterCode');
    //     $rosterName = $this->input->post('rosterName');
    //     $monthType = $this->input->post('monthType');
    //     $dataset = $this->input->post('dates');

    //     $serial = $this->Db_model->getData('serial', 'tbl_serials', array("code" => "Roster"));
    //     $serialNo = $serial[0]->serial + 1;

    //     $dataset = json_decode($_POST['hdntext']);

    //     foreach ($dataset as $dataitems) {
    //         $shiftarray = array(
    //             "RosterCode" => $this->input->post('txtRoster_Code'),
    //             'RosterName' => $this->input->post('txtRoster_Name'),
    //             'ShiftCode' => $dataitems->SHType,
    //             'DayName' => $dataitems->Day,
    //             'ShiftType' => $dataitems->SType,
    //         );

    //         $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $shiftarray);
    //     }
    //     $this->session->set_flashdata('success_message', 'New Weekly Roster has been added successfully');

    //     // $id = '1';
    //     $data = array(
    //         'RosterCode' => $this->input->post('txtRoster_Code'),
    //         'RosterName' => $this->input->post('txtRoster_Name'),
    //         'ShiftCode' => $this->input->post('txtSH_MO')
    //     );

    //     $result = $this->Db_model->insertData("tbl_rosterpatternweeklyhd", $data);


    //     $serialdata = "";
    //     $condition = 0;
    //     $data = array("serial" => $serialNo);

    //     if ($result) {
    //         $condition = 1;


    //         $whereArr = array("code" => "Roster");
    //         $result = $this->Db_model->updateData('tbl_serials', $data, $whereArr);

    //         //Genarate next designation code

    //         $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Designation'));
    //         $serial = "DS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
    //         $data['serial'] = ++$serial;
    //     } else {
    //         $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Designation'));
    //         $serial = "DS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
    //         $data['serial'] = ++$serial;
    //     }

    //     redirect('/Master/Weekly_Roster/');
    // }

    /*
     * Get data
     */

    public function get_details()
    {
        $ShiftCode = $this->input->post('ShiftCode');

        $whereArray = array('ShiftCode' => $ShiftCode);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('ShiftCode,ShiftName,FromTime,ToTime,ShiftGap', 'tbl_shifts');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit()
    {
        $ShiftCode = $this->input->post("ShiftCode", TRUE);
        $ShiftName = $this->input->post("ShiftName", TRUE);
        $FromTime = $this->input->post("FromTime", TRUE);
        $ToTime = $this->input->post("ToTime", TRUE);
        $ShiftGap = $this->input->post("ShiftGap", TRUE);



        $data = array("ShiftName" => $ShiftName, "FromTime" => $FromTime, "ToTime" => $ToTime, "ShiftGap" => $ShiftGap, );
        $whereArr = array("ShiftCode" => $ShiftCode);
        $result = $this->Db_model->updateData("tbl_shifts", $data, $whereArr);
        redirect(base_url() . "Master/Shifts");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id)
    {
        // $table = "tbl_shifts";
        // $where = 'ShiftCode';
        // $this->Db_model->delete_by_id($id, $where, $table);
        // echo json_encode(array("status" => TRUE));

        $data1 = "0";
        $data2 = array("Status" => $data1, );
        $whereArr3 = array("RosterCode" => $id);
        $result = $this->Db_model->updateData("tbl_rosterpatternweeklyhd", $data2, $whereArr3);
        redirect(base_url() . "Master/Weekly_Roster");
    }

    /*
     * Get Bank account number
     */

    function get_data()
    {
        $state = $this->input->post('cmb_bank');
        $query = $this->Db_model->get_bank_info();
        echo '<option value="" default>-- Select --</option>';
        foreach ($query->result() as $row) {

            echo "<option value='" . $row->Acc_no . "'>" . $row->Acc_no . "</option>";
        }
    }

    /*
     * Get last cheque number according to bank account number
     */

    function get_data_chq()
    {
        $state = $this->input->post('cmb_acc_no');
        $query = $this->Db_model->get_chqno_info();

        foreach ($query->result() as $row) {
            //                 echo "< value='".$row->lc_no."'>".$row->lc_no."";

            echo $row->lc_no;
        }
    }

    public function getShiftData()
    {
        $shiftcode = $this->input->post("shiftCode");
        $string = "SELECT FromTime,ToTime FROM tbl_shifts WHERE ShiftCode='$shiftcode'";
        $shfitData = $this->Db_model->getfilteredData($string);

        echo json_encode($shfitData);
    }

}
