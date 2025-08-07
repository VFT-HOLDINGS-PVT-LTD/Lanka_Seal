
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Announcement extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index_get($id=0) {
        if (!empty($id)) {
            $data = $this->db->select('*')->from('tbl_announcement')->where('ID', $id)->get()->result();
        } else {
        $data = $this->db->select('*')->from('tbl_announcement')->get()->result();
        }
        $this->response($data, 200);
    }

    public function index_post() {
        $data = $this->post();
        $result = $this->db->insert('tbl_announcement', $data);
        if ($result) {
            $this->response(array("message"=>"Success",'status'=>true),200);
        }else{
            $this->response(array("message"=>"Failed",'status'=>false),200);
        }
    }

    public function index_put($id=0) {
        $data = $this->put();
        $result = $this->db->where('ID', $id)->update('tbl_announcement', $data);
        if ($result) {
            $this->response(array("message"=>"Success",'status'=>true),200);
        }else{
            $this->response(array("message"=>"Failed",'status'=>false),200);
        }
    }

    public function index_delete($id=0) {
        $result = $this->db->where('ID', $id)->delete('tbl_announcement');
        if ($result) {
            $this->response(array("message"=>"Success",'status'=>true),200);
        }else{
            $this->response(array("message"=>"Failed",'status'=>false),200);
        }
    }
}
