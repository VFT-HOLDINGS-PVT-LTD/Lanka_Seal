<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_Initialize extends CI_Controller {

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

        $data['title'] = "Attendance Initialize | HRM System";
        $this->load->view('Attendance/Attendance_Initialize/index', $data);
    }

    /*
     * initialize Data
     */

    public function initialize() {



        $cat = $this->input->post('cmb_cat');
        if ($cat == "Employee") {
            $cat2 = $this->input->post('txt_nic');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE EmpNo='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Department") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Dep_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Designation") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Des_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }
        if ($cat == "Employee_Group") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Grp_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }

        if ($cat == "Company") {
            $cat2 = $this->input->post('cmb_cat2');
            $string = "SELECT EmpNo FROM tbl_empmaster WHERE Cmp_ID='$cat2' and Status = 1";
            $EmpData = $this->Db_model->getfilteredData($string);
        }


        $from_date = $this->input->post('txt_from_date');
        $to_date = $this->input->post('txt_to_date');




        $Count = count($EmpData);

        for ($i = 0; $i < $Count; $i++) {

            $EmpN = $EmpData[$i]->EmpNo;

            $this->Db_model->getfilteredDelete("DELETE FROM tbl_individual_roster WHERE FDate between '$from_date' and '$to_date' and EmpNo= $EmpN");


            $this->Db_model->getfilteredDelete("DELETE FROM tbl_ot_d WHERE OTDate between '$from_date' and '$to_date' and EmpNo= $EmpN");
        }


        $this->session->set_flashdata('success_message', 'Attendance Initialize successfully');
        redirect(base_url() . "Attendance/Attendance_Initialize");
    }

}
