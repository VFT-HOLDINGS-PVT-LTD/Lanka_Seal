
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Shiftdata extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index_get($id=0) {
        if (!empty($id)) {
            $data = $this->db->select('tbl_empmaster.EmpNo,tbl_empmaster.Enroll_No,tbl_rosterpatternweeklydtl.* , tbl_shifts.*')->from('tbl_rosterpatternweeklydtl')->join('tbl_shifts', 'tbl_shifts.ShiftCode = tbl_rosterpatternweeklydtl.ShiftCode')->join('tbl_empmaster', 'tbl_empmaster.RosterCode = tbl_rosterpatternweeklydtl.RosterCode')->where('tbl_empmaster.EmpNo', $id)->get()->result();
        } else {
            $data = $this->db->select('tbl_empmaster.EmpNo,tbl_empmaster.Enroll_No,tbl_rosterpatternweeklydtl.* , tbl_shifts.*')->from('tbl_rosterpatternweeklydtl')->join('tbl_shifts', 'tbl_shifts.ShiftCode = tbl_rosterpatternweeklydtl.ShiftCode')->join('tbl_empmaster', 'tbl_empmaster.RosterCode = tbl_rosterpatternweeklydtl.RosterCode')->get()->result();
        
        }
        $this->response($data, 200);
        
    }

    
}
