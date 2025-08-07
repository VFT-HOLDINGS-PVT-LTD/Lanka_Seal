<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index_get() {
        $this->response(array('status'=>'true','message'=>'connection successfull'),200);
    }
    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $password = hash('sha512', $password);
        $query = $this->db->get_where('tbl_empmaster',array('username'=>$username,'password'=>$password));
        if($query->num_rows() > 0) {
            $data = $query->row_array();
            $res_data = array(
                'EmpNo' => $data['EmpNo'],
                'Enroll_No' => $data['Enroll_No'],
                'Is_allow_login' => $data['Is_allow_login'],
                'username' => $data['username'],
                'Status' => $data['Status'],
            );
            $this->response($data, REST_Controller::HTTP_ACCEPTED);
        } else {
            $this->response(array('message' => 'Authentication failed'), REST_Controller::HTTP_UNAUTHORIZED);

        }
    }

   

   
}
