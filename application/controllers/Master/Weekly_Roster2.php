<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Weekly_Roster2 extends CI_Controller
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
        $data['data_set_shift'] = $this->Db_model->getfilteredData("SELECT ShiftCode,ShiftName,FromTime,ToTime,NextDay,DayType,FHDSessionEndTime,SHDSessionStartTime,ShiftGap FROM tbl_shifts WHERE ShiftCode > '165';");
        // $data['data_set'] = $this->Db_model->getData('RosterCode,RosterName', 'tbl_rosterpatternweeklyhd');
        $data['data_set'] = $this->Db_model->getfilteredData("select RosterCode,RosterName from tbl_rosterpatternweeklyhd WHERE RosterCode > 'RS0594';");



        $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Roster'));
        $serial = "RS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
        $data['serial'] = ++$serial;

        $this->load->view('Master/Weekly_Roster2/index', $data, $serial);
        //        $this->load->view('Master/Weekly_Roster/index', $data, $serial);
    }

    /*
     * Insert Data
     */

    //  public function insert_data2() {
    //      // Retrieve POST data
    //     $roster_code = $this->input->post('txtRoster_Code');
    //     $roster_name = $this->input->post('txtRoster_Name');
    //     $shift_codes = $this->input->post('txtSH_MO'); // This will be an array if multiple shifts are submitted
    //     $shift_names = $this->input->post('txt_shift_name');
    //     $shift_types = $this->input->post('txtM_SType');
    //     $days = $this->input->post('txtDay');

    //     // Assume you insert this data into the database and it succeeds
    //     $inserted_data = [
    //         'roster_code' => $roster_code,
    //         'roster_name' => $roster_name,
    //         'shifts' => []
    //     ];

    //     // Combine related data for shifts
    //     if (is_array($shift_codes) && count($shift_codes) > 0) {
    //         foreach ($shift_codes as $index => $shift_code) {
    //             $inserted_data['shifts'][] = [
    //                 'day' => $days[$index] ?? null,
    //                 'shift_code' => $shift_code,
    //                 'shift_name' => $shift_names[$index] ?? null,
    //                 'shift_type' => $shift_types[$index] ?? null
    //             ];
    //         }
    //     }

    //     // Echo the inserted data as JSON
    //     echo json_encode([
    //         'success' => true,
    //         'message' => 'Data inserted successfully',
    //         'data' => $inserted_data
    //     ]);
    // }

    // public function insert_data2() {
    //     // Retrieve POST data
    //     $roster_code = $this->input->post('txtRoster_Code');
    //     $roster_name = $this->input->post('txtRoster_Name');
    //     $shift_codesdata = $this->input->post('SHType'); // Assuming SHType is sent as an array from the frontend
    //     if (is_array($shift_codesdata)) {
    //         // Loop through the first 4 values or up to the count of the array
    //         for ($x = 0; $x < 7 && $x < count($shift_codesdata); $x++) {
    //             $shift_codes = $shift_codesdata[$x];
    //             // Your logic here

    //             $inserted_data = [
    //                 'roster_code' => $roster_code,
    //                 'roster_name' => $roster_name,
    //                 'shifts' => [
    //                     'shift_id' => $shift_codes,
    //                 ]
    //             ];


    //         }
    //     }
    //     echo json_encode([
    //         'success' => true,
    //         'message' => 'Data inserted successfully',
    //         'data' => $inserted_data
    //     ], JSON_PRETTY_PRINT);
    //     // $shift_codes = $this->input->post('SHType0'); // This will be an array if multiple shifts are submitted
    //     // $shift_codes6 = $this->input->post('SHType6'); // This will be an array if multiple shifts are submitted
    //     // // $shift_codes = $this->input->post('txtSH_MO'); // This will be an array if multiple shifts are submitted
    //     // // $shift_codes = $this->input->post('txtSH_MO'); // This will be an array if multiple shifts are submitted
    //     // // $shift_codes = $this->input->post('txtSH_MO'); // This will be an array if multiple shifts are submitted

    //     // $shift_names = $this->input->post('txtDayType0');
    //     // $shift_names6 = $this->input->post('txtDayType6');

    //     // $shift_types = $this->input->post('txtSType0');
    //     // $shift_types6 = $this->input->post('txtSType6');

    //     // $days = $this->input->post('DType0');
    //     // $days6 = $this->input->post('DType6');


    //     // // Assume you insert this data into the database and it succeeds
    //     // $inserted_data = [
    //     //     'roster_code' => $roster_code,
    //     //     'roster_name' => $roster_name,
    //     //     'shifts' => [
    //     //         'Day' => $days,
    //     //         'shift_id' => $shift_codes,
    //     //         'shift_id_time' => $shift_names,
    //     //         'shift_type_du_off_ex' => $shift_types,
    //     //     ],
    //     //     'shifts2' =>[
    //     //         'Day' => $days6,
    //     //         'shift_id' => $shift_codes6,
    //     //         'shift_id_time' => $shift_names6,
    //     //         'shift_type_du_off_ex' => $shift_types6,
    //     //     ]
    //     // ];

    //     // // Combine related data for shifts
    //     // // if (is_array($shift_codes) && count($shift_codes) > 0) {
    //     // //     foreach ($shift_codes as $index => $shift_code) {
    //     // //         $inserted_data['shifts'][] = [
    //     // //             'day' => isset($days[$index]) ? $days[$index] : null,
    //     // //             'shift_code' => $shift_code,
    //     // //             'shift_name' => isset($shift_names[$index]) ? $shift_names[$index] : null,
    //     // //             'shift_type' => isset($shift_types[$index]) ? $shift_types[$index] : null
    //     // //         ];
    //     // //     }
    //     // // }

    //     // if (is_array($shift_codes) && count($shift_codes) > 0) {
    //     //     $inserted_data['shifts'] = []; // Initialize to avoid undefined index error.
    //     //     foreach ($shift_codes as $index => $shift_code) {
    //     //         $inserted_data['shifts'][] = [
    //     //             'day' => isset($days[$index]) ? $days[$index] : null,
    //     //             'shift_code' => $shift_code,
    //     //             'shift_name' => isset($shift_names[$index]) ? $shift_names[$index] : null,
    //     //             'shift_type' => isset($shift_types[$index]) ? $shift_types[$index] : null
    //     //         ];
    //     //     }
    //     // }

    //     // // Echo the data for debugging purposes.
    //     // // echo "<pre>"; // Preformat for better readability.
    //     // // print_r($inserted_data);
    //     // // echo "</pre>";

    //     // echo json_encode([
    //     //     'success' => true,
    //     //     'message' => 'Data inserted successfully',
    //     //     'data' => $inserted_data
    //     // ], JSON_PRETTY_PRINT);

    //     // Echo the inserted data as JSON
    //     // echo json_encode([
    //     //     'success' => true,
    //     //     'message' => 'Data inserted successfully',
    //     //     'data' => $inserted_data
    //     // ]);
    // }

    public function insert_data2()
    {
        // Retrieve POST data
        $roster_code = $this->input->post('txtRoster_Code');
        $roster_name = $this->input->post('txtRoster_Name');
        // $shift_codes0 = $this->input->post('SHType0'); // This will be an array if multiple shifts are submitted
        // $shift_codes1 = $this->input->post('SHType1'); // This will be an array if multiple shifts are submitted
        // $shift_codes2 = $this->input->post('SHType2'); // This will be an array if multiple shifts are submitted
        // $shift_codes3 = $this->input->post('SHType3'); // This will be an array if multiple shifts are submitted
        // $shift_codes4 = $this->input->post('SHType4'); // This will be an array if multiple shifts are submitted
        // $shift_codes5 = $this->input->post('SHType5'); // This will be an array if multiple shifts are submitted
        // $shift_codes7 = $this->input->post('SHType7'); // This will be an array if multiple shifts are submitted

        // insert part - start
        $currentYear = date('Y');
        $data = array(
            'RosterCode' => $roster_code,
            'RosterName' => $roster_name,
            // 'Status' => $id,
            'MonthType' => 'Weekly',
            'CurrentYear' => $currentYear,
            'Data' => 'Only Group'
        );
        $result = $this->Db_model->insertData("tbl_rosterpatternweeklyhd", $data);
        // insert part - end

        // Initialize arrays to hold dynamic variables
        $shift_names = [];
        $shift_types = [];
        $days = [];
        $shift_codes = [];

        // Loop through the required indices
        for ($i = 0; $i <= 7; $i++) {
            if ($i === 6)
                continue; // Skip index 6

            // Dynamically assign values to each array
            $shift_names[$i] = $this->input->post("txtDayType$i");
            $shift_types[$i] = $this->input->post("txtSType$i");
            $days[$i] = $this->input->post("DType$i");
            $shift_codes[$i] = $this->input->post("SHType$i");

            // insert part - start
            if ($days[$i] == 'MON') {
                $Day = 'Monday';
            } else if ($days[$i] == 'TUE') {
                $Day = 'Tuesday';
            } else if ($days[$i] == 'WED') {
                $Day = 'Wednesday';
            } else if ($days[$i] == 'THU') {
                $Day = 'Thursday';
            } else if ($days[$i] == 'FRI') {
                $Day = 'Friday';
            } else if ($days[$i] == 'SAT') {
                $Day = 'Saturday';
            } else if ($days[$i] == 'SUN') {
                $Day = 'Sunday';
            }

            $shiftarray = array(
                "RosterCode" => $roster_code,
                'RosterName' => $roster_name,
                'ShiftCode' => $shift_codes[$i],
                'DayName' => $Day,
                'ShiftType' => $shift_types[$i],
                'Date' => 'Weekly'
            );

            $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $shiftarray);
            // insert part - end
        }

        // Specify the additional indices to handle
        $additional_indices = [6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60];

        foreach ($additional_indices as $i) {
            $shift_names[$i] = $this->input->post("txtDayType$i");
            $shift_types[$i] = $this->input->post("txtSType$i");
            $days[$i] = $this->input->post("DType$i");
            $shift_codes[$i] = $this->input->post("SHType$i");

            // Check if all required fields are not null
            if (!empty($shift_codes[$i]) && !empty($days[$i]) && !empty($shift_types[$i])) {

                 // insert part - start
                if ($days[$i] == 'DAY6' || $days[$i] == 'DAY8' || $days[$i] == 'DAY10' || $days[$i] == 'DAY12') {
                    $Main_Day = 'Monday';
                }else if( $days[$i] == 'DAY14' || $days[$i] == 'DAY16' || $days[$i] == 'DAY18' || $days[$i] == 'DAY20'){
                    $Main_Day = 'Tuesday';
                }else if( $days[$i] == 'DAY22' || $days[$i] == 'DAY24' || $days[$i] == 'DAY26' || $days[$i] == 'DAY28'){
                    $Main_Day = 'Wednesday';
                }else if( $days[$i] == 'DAY30' || $days[$i] == 'DAY32' || $days[$i] == 'DAY34' || $days[$i] == 'DAY36'){
                    $Main_Day = 'Thursday';
                }else if( $days[$i] == 'DAY38' || $days[$i] == 'DAY40' || $days[$i] == 'DAY42' || $days[$i] == 'DAY44'){
                    $Main_Day = 'Friday';
                }else if( $days[$i] == 'DAY46' || $days[$i] == 'DAY48' || $days[$i] == 'DAY50' || $days[$i] == 'DAY52'){
                    $Main_Day = 'Saturday';
                }else if( $days[$i] == 'DAY54' || $days[$i] == 'DAY56' || $days[$i] == 'DAY58' || $days[$i] == 'DAY60'){
                    $Main_Day = 'Sunday';
                }
                // Prepare the array for insertion
                $shiftarray = array(
                    "RosterCode" => $roster_code,
                    'RosterName' => $roster_name,
                    'ShiftCode' => $shift_codes[$i],
                    // 'DayName' => $days[$i],
                    'DayName' =>  $Main_Day,
                    'ShiftType' => $shift_types[$i],
                    'Date' => 'Weekly',
                    // 'Main_Day' => $Main_Day
                );

                // Insert into the database
                $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $shiftarray);
                 // insert part - end

            }
        }


        // Prepare inserted data dynamically
        $inserted_data = [
            'roster_code' => $roster_code,
            'roster_name' => $roster_name
        ];

        foreach ($shift_codes as $index => $code) {
            $inserted_data["shifts$index"] = [
                'shift_id' => $code,
                'shift_name' => $shift_names[$index],
                'shift_type' => $shift_types[$index],
                'day' => $days[$index]
            ];
        }

        $serial = $this->Db_model->getData('serial', 'tbl_serials');
        $serialNo = $serial[0]->serial + 1;
        $data = array("serial" => $serialNo);
        
        $whereArr = array("code" => "RS");
        $result = $this->Db_model->updateData('tbl_serials', $data, $whereArr);

        // Echo the data in JSON format
        echo json_encode([
            'success' => true,
            'message' => 'Data inserted successfully',
            // 'data' => $inserted_data
            'data' => "Success"
        ], JSON_PRETTY_PRINT);

    }

    // public function insert_data2() {

    //     // Retrieve POST data
    //     $roster_code = $this->input->post('txtRoster_Code');
    //     $roster_name = $this->input->post('txtRoster_Name');
    //     $shift_codes = $this->input->post('txtSH_MO'); // This will be an array if multiple shifts are submitted
    //     $shift_names = $this->input->post('txt_shift_name');
    //     $shift_types = $this->input->post('txtM_SType');
    //     $days = $this->input->post('txtDay');

    //     // Validate the inputs
    //     if (!is_array($shift_codes)) {
    //         $shift_codes = []; // Initialize as an empty array if not valid
    //     }
    //     if (!is_array($shift_names)) {
    //         $shift_names = [];
    //     }
    //     if (!is_array($shift_types)) {
    //         $shift_types = [];
    //     }
    //     if (!is_array($days)) {
    //         $days = [];
    //     }

    //     // Format the data for output
    //     $shifts = [];
    //     foreach ($shift_codes as $index => $shift_code) {
    //         $shifts[] = [
    //             'day' => isset($days[$index]) ? $days[$index] : '',
    //             'shift_code' => $shift_code,
    //             'shift_time' => isset($shift_names[$index]) ? $shift_names[$index] : '',
    //             'shift_type' => isset($shift_types[$index]) ? $shift_types[$index] : ''
    //         ];
    //     }


    //     // Create response data
    //     $inserted_data = [
    //         'roster_code' => $roster_code,
    //         'roster_name' => $roster_name,
    //         'shifts' => $shifts
    //     ];

    //     if (empty($shift_codes)) {
    //         echo json_encode([
    //             'success' => false,
    //             'message' => 'No shift data provided.',
    //             'data' => []
    //         ]);
    //         return;
    //     }else{
    //         echo json_encode([
    //             'success' => true,
    //             'message' => 'Data inserted successfully',
    //             'data' => $inserted_data
    //         ]);
    //     }

    // }


    // public function insert_data2() {
    //     // Retrieve POST data
    //     $roster_code = $this->input->post('txtRoster_Code');
    //     $roster_name = $this->input->post('txtRoster_Name');
    //     $shift_codes = $this->input->post('txtSH_MO'); // Array for multiple shifts
    //     $shift_names = $this->input->post('txt_shift_name');
    //     $shift_types = $this->input->post('txtM_SType');
    //     $days = $this->input->post('txtDay');

    //     // Prepare data for response
    //     $daywise_shifts = [];
    //     foreach ($days as $index => $day) {
    //         $daywise_shifts[] = [
    //             'day' => $day,
    //             'shift_code' => $shift_codes[$index],
    //             'shift_name' => $shift_names[$index],
    //             'shift_type' => $shift_types[$index]
    //         ];
    //     }

    //     $inserted_data = [
    //         'roster_code' => $roster_code,
    //         'roster_name' => $roster_name,
    //         'daywise_shifts' => $daywise_shifts
    //     ];

    //     echo json_encode([
    //         'success' => true,
    //         'message' => 'Data inserted successfully',
    //         'data' => $inserted_data
    //     ]);
    // }




    public function insert_data()
    {

        $serial = $this->Db_model->getData('serial', 'tbl_serials', array("code" => "Roster"));
        $serialNo = $serial[0]->serial + 1;

        $dataset = json_decode($_POST['hdntext']);

        $currentYear = date('Y');
        $currentMonth = date('M');

        // $Day = '';


        // if ($dataset[0]->Day == 'MON') {
        //     $Day = 'Monday';
        // }if ($dataset[0]->Day == 'TUE') {
        //     $Day = 'Tuesday';
        // }if ($dataset[0]->Day == 'WED') {
        //     $Day = 'Wednesday';
        // }if ($dataset[0]->Day == 'THU') {
        //     $Day = 'Thursday';
        // }if ($dataset[0]->Day == 'FRI') {
        //     $Day = 'Friday';
        // }if ($dataset[0]->Day == 'SAT') {
        //     $Day = 'Saturday';
        // }if ($dataset[0]->Day == 'SUN') {
        //     $Day = 'Sunday';
        // }


        foreach ($dataset as $dataitems) {
            if ($dataitems->Day == 'MON') {
                $Day = 'Monday';
            } else if ($dataitems->Day == 'TUE') {
                $Day = 'Tuesday';
            } else if ($dataitems->Day == 'WED') {
                $Day = 'Wednesday';
            } else if ($dataitems->Day == 'THU') {
                $Day = 'Thursday';
            } else if ($dataitems->Day == 'FRI') {
                $Day = 'Friday';
            } else if ($dataitems->Day == 'SAT') {
                $Day = 'Saturday';
            } else if ($dataitems->Day == 'SUN') {
                $Day = 'Sunday';
            }
            $shiftarray = array(
                "RosterCode" => $this->input->post('txtRoster_Code'),
                'RosterName' => $this->input->post('txtRoster_Name'),
                'ShiftCode' => $dataitems->SHType,
                'DayName' => $Day,
                'ShiftType' => $dataitems->SType,
                'Date' => 'Weekly'
            );

            $result = $this->Db_model->insertData('tbl_rosterpatternweeklydtl', $shiftarray);
        }
        $this->session->set_flashdata('success_message', 'New Weekly Roster has been added successfully');

        $id = '1';
        $data = array(
            'RosterCode' => $this->input->post('txtRoster_Code'),
            'RosterName' => $this->input->post('txtRoster_Name'),
            // 'Status' => $id,
            'MonthType' => 'Weekly',
            'CurrentYear' => $currentYear,
            'Data' => 'Only Group'
        );

        $result = $this->Db_model->insertData("tbl_rosterpatternweeklyhd", $data);


        $serialdata = "";
        $condition = 0;
        $data = array("serial" => $serialNo);

        if ($result) {
            $condition = 1;


            $whereArr = array("code" => "RS");
            $result = $this->Db_model->updateData('tbl_serials', $data, $whereArr);

            //Genarate next designation code

            $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Designation'));
            $serial = "DS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
            $data['serial'] = ++$serial;
        } else {
            $serialdata = $this->Db_model->getData('serial', 'tbl_serials', array('code' => 'Designation'));
            $serial = "DS" . substr(("0000" . (int) $serialdata[0]->serial), strlen("0000" . $serialdata[0]->serial) - 4, 4);
            $data['serial'] = ++$serial;
        }

        redirect('/Master/Weekly_Roster2/');
    }

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
