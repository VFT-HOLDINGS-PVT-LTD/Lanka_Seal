<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_Types extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        $this->load->model('Db_model', '', TRUE);
    }

    public function index() {

        $this->load->helper('url');
        $data['title'] = "Deduction Types | HRM SYSTEM";
        $data['data_set'] = $this->Db_model->getData('Ded_ID,Deduction_name,IsActive,isFixed', 'tbl_deduction_types');
        $this->load->view('Master/Deduction_Types/index', $data);
    }

    /*
     * Insert Data
     */

    public function insert_data() {


        $Fixed = $this->input->post('isFixed');
        if ($Fixed == null) {
            $Fixed = 0;
        }

        $Active = $this->input->post('isActive');
        if ($Active == null) {
            $Active = 0;
        }

        $data = array(
            'Deduction_name' => $this->input->post('txt_deduction'),
            'IsActive' => $Active,
            'isFixed' => $Fixed
        );
        //var_dump($data);die;

        $result = $this->Db_model->insertData("tbl_deduction_types", $data);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {

            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'New Deduction has been added successfully');
        }

        redirect(base_url() . 'Master/Deduction_Types/'); //*********Redirect to designation form
    }

    /*
     * Get data
     */

    public function get_details() {
        $id = $this->input->post('id');

//                    echo "OkM " . $id;

        $whereArray = array('Ded_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Ded_ID,Deduction_name,isFixed', 'tbl_deduction_types');



        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $Name = $this->input->post("Allowance_Name", TRUE);
        $Is_fxd = $this->input->post("is_Fixced", TRUE);
        $Is_Act = $this->input->post("is_Active", TRUE);
//        var_dump($Is_fxd.$Is_Act);die;


        $data = array("Allowance_name" => $Name, "IsActive" => $Is_Act, "isFixed" => $Is_fxd);
        $whereArr = array("Alw_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_allowance_type", $data, $whereArr);
        redirect(base_url() . "Administration/Allowance_Types");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_deduction_types";
        $where = 'Ded_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }
    public function update_Data() {
        
        $BF=$this->input->post('leavebf');
        if ($BF == null) {
            $BF = 0;
        } elseif ($BF == 'on') {
            $BF=1;
        }
        
        $data = array(
           
            'Deduction_name' => $this->input->post('leave_name'),
            
            'isFixed' => $BF,
            'IsActive' => 1,
        );
        // $result = $this->Db_model->insertData("tbl_leave_types", $data);
        $whereArr = array("Ded_ID" => $this->input->post('id'));
            $this->Db_model->updateData("tbl_deduction_types", $data, $whereArr);
            redirect(base_url() . "Master/Deduction_Types/");
        
    }

}