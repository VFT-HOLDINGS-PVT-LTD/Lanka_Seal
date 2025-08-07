<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

        $this->load->model('Db_model', '', TRUE);
    }

    public function index() {

        $this->load->helper('url');
        $data['title'] = 'Branches | HRM System';
//        $data['data_array'] = $this->Db_model->getData2('tbl_branches',array('B_id,B_name,Address,Tel1,Tel2,Fax,Email') );
        $data['data_array'] = $this->Db_model->getData('B_id,B_name,Address,Tel1,Tel2,Fax,Email', 'tbl_branches');

       
        
        $this->load->view('Master/Branch/index', $data);
    }

   
    public function insert_branch() {

        $dataArr = array(
            
            'B_Name' => $this->input->post('txt_B_name'),
            'Address' => $this->input->post('txt_address'),
            'Tel1' => $this->input->post('txt_tp'),
            'Tel2' => $this->input->post('txt_mobile'),
            'Email' => $this->input->post('txt_fax'),
            'Fax' => $this->input->post('txt_Email')
        );

        $result = $this->Db_model->insertData("tbl_branches", $dataArr);


       $this->session->set_flashdata('success_message', 'New Branch has been added successfully');

        
        redirect(base_url() . 'Master/Branch/');
    }

    public function branch_details() {
        $id = $this->input->post('id');
//            echo "OkM " . $id;
        $whereArray = array('B_id' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('B_id,B_name,Address,Tel1,Tel2,Fax,Email', 'tbl_branches');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    public function edit() {
        $B_Code = $this->input->post("id", TRUE);
        $B_name = $this->input->post("B_name", TRUE);
        $Address = $this->input->post("Address", TRUE);
        $TelNo = $this->input->post("TelNo", TRUE);
        $TelNo1 = $this->input->post("TelNo1", TRUE);
        $FaxNo = $this->input->post("FaxNo", TRUE);
        $Email = $this->input->post("Email", TRUE);
        $IsActive = 1;

        $data = array("B_name" => $B_name,"Address" => $Address,"Tel1" => $TelNo,"Tel2" => $TelNo1,"Fax" => $FaxNo,"Email" => $Email,"IsActive" => $IsActive);
        $whereArr = array("B_id" => $B_Code);
        $result = $this->Db_model->updateData("tbl_branches", $data, $whereArr);
        redirect(base_url() . "index.php/Master/Branch/");
    }
    
    
       public function ajax_delete($id)
	{
                $table = "tbl_branches";
		$this->Db_model->delete_by_code($id,$table);
		echo json_encode(array("status" => TRUE));
	}

}
