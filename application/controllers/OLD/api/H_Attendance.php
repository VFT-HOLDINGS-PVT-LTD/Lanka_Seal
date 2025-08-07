<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Attendance extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index_get() {
        $this->response(array("message"=>"Success"), 200);
    }

    public function index_post() {
        $data = $this->post();
        $this->db->insert('tbl_u_attendancedata', $data);
        $this->response(array("message"=>"Success"), 200);
        
    }

    public function index_put() {
        
    }

    public function index_delete() {
        
    }
    public function of_user_by_date_range_without_break_get($id=0) {
        
        $form_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $from_date = new DateTime($form_date);
        $to_date = new DateTime($to_date);
        $to_date->modify('+1 day');
        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($from_date, $interval, $to_date);
        $dates_array = [];
        foreach($daterange as $date) {
            $dates_array[] = $date->format('Y-m-d');
        }
        $result_array = [];
        foreach($dates_array as $date) {
            $single_result['date']= $date;
            $query = $this->db->select('Enroll_No,AttDate,MIN(AttTime) as InTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 1)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['in_time'] = $result[0]->InTime;
            }
            $query = $this->db->select('Enroll_No,AttDate,MAX(AttTime) as OutTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 2)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['out_time'] = $result[0]->OutTime;
            }
                
                array_push($result_array, $single_result);
                $single_result = [];
            
            
        }
        $this->response($result_array, 200);
    }
    public function of_user_by_date_range_get($id=0) {
        
        $form_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $from_date = new DateTime($form_date);
        $to_date = new DateTime($to_date);
        $to_date->modify('+1 day');
        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($from_date, $interval, $to_date);
        $dates_array = [];
        foreach($daterange as $date) {
            $dates_array[] = $date->format('Y-m-d');
        }
        $result_array = [];
        foreach($dates_array as $date) {
            $single_result['date']= $date;
            $query = $this->db->select('Enroll_No,AttDate,MIN(AttTime) as InTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 1)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['in_time'] = $result[0]->InTime;
            }

            $query = $this->db->select('Enroll_No,AttDate,MIN(AttTime) as InTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 3)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['break_in_time'] = $result[0]->InTime;
            }

            $query = $this->db->select('Enroll_No,AttDate,MAX(AttTime) as OutTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 4)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['break_out_time'] = $result[0]->OutTime;
            }
            
            $query = $this->db->select('Enroll_No,AttDate,MAX(AttTime) as OutTime')->from('tbl_u_attendancedata')->where('Enroll_No', $id)->where('AttDate', $date)->where('Status', 2)->group_by('AttDate')->get();
            $result = $query->result();
            if(count($result) > 0) {
                $single_result['out_time'] = $result[0]->OutTime;
            }
                
                array_push($result_array, $single_result);
                $single_result = [];
            
            
        }
        $this->response($result_array, 200);
    }
}
