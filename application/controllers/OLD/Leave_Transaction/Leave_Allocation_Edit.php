<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leave_Allocation_Edit extends CI_Controller
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

        $data['title'] = "Leave Allocation Edit | HRM System";
        // $data['data_set'] = $this->Db_model->getData('ID,EmpNo,Year,Entitle,Used,Balance', 'tbl_leave_allocation');
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT ID,tbl_empmaster.EmpNo,tbl_empmaster.Emp_Full_Name,Year,Entitle,Used,Balance,leave_name FROM tbl_leave_allocation INNER JOIN tbl_leave_types ON tbl_leave_types.Lv_T_ID=tbl_leave_allocation.Lv_T_ID INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo;");

        $this->load->view('Leave_Transaction/Leave_Allocation_Edit/index', $data);
    }

    /*
     * Insert Data
     */

    public function insert_Designation()
    {

        /*
         * Data array
         */
        $data = array(
            'Desig_Name' => $this->input->post('txt_desig_name'),
            'Desig_Order' => $this->input->post('txt_desig_order')
        );

        //**********Transaction Start
        $this->db->trans_start();

        //Insert Data
        $result = $this->Db_model->insertData("tbl_designations", $data);

        //**********Transaction complate
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {

            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'New Designation has been added successfully');
        }

        redirect(base_url() . 'Master/Designation/'); //*********Redirect to designation form
    }

    /*
     * Get data
     */


    public function updateAttView()
    {
        $id = $this->input->get('id');

        //    echo "OkM " . $id;

        $whereArray = array('ID' => $id);


        $this->Db_model->setWhere($whereArray);
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT ID,tbl_empmaster.EmpNo,tbl_empmaster.Emp_Full_Name,Year,Entitle,Used,Balance,leave_name FROM tbl_leave_allocation INNER JOIN tbl_leave_types ON tbl_leave_types.Lv_T_ID=tbl_leave_allocation.Lv_T_ID INNER JOIN tbl_empmaster ON tbl_empmaster.EmpNo = tbl_leave_allocation.EmpNo WHERE ID = '$id';");
        $data['title'] = "Leave_Allocation_Edit | HRM System";


        $this->load->view('Leave_Transaction/Leave_Allocation_Edit/update', $data);
    }
    public function Delete()
    {
        $id = $this->input->get('id');

        //   echo "Do you want to delete the entry? Please contact the cloud administrator";
        //    echo "Do you want to delete the entry? Please contact the cloud administrator:" . $id;

        $table = "tbl_leave_allocation";
        $where = 'id';
        $this->Db_model->delete_by_id($id, $where, $table);
        $this->session->set_flashdata('success_message', 'Leave allocation deleted successfully');
        redirect(base_url() . "Leave_Transaction/Leave_Allocation_Edit");
    }

    /*
     * Edit Data
     */

    public function edit()
    {
        $ID = $this->input->post("id", TRUE);
        $EmpNo = $this->input->post("EmpNo", TRUE);
        $Year = $this->input->post("Year", TRUE);
        $Entitle = $this->input->post("Entitle", TRUE);
        $Used = $this->input->post("Used", TRUE);
        $Balance = $this->input->post("Balance", TRUE);


        # get data from tbl_leave_allocation
        $leave_allocation_data=$this->Db_model->getfilteredData("SELECT Entitle, Used, Balance FROM tbl_leave_allocation WHERE ID = '$ID';");

        $old_entitle = $leave_allocation_data[0]->Entitle;
        $old_used=$leave_allocation_data[0]->Used;
        $old_balance=$leave_allocation_data[0]->Balance;

        $new_entitle=0;
        $new_used=$old_used;
        $new_balance=0;

        if($Entitle > $old_entitle){

            $new_balance=$old_balance+($Entitle-$old_entitle);

            $data = array('Entitle' => $Entitle, 'Used' => $new_used, 'Balance' => $new_balance);
            $whereArr = array("ID" => $ID);
            $result = $this->Db_model->updateData("tbl_leave_allocation", $data, $whereArr);

            $this->session->set_flashdata('success_message', 'Leave allocation updated successfully');
            redirect(base_url() . "Leave_Transaction/Leave_Allocation_Edit");

        }elseif($Entitle < $old_entitle){
            $this->session->set_flashdata('error_message', 'Entitlement is less than the old entitlement');
            redirect(base_url() . "Leave_Transaction/Leave_Allocation_Edit");
            
        }else{
            
            $data = array('Entitle' => $old_entitle, 'Used' => $old_used, 'Balance' => $old_balance);
            $whereArr = array("ID" => $ID);
            $result = $this->Db_model->updateData("tbl_leave_allocation", $data, $whereArr);
            $this->session->set_flashdata('success_message', 'Leave allocation updated successfully');
            redirect(base_url() . "Leave_Transaction/Leave_Allocation_Edit");
        }
        
       
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id)
    {
        $table = "tbl_designations";
        $where = 'Des_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

}