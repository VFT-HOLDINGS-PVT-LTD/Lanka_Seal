<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_Levels extends CI_Controller {

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
     * Index page in Departmrnt
     */

    public function index() {

        $data['title'] = "User Levels | HRM System";
        $data['data_set'] = $this->Db_model->getData('user_level_id,user_level_name', 'tbl_user_level_master');
        $this->load->view('Master/User_Levels/index', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function insert_data() {
        $user_level = $this->input->post('txt_user_level');

        // Get the next user_level_id
        $this->db->select_max('user_level_id');
        $query = $this->db->get('tbl_user_level_master');
        $row = $query->row();
        $next_id = isset($row->user_level_id) ? $row->user_level_id + 1 : 1;

        $data = array(
            'user_level_id' => $next_id,
            'user_level_name' => $user_level
        );

        $result = $this->Db_model->insertData("tbl_user_level_master", $data);

        if ($result) {
            // Insert the user_level_id into tbl_user_permisions
            $permissions_data = array(
                'user_p_id' => $next_id
            );
            $this->db->insert('tbl_user_permisions', $permissions_data);
        }

        // Return a JSON response
        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'user_level' => $user_level
        ]);
    }

         /*
     * Insert permission data
     */

     public function insert_permission_data() {
        // Get the current maximum user_p_id (no +1 here)
        $this->db->select_max('user_p_id');
        $query = $this->db->get('tbl_user_permisions');
        $row = $query->row();
        $current_max_id = isset($row->user_p_id) ? $row->user_p_id : 1;
    
        // Alternatively, if you passed user_p_id directly from the frontend (preferred way):
        // $current_max_id = $this->input->post('user_p_id');
    
        $checked_permissions = $this->input->post('permissions'); // Array of checked permissions
    
        // All possible permissions
        $all_permissions = [
            "Dashboard", "master_data", "designation", "department", "holiday_types", "holidays", "shifts", "weekly_roster",
            "user_level", "leave_types", "banks", "bank_accounts", "payees", "loan_types", "allowance_types", "deduction_types",
            "branches", "employee_groups", "Employee_mgt", "add_employee", "add_employee_branch", "view_employee",
            "Attendance", "shift_allocation", "attendance_collection", "attendance_row_data", "manual_attendance",
            "manual_att_request", "Is_manual_Sup", "Is_manual_Admin", "attendance_process", "Leave_Transaction",
            "view_lv_balance", "leave_allocation", "leave_approve", "leave_app_sup", "leave_entry", "leave_request",
            "leave_adj", "Payroll", "allowance", "deduction", "loan_entry", "salary_increment", "salary_advance",
            "request_advance", "approve_advance", "payroll_process", "payroll_initialize", "Cheque", "write_cheque", "view_cheque",
            "Messages", "send_message", "view_message", "company", "company_profile", "Reports", "Master_Reports",
            "employee_report", "designation_report", "department_report", "holidays_report", "employee_birthdays",
            "Attendance_Report", "in_out_report", "overtime_report", "absence_report", "leave_report", "late_report",
            "monthly_summery_report", "leave_summery_report", "ot_summery_report", "Payroll_reports", "allowance_report",
            "deduction_report", "salary_advance_report", "paysheet_report", "payslip_report", "Analysis_Report",
            "month_absence_rpt", "attendance_rpt", "leave_rpt", "Tools", "System_backup", "dsh_report", "dsh_ch_1",
            "dsh_ch_2", "dsh_ch_3", "dsh_rpt_in_out", "dsh_rpt_paysheet", "dsh_rpt_leave", "dsh_rpt_emp_master",
            "dsh_rpt_sal_advance", "dsh_rpt__absence", "header_lv_nt"
        ];
    
        // Prepare data for insertion
        $permissions_data = ['user_p_id' => $current_max_id];
        foreach ($all_permissions as $perm) {
            $permissions_data[$perm] = in_array($perm, $checked_permissions ?? []) ? 1 : 0;
        }
    
        // Optional: Delete existing permission row before insert (if needed)
        $this->db->where('user_p_id', $current_max_id);
        $this->db->delete('tbl_user_permisions');
    
        // Insert permissions
        $this->db->insert('tbl_user_permisions', $permissions_data);
    
        echo json_encode([
            'status' => 'success',
            'message' => 'Permissions inserted for user_p_id: ' . $current_max_id,
            'user_p_id' => $current_max_id
        ]);
    }
    

     /*
     * Get permission data
     */

    public function get_permission_data() {
        $user_level_id = $this->input->post('user_level_select', TRUE);

        if ($user_level_id) {
            $this->db->select('tbl_user_level_master.user_level_name, tbl_user_permisions.*');
            $this->db->from('tbl_user_level_master');
            $this->db->join('tbl_user_permisions', 'tbl_user_level_master.user_level_id = tbl_user_permisions.user_p_id');
            $this->db->where('tbl_user_level_master.user_level_id', $user_level_id);
            $query = $this->db->get();

            $data = $query->result_array();
            echo json_encode($data);
        } else {
            echo json_encode(array("error" => "No user level selected."));
        }
    }

    public function update_permission_data() {
        $user_level_id = $this->input->post('user_level_id', TRUE);
        $selected_permissions = $this->input->post('permissions'); // array of selected permission names
    
        if (!$user_level_id) {
            $this->session->set_flashdata('error', 'User level is not selected.');
            redirect('Master/User_Levels');
        }
    
        // Get the full list of permissions used in the form
        $all_permissions = [
            "Dashboard", "master_data", "designation", "department",
            "holiday_types", "holidays", "shifts", "weekly_roster",
            "user_level", "leave_types", "banks", "bank_accounts",
            "payees", "loan_types", "allowance_types", "deduction_types",
            "branches", "employee_groups", "Employee_mgt", "add_employee",
            "add_employee_branch", "view_employee", "Attendance", "shift_allocation",
            "attendance_collection", "attendance_row_data", "manual_attendance", "manual_att_request",
            "Is_manual_Sup", "Is_manual_Admin", "attendance_process", "Leave_Transaction",
            "view_lv_balance", "leave_allocation", "leave_approve", "leave_app_sup",
            "leave_entry", "leave_request", "leave_adj", "Payroll",
            "allowance", "deduction", "loan_entry", "salary_increment",
            "salary_advance", "request_advance", "approve_advance", "payroll_process", "payroll_initialize",
            "Cheque", "write_cheque", "view_cheque", "Messages",
            "send_message", "view_message", "company", "company_profile",
            "Reports", "Master_Reports", "employee_report", "designation_report",
            "department_report", "holidays_report", "employee_birthdays", "Attendance_Report",
            "in_out_report", "overtime_report", "absence_report", "leave_report",
            "late_report", "monthly_summery_report", "leave_summery_report", "ot_summery_report",
            "Payroll_reports", "allowance_report", "deduction_report", "salary_advance_report",
            "paysheet_report", "payslip_report", "Analysis_Report", "month_absence_rpt",
            "attendance_rpt", "leave_rpt", "Tools", "System_backup",
            "dsh_report", "dsh_ch_1", "dsh_ch_2", "dsh_ch_3",
            "dsh_rpt_in_out", "dsh_rpt_paysheet", "dsh_rpt_leave", "dsh_rpt_emp_master",
            "dsh_rpt_sal_advance", "dsh_rpt__absence", "header_lv_nt"
        ];
    
        // Prepare permission data to update
        $permissions_data = [];
        foreach ($all_permissions as $permission) {
            $permissions_data[$permission] = in_array($permission, $selected_permissions ?? []) ? 1 : 0;
        }
    
        // Check if permission record already exists
        $exists = $this->db->get_where('tbl_user_permisions', ['user_p_id' => $user_level_id])->row();
    
        if ($exists) {
            // Update existing record
            $this->db->where('user_p_id', $user_level_id);
            $this->db->update('tbl_user_permisions', $permissions_data);
        } else {
            // Insert new record
            $permissions_data['user_p_id'] = $user_level_id;
            $this->db->insert('tbl_user_permisions', $permissions_data);
        }
    
        $this->session->set_flashdata('success', 'Permissions updated successfully.');
        redirect('Master/User_Levels');
    }    

    /*
     * Get Department data
     */

    public function get_details() {
        $id = $this->input->post('id');

        $whereArray = array('user_level_id' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('user_level_id,user_level_name', 'tbl_user_level_master');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $UL = $this->input->post("user_level_name", TRUE);


        $data = array("user_level_name" => $UL);
        $whereArr = array("user_level_id" => $ID);
        $result = $this->Db_model->updateData("tbl_user_level_master", $data, $whereArr);
        redirect(base_url() . "Master/User_Levels");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_user_level_master";
        $where = 'user_level_id';

        // Delete from tbl_user_permisions where user_p_id matches the id
        $this->db->where('user_p_id', $id);
        $this->db->delete('tbl_user_permisions');

        // Delete from tbl_user_level_master
        $this->Db_model->delete_by_id($id, $where, $table);

        echo json_encode(array("status" => TRUE));
    }

}
