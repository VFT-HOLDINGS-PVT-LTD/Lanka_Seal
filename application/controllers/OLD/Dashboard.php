<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        $this->load->model('Db_model', '', TRUE);
    }

    public function index()
    {

        date_default_timezone_set('Asia/Colombo');
        $now = new DateTime();
        $Date = $now->format('Y-m-d'); 
        $month_day = $now->format('m-d');


        $data['title'] = "Dashboard | HRM System";
        $data['count'] = $this->Db_model->getfilteredData('select count(EmpNo) as count_emp from tbl_empmaster');
        $data['Bdays'] = $this->Db_model->getfilteredData("SELECT Emp_Full_Name, Tel_mobile, tbl_branches.B_name FROM tbl_empmaster INNER JOIN tbl_branches ON tbl_branches.B_id = tbl_empmaster.B_id WHERE DATE_FORMAT(DOB, '%m-%d') = '$month_day'");
        // $data['Bdays_count'] = $this->Db_model->getfilteredData("SELECT COUNT(Emp_Full_Name) as count FROM tbl_empmaster WHERE DATE_FORMAT(DOB, '%m-%d') = '$month_day'");
        // $data['Bdays_count'] = $data['Bdays_count'][0]->count;

        //**** Employee department chart data
        $data['sdata'] = $this->Db_model->getfilteredData("SELECT 
                                                            COUNT(EmpNo)as EmpCount , Dep_Name
                                                        FROM
                                                            tbl_empmaster
                                                                INNER JOIN
                                                            tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID
                                                        group by tbl_departments.Dep_ID");

        $date1 = new DateTime();
        $timestamp1 = date_format($date1, 'Y-m-d');

        //**** Employee day present (PR) count
        $data['data3']  = $this->Db_model->getfilteredData("select count(EmpNo) as allcountemp from tbl_empmaster ");

        // $data['data3']; die;
        $data['data2'] = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT Enroll_No) AS count FROM tbl_u_attendancedata WHERE AttDate = '$timestamp1'");
        $data['data1'] =  intval($data['data3'][0]->allcountemp) - intval($data['data2'][0]->count);
        // $data['data4']='';
        foreach ($data['data2'] as $sales) {
            $data['data4'] = intval($sales->count);
        }

        //         $data['sdata_gender'] = $this->Db_model->getfilteredData("SELECT
        //             COUNT(*) AS total_count,
        //     COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
        //     COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
        // FROM
        //     tbl_empmaster where Status=1");



        //**** Employee day present (PR) count
        $data['today_c'] = $this->Db_model->getfilteredData("select count(ID_Roster) as TodayCount from tbl_individual_roster where FDate = curdate() and DayStatus='PR' ");



        $currentUser = $this->session->userdata('login_user');
        $Emp = $currentUser[0]->EmpNo;

        $Year = date('Y');

        $data['data_leave'] = $this->Db_model->getfilteredData("SELECT 
                                                                        lv_typ.Lv_T_ID,
                                                                        lv_typ.leave_name,
                                                                        lv_al.Balance
                                                                    FROM
                                                                        tbl_leave_allocation lv_al
                                                                        right join
                                                                        tbl_leave_types lv_typ on lv_al.Lv_T_ID = lv_typ.Lv_T_ID
                                                                        where EmpNo='$Emp' and lv_al.Year = '$Year';
                                                                    ");

        //        var_dump($data['data_leave'] );die;


        $this->leave_allocation_process();
        $this->load->view('Dashboard/index', $data);
    }

    public function get_attendance_data()
    {
        $department = $this->input->post('department');
        $date = date('Y-m-d');

        if ($department === 'all') {
            // All departments
            $total = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) as total FROM tbl_empmaster WHERE Status = 1");
            $present = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT Enroll_No) as count
                FROM tbl_u_attendancedata
                WHERE AttDate = '$date'");
        } else {
            // Specific department
            $total = $this->Db_model->getfilteredData("SELECT COUNT(EmpNo) as total 
                FROM tbl_empmaster em
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE d.Dep_Name = '$department'");

            $present = $this->Db_model->getfilteredData("SELECT COUNT(DISTINCT ua.Enroll_No) as count 
                FROM tbl_u_attendancedata ua
                INNER JOIN tbl_empmaster em ON ua.Enroll_No = em.Enroll_No
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE em.Status = 1 AND ua.AttDate = '$date' AND d.Dep_Name = '$department'");
        }

        $attended = intval($present[0]->count);
        $absent = intval($total[0]->total) - $attended;

        echo json_encode([
            'attended' => $attended,
            'absent' => $absent,
            'title' => ucfirst($department) . ' Department Attendance'
        ]);
    }

    public function get_employee_data()
    {
        $department = $this->input->post('department');
        log_message('error', 'Department selected: ' . $department);

        if ($department === 'all') {
            // Query for all departments
            $result = $this->Db_model->getfilteredData("
                SELECT
                    COUNT(*) AS total_count,
                    COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
                    COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
                FROM tbl_empmaster
                WHERE Status = 1
            ");
        } else {
            // Escape the input to prevent SQL injection
            $escaped_dep = $this->db->escape($department);

            // Query for specific department
            $sql = "
                SELECT
                    COUNT(*) AS total_count,
                    COUNT(CASE WHEN Gender = 'Male' THEN 1 END) AS male_count,
                    COUNT(CASE WHEN Gender = 'Female' THEN 1 END) AS female_count
                FROM tbl_empmaster em
                INNER JOIN tbl_departments d ON em.Dep_ID = d.Dep_ID
                WHERE d.Dep_Name = $escaped_dep AND em.Status = 1
            ";

            $result = $this->Db_model->getfilteredData($sql);
        }

        echo json_encode([
            'total' => intval($result[0]->total_count),
            'male' => intval($result[0]->male_count),
            'female' => intval($result[0]->female_count)
        ]);
    }

    /**
     * Process leave allocation for employees based on company settings.
     * 
     */
    public function leave_allocation_process() {
        // Get company setting data
        $company_setting = $this->Db_model->getfilteredData("SELECT leave_year, leave_month FROM tbl_company_setting WHERE id=1");
        $leave_year = $company_setting[0]->leave_year;
        $leave_month = $company_setting[0]->leave_month;

        // Get current year and month
        $current_year = date('Y');
        $current_month = date('m');

        // Get employees
        $employees = $this->Db_model->getfilteredData("SELECT EmpNo, ApointDate FROM tbl_empmaster WHERE status=1 AND Active_process=1 AND leave_allocate_type='AUTO'");
       

        // Get leave types
        $leave_types = $this->Db_model->getfilteredData("SELECT Lv_T_ID, leave_name, leave_entitle,leave_BF FROM tbl_leave_types WHERE IsActive = 1");
        $annual_leave_id = $leave_types[0]->Lv_T_ID ?? null;
        $casual_leave_id = $leave_types[1]->Lv_T_ID ?? null;
        $annual_leave_BF = $leave_types[0]->leave_BF ?? 0;
        
        // Get logged user id
        $loggedEmpNo = $this->session->userdata('login_user')[0]->EmpNo;

        //check logged user is admin or not
        if($loggedEmpNo==9000){
           
            // Annual leave process (yearly)
            if ($current_year != $leave_year && $annual_leave_id) {

                foreach ($employees as $employee) {
                    $appointDate = new DateTime($employee->ApointDate);
                    $currentDate = new DateTime();
                    $interval = $currentDate->diff($appointDate);

                    $entitle = 0;

                    if ($interval->y >= 2) {
                        //check year is greater than or equal to 2
                        $entitle = 14;

                    } elseif ($interval->y >= 1 && $interval->y < 2) {
                        //check year is greater than or equal to 1 and less than 2
                        $quarters = [
                            [0, 3, 4], // quarter 1->(0-3 months)entitle 4
                            [3, 6, 7], // quarter 2->(3-6 months)entitle 7
                            [6, 9, 10], // quarter 3->(6-9 months)entitle 10
                            [9, 12, 14] // quarter 4->(9-12 months)entitle 14
                        ];
                        //loop quarters and add entitle
                        foreach ($quarters as $q) {
                            if ($interval->m >= $q[0] && $interval->m < $q[1]) {
                                $entitle = $q[2];
                                break;
                            }
                        }
                    }
                    
                   if ($entitle > 0) {

                        // Carry forward previous year's balance if leave_BF == 1
                        $prev_balance = 0;
                        if ($annual_leave_BF == 1) {
                            $prev = $this->Db_model->getfilteredData(
                                "SELECT Balance FROM tbl_leave_allocation WHERE EmpNo = '{$employee->EmpNo}' AND Year = '".($current_year-1)."' AND Lv_T_ID = '{$annual_leave_id}'"
                            );
                            
                            if ($prev) {
                                $prev_balance = floatval($prev[0]->Balance);
                            }
                        }

                        // Calculate new balance
                        $new_balance = $entitle + $prev_balance;
                       
                        // define data for tbl_leave_allocation
                        $data = [
                            'EmpNo' => $employee->EmpNo,
                            'Year' => $current_year,
                            'Lv_T_ID' => $annual_leave_id,
                            'Entitle' => $entitle,
                            'Used' => 0,
                            'Balance' => $new_balance,
                            'Apply_by' => $loggedEmpNo,
                            'Trans_time' => date('Y-m-d H:i:s'),
                        ];

                        $whereArr = array("EmpNo" => $employee->EmpNo, "Year" => $current_year, "Lv_T_ID" => $annual_leave_id);

                        // Check if record exists
                        $exists = $this->Db_model->getfilteredData("SELECT * FROM tbl_leave_allocation WHERE EmpNo = '{$employee->EmpNo}' AND Year = '{$current_year}' AND Lv_T_ID = '{$annual_leave_id}'");

                        if ($exists) {
                            // Update existing record
                            $updateData = [
                                'Entitle' => $entitle,
                                'Balance' => $new_balance,
                                'Apply_by' => $loggedEmpNo,
                                'Trans_time' => date('Y-m-d H:i:s'),
                            ];
                            $this->Db_model->updateData("tbl_leave_allocation", $updateData, $whereArr);
                        } else {
                            // Insert new record
                            $this->Db_model->insertData("tbl_leave_allocation", $data);
                        }
                    }
                }
                $this->update_company_settings_year($current_year);
            }

            // Casual leave process (monthly)
            if ($leave_month != $current_month && $casual_leave_id) {

                foreach ($employees as $employee) {
                $appointDate = new DateTime($employee->ApointDate);
                $currentDate = new DateTime();
                $interval = $currentDate->diff($appointDate);

                $entitle = 0;
                if ($interval->y >= 1) {
                    //check year is greater than or equal to 1(add 7 entile)
                    $entitle = 7;

                } elseif ($interval->y >= 0 && $interval->y < 1) {
                    //check year is greater than or equal to 0 and less than 1(add 0.5 entile for every month)
                    $months = min($interval->m, 11);
                    if ($months >= 1) {
                    $entitle = 0.5 * $months;
                    }
                }

                if ($entitle > 0) {
                    $whereArr = array("EmpNo" => $employee->EmpNo, "Year" => $current_year, "Lv_T_ID" => $casual_leave_id);
                    // Get previous balance
                    $prev = $this->Db_model->getfilteredData("SELECT Balance FROM tbl_leave_allocation WHERE EmpNo = '{$employee->EmpNo}' AND Year = '{$current_year}' AND Lv_T_ID = '{$casual_leave_id}'");
                    $prev_balance = $prev ? floatval($prev[0]->Balance) : 0;

                    // Calculate new balance
                    $new_balance = $prev_balance + $entitle;

                    // define data for leave allocation
                    $data = [
                        'EmpNo' => $employee->EmpNo,
                        'Year' => $current_year,
                        'Lv_T_ID' => $casual_leave_id,
                        'Entitle' => $entitle,
                        'Used' => 0,
                        'Balance' => $new_balance,
                        'Apply_by' => $loggedEmpNo,
                        'Trans_time' => date('Y-m-d H:i:s'),
                    ];
                    //check if leave allocation already exists(update data) else insert data
                    if ($prev) {
                    $updateData = [
                        'Entitle' => $entitle,
                        'Balance' => $new_balance,
                        'Apply_by' => $loggedEmpNo,
                        'Trans_time' => date('Y-m-d H:i:s'),
                    ];
                    $this->Db_model->updateData("tbl_leave_allocation", $updateData, $whereArr);
                    } else {
                    $this->Db_model->insertData("tbl_leave_allocation", $data);
                    }
                }
                }
                //update company settings month
                $this->update_company_settings_month($current_month);
            }
        }
        
    }

    #update company settings year
    public function update_company_settings_year($leave_year){
        $data = array("leave_year" => $leave_year);
        $whereArr = array("id" => 1);
        $result = $this->Db_model->updateData("tbl_company_setting", $data, $whereArr);
    }

    #update company settings month
    public function update_company_settings_month($leave_month){
        $data = array("leave_month" => $leave_month);
        $whereArr = array("id" => 1);
        $result = $this->Db_model->updateData("tbl_company_setting", $data, $whereArr);
    }
}
