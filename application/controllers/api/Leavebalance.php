<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Leavebalance extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/LeaveType_model', 'LeaveType');
        $this->load->model('api_models/LeaveAllocation_model', 'LeaveAllocation');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function index_get()
    {
        $id = $this->input->get('id');
        $year = $this->input->get('year');
        $totalUsed = 0;
        $totalBalance = 0;
        $totalAllocated = 0;
        $temp = [];
        $leaveAllocationList = $this->LeaveAllocation->getEmployeeLeaveAllocation($id, $year);
        if ($leaveAllocationList != null) {
            foreach ($leaveAllocationList as $leaveAllocation) {
                $leaveTypeList = $this->LeaveType->getAllLeaveType($leaveAllocation['Lv_T_ID']);

                $leaveTypeBalance[] = [
                    'Leave_Type_ID' => $leaveTypeList[0]->Lv_T_ID,
                    'Leave_Type_Name' => $leaveTypeList[0]->leave_name,
                    'Leave_Entitle' => $leaveAllocation['Entitle'],
                    'Leave_Used' => $leaveAllocation['Used'],
                    'Leave_Balance' => $leaveAllocation['Balance'],
                ];
                $totalUsed += $leaveAllocation['Used'];
                $totalBalance += $leaveAllocation['Balance'];
                $totalAllocated += $leaveAllocation['Entitle'];
            }

            $results = [
                "EmpNo" => $leaveAllocationList[0]['EmpNo'],
                "Year" => $leaveAllocationList[0]['Year'],
                "TotalUsed" => $totalUsed,
                "TotalBalance" => $totalBalance,
                "TotalEntitled" => $totalAllocated,
                "LeaveTotalBalance" => $leaveTypeBalance,
            ];
            $this->response($results, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'No balance ware found'
            ], REST_Controller::HTTP_OK);
        }
    }

}