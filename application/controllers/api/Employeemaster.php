<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Employeemaster extends REST_Controller {
      
    public function __construct() {
        parent::__construct();
        $this->load->model('api_models/EmpMaster_model', 'EmpMaster');
        $this->load->model('api_models/Designation_model');
        $this->load->model('api_models/Department_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get() {
        $id = $this->input->get('id');
        $results = $this->EmpMaster->getEmployeeDataEnrollNo($id);
        $EmployeeList = [];  
        
        if($results) {
            foreach($results as $result) {
                unset($result['password']);
                $designation = $this->Designation_model->getDesignation($result['Des_ID']);
                $department = $this->Department_model->getDepartment($result['Dep_ID']);
                $result['des_name'] = $designation ? $designation['Desig_Name'] : 'Unknown';

                $result['dep_name'] = $department ? $department['Dep_Name'] : 'Unknown';
                // Get image path
                $image_path = FCPATH . 'assets/images/Employees/' . $result['Image'];
                // Check if image exists
                if(!empty($result['Image']) && file_exists($image_path)) {
                    // Convert image to base64
                    $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
                    $image_data = file_get_contents($image_path);
                    $base64_image = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
                    
                    // Add base64 image to result object
                    $result['image_url'] = $base64_image;
                    // $this->response($results, REST_Controller::HTTP_OK);
                    $EmployeeList[] = $result;
                    
                } else {
                    // Handle the case where the image doesn't exist
                    $result['image_url'] = '';
                    $EmployeeList[] = $result;
                }
            }
            
            $this->response(['status' => true, 'data' => $EmployeeList], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }
}