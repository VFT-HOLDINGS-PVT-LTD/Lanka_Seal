<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_Analysis_Chart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Salary_Analysis_chart_new
     */

    public function index() {

        $attendance_summary = [];

        // Loop through last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $filter = '';
            if (!empty($dept)) {
                $filter = "WHERE dep.Dep_ID = " . $this->db->escape($dept);
            }

            // Get total employees
            $total_query = $this->db->query("
                SELECT COUNT(*) AS total_employees
                FROM tbl_empmaster e
                LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                $filter
            ");
            $total_employees = (int)$total_query->row()->total_employees;

            // Get present count for the date
            $present_query = $this->db->query("
                SELECT COUNT(DISTINCT a.Enroll_No) AS present_count
                FROM tbl_u_attendancedata a
                LEFT JOIN tbl_empmaster e ON e.Enroll_No = a.Enroll_No
                LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                WHERE a.AttDate = '$date'
                " . (!empty($dept) ? " AND dep.Dep_ID = " . $this->db->escape($dept) : "") . "
            ");
            $present_count = (int)$present_query->row()->present_count;
            $absent_count = $total_employees - $present_count;

            $attendance_summary[] = [
                'date' => $date,
                'present' => $present_count,
                'absent' => $absent_count
            ];
        }

        $data['weekly_attendance'] = json_encode($attendance_summary);
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Analysis/Attendance_Analysis_Chart', $data);

    }

    public function filter_attendace_data() {
        
        $dept = $this->input->post("cmb_dep");
        $fiter_time_range = $this->input->post("fiter_time_range");

        if($dept != null && $fiter_time_range ==1){
            
           // Add filters
            $filter = '';
            if (!empty($dept)) {
                $filter = "WHERE dep.Dep_ID = " . $this->db->escape($dept);
            }
        
            // Combined and filtered query
            $query = $this->db->query("SELECT
                    total_employees,
                    present_count,
                    (total_employees - present_count) AS absent_count
                FROM (
                    SELECT
                        (SELECT COUNT(*) 
                            FROM tbl_empmaster e
                            LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                            $filter
                        ) AS total_employees,
        
                        (SELECT COUNT(DISTINCT a.Enroll_No)
                            FROM tbl_u_attendancedata a
                            LEFT JOIN tbl_empmaster e ON e.Enroll_No = a.Enroll_No
                            LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                            WHERE a.AttDate = CURDATE()
                            " . (!empty($filter) ? " AND dep.Dep_ID = " . $this->db->escape($dept) : "") . "
                        ) AS present_count
                ) AS attendance_summary
            ");
        
            $result = $query->row();
            $present_count = (int)$result->present_count;
            $absent_count = (int)$result->absent_count;
        
            $data['attendance_summary'] = json_encode([
                ['name' => 'Present', 'y' => $present_count, 'color' => '#28a745'],
                ['name' => 'Absent', 'y' => $absent_count, 'color' => '#007bff']
            ]);
        
            $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        
            $this->load->view('Reports/Analysis/Attendance_Analysis_Chart', $data);

        }elseif($fiter_time_range ==2 && $dept == null){
            
            $attendance_summary = [];

            // Loop through last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $filter = '';
                if (!empty($dept)) {
                    $filter = "WHERE dep.Dep_ID = " . $this->db->escape($dept);
                }

                // Get total employees
                $total_query = $this->db->query("
                    SELECT COUNT(*) AS total_employees
                    FROM tbl_empmaster e
                    LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                    $filter
                ");
                $total_employees = (int)$total_query->row()->total_employees;

                // Get present count for the date
                $present_query = $this->db->query("
                    SELECT COUNT(DISTINCT a.Enroll_No) AS present_count
                    FROM tbl_u_attendancedata a
                    LEFT JOIN tbl_empmaster e ON e.Enroll_No = a.Enroll_No
                    LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                    WHERE a.AttDate = '$date'
                    " . (!empty($dept) ? " AND dep.Dep_ID = " . $this->db->escape($dept) : "") . "
                ");
                $present_count = (int)$present_query->row()->present_count;
                $absent_count = $total_employees - $present_count;

                $attendance_summary[] = [
                    'date' => $date,
                    'present' => $present_count,
                    'absent' => $absent_count
                ];
            }

            $data['weekly_attendance'] = json_encode($attendance_summary);
            $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

            $this->load->view('Reports/Analysis/Attendance_Analysis_Chart', $data);

        }elseif($fiter_time_range ==2 && $dept != null){
            $attendance_summary = [];

            // Loop through last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $filter = '';
                if (!empty($dept)) {
                    $filter = "WHERE dep.Dep_ID = " . $this->db->escape($dept);
                }

                // Get total employees
                $total_query = $this->db->query("
                    SELECT COUNT(*) AS total_employees
                    FROM tbl_empmaster e
                    LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                    $filter
                ");
                $total_employees = (int)$total_query->row()->total_employees;

                // Get present count for the date
                $present_query = $this->db->query("
                    SELECT COUNT(DISTINCT a.Enroll_No) AS present_count
                    FROM tbl_u_attendancedata a
                    LEFT JOIN tbl_empmaster e ON e.Enroll_No = a.Enroll_No
                    LEFT JOIN tbl_departments dep ON dep.Dep_ID = e.Dep_ID
                    WHERE a.AttDate = '$date'
                    " . (!empty($dept) ? " AND dep.Dep_ID = " . $this->db->escape($dept) : "") . "
                ");
                $present_count = (int)$present_query->row()->present_count;
                $absent_count = $total_employees - $present_count;

                $attendance_summary[] = [
                    'date' => $date,
                    'present' => $present_count,
                    'absent' => $absent_count
                ];
            }

            $data['weekly_attendance'] = json_encode($attendance_summary);
            $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

            $this->load->view('Reports/Analysis/Attendance_Analysis_Chart', $data);
            
        }else{
            $query = $this->db->query("SELECT
            total_employees,
            present_count,
            (total_employees - present_count) AS absent_count
            FROM (
            SELECT
                (SELECT COUNT(*) FROM tbl_empmaster) AS total_employees,
                (SELECT COUNT(DISTINCT Enroll_No)
                 FROM tbl_u_attendancedata
                 WHERE AttDate = CURDATE()) AS present_count
            ) AS attendance_summary
            ");

            $result = $query->row();
            $present_count = (int)$result->present_count;
            $absent_count = (int)$result->absent_count;

            $data['attendance_summary'] = json_encode([
                ['name' => 'Present', 'y' => $present_count, 'color' => '#28a745'],
                ['name' => 'Absent', 'y' => $absent_count, 'color' => '#007bff']
            ]);

            $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

            $this->load->view('Reports/Analysis/Attendance_Analysis_Chart', $data);

        }
        
    }
        
    
    

}