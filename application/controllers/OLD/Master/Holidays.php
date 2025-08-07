<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Holidays extends CI_Controller {

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

        $data['title'] = "Holidays | HRM System";
        $data['data_set'] = $this->Db_model->getData('id,HTCode,HTDescription', 'tbl_holiday_types');
        $data['data_set_H'] = $this->Db_model->getData('Holy_ID,HTCode,Hdate,H_description', 'tbl_holidays');
        $this->load->view('Master/Holidays/index', $data);
    }

    /*
     * Insert
     */

    public function insert_data() {

        $data = array(
            'HTCode' => $this->input->post('cmb_HDay_type'),
            'Hdate' => $this->input->post('txt_HDate'),
            'H_description' => $this->input->post('txt_Description')
        );

        $result = $this->Db_model->insertData("tbl_holidays", $data);

        $this->session->set_flashdata('success_message', 'New Holiday type has been added successfully');


        redirect(base_url() . 'Master/Holidays/');
    }

    /*
     * Get data
     */

    public function get_details() {
        $id = $this->input->post('Holy_ID');

        $whereArray = array('Holy_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Holy_ID,Hdate,HTCode,H_description', 'tbl_holidays');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $H_date = $this->input->post("H_Date", TRUE);
        $H_code = $this->input->post("H_Code", TRUE);
        $H_Desc = $this->input->post("H_Desc", TRUE);

        $data = array("Hdate" => $H_date, 'HTCode' => $H_code,'H_description' => $H_Desc);
        $whereArr = array("Holy_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_holidays", $data, $whereArr);
        redirect(base_url() . "Master/Holidays/");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_holidays";
        $where = 'Holy_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}
