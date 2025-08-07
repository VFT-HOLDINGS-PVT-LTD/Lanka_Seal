<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Leaveentry extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_models/EmpMaster_model', 'EmpMaster');
        $this->load->model('api_models/EmpGroup_model', 'EmpGroup');
        $this->load->model('api_models/LeaveAllocation_model', 'LeaveAllocation');
        $this->load->model('api_models/LeaveEntry_model', 'LeaveEntry');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');

    }

    public function index_get()
    {
        $id = $this->input->get('id');
        $result = $this->LeaveEntry->getAllLeaveEntry($id);
        if ($result) {
            $this->response(['status' => true, 'data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'No data found'], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = json_decode($this->input->raw_input_stream, true);
        $group_id = $this->getEmployeeGroupID($data['EmpNo']);
        $supervisor_id = $this->getSupervisorID($group_id[0]->Grp_ID);
        $Start_Date = date('Y-m-d', strtotime($data['Start_Leave_Date']));
        unset($data['Start_Leave_Date']);
        $End_Date = date('Y-m-d', strtotime($data['End_Leave_Date']));
        unset($data['End_Leave_Date']);
        $data['Approved_by'] = $supervisor_id[0]->Sup_ID;

        if ($Start_Date == $End_Date) {
            $data['Leave_Date'] = $Start_Date;
            $result = $this->LeaveEntry->insert_leave_entry($data);
        } else {
            $CurrentDate = $Start_Date;
            while ($CurrentDate <= $End_Date) {
                $data['Leave_Date'] = $CurrentDate;
                $result = $this->LeaveEntry->insert_leave_entry($data);
                $CurrentDate = date('Y-m-d', strtotime($CurrentDate . ' +1 day'));
            }
        }


        
        if ($result) {
            $this->response(['status' => true, 'message' => 'Leave entry added successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Failed to add leave entry'], REST_Controller::HTTP_OK);
        }
    }

    public function index_put()
    {
        $id = $this->input->get('id');
        $post_data = json_decode($this->input->raw_input_stream, true);
        if ($post_data['Is_Approve'] != '1') {
            $result = $this->LeaveEntry->update_leave_entry($id, $post_data);
            if ($result) {
                $this->response(['status' => true, 'message' => 'Leave entry updated successfully'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => 'Failed to update leave entry'], REST_Controller::HTTP_OK);
            }
        } else {
            $this->setLeaveBalance($id);
        }
    }

    public function index_delete()
    {
        $id = $this->input->get('id');
        $data = json_decode($this->input->raw_input_stream, true);
        $empid = $data['EmpNo'];
        $result = $this->LeaveEntry->deleteMask_leave_entry($id, $empid);
        if ($result) {
            $this->response(['status' => true, 'message' => 'Leave entry deleted successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Failed to delete leave entry'], REST_Controller::HTTP_OK);
        }
    }


    // process functions

    public function getEmployeeGroupID($id)
    {
        $result = $this->EmpMaster->getEmployeeGroupID($id);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getSupervisorID($id)
    {
        $result = $this->EmpGroup->getSupervisorID($id);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getDateInRange($start_date, $end_date)
{
    // Convert the dates to DateTime objects
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);

    // Add one day to include the end date in the range
    $end->modify('+1 day');

    // Create a DatePeriod instance
    $interval = new DateInterval('P1D'); // 1-day interval
    $date_range = new DatePeriod($start, $interval, $end);

    // Collect the dates in an array
    $dates = [];
    foreach ($date_range as $date) {
        $dates[] = $date->format('Y-m-d'); // Format the date as 'YYYY-MM-DD'
    }

    return $dates;
}


    public function setLeaveBalance($id) {
        $leave_entry_record = $this->LeaveEntry->getLeaveEntry($id);
        $empid = $leave_entry_record[0]['EmpNo'];
        $year = $leave_entry_record[0]['Year'];
        $lvtid = $leave_entry_record[0]['LV_T_ID'];
        $leave_allocation_record = $this->LeaveAllocation->getLeaveAllocation($empid, $year, $lvtid);
        $lcount = intval($leave_entry_record[0]['Leave_Count']);
        $leave_allocation_id = $leave_allocation_record[0]['ID'];
        $db_data = array(
            'Used' => $leave_allocation_record[0]['Used'] + $lcount,
            'Balance' => $leave_allocation_record[0]['Balance'] - $lcount,
        );

        $result = $this->LeaveAllocation->setLeaveBalanceData($leave_allocation_id, $db_data);
        
        if ($result) {
            $this->response(['status' => true, 'message' => 'Leave Approved successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Failed to approve leave'], REST_Controller::HTTP_OK);
        }
    }



}