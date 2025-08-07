<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class View_Letter_Templates extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }

        // Load helpers, models, and libraries
        $this->load->helper('form');
        $this->load->library(['session', 'form_validation']);
        $this->load->model('Db_model', 'Db_model');
        $this->load->database();
    }

    public function index() {
        $data['templates'] = $this->Db_model->getData('*', 'tbl_letter_templates');
        $this->load->view('Employee_Management/View_Letter_Templates/index', $data);
    }

    public function add() {
        $this->load->view('Employee_Management/View_Letter_Templates/index');
    }

    public function save() {
        $data = [
            'template_name'  => $this->input->post('template_name'),
            'letter_subject' => $this->input->post('letter_subject'),
            'letter_body'    => $this->input->post('letter_body'),
        ];

        $result = $this->Db_model->insertData('tbl_letter_templates', $data);

        if ($result) {
            $this->session->set_flashdata('success', 'Template saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save the template.');
        }
        redirect('Employee_Management/View_Letter_Templates/index');
    }

    public function edit_template($id) {
        $strSQL = "SELECT * FROM tbl_letter_templates WHERE id = " . $this->db->escape($id);
        $template = $this->Db_model->getfilteredData($strSQL);

        if (!empty($template)) {
            $data['template'] = $template[0];
            $data['id'] = $id;
            $this->load->view('Employee_Management/View_Letter_Templates/edit_template', $data);
        } else {
            $this->session->set_flashdata('error', 'Template not found.');
            redirect('Employee_Management/View_Letter_Templates/index');
        }
    }

    public function update($id) {
        $data = [
            'template_name'  => $this->input->post('template_name'),
            'letter_subject' => $this->input->post('letter_subject'),
            'letter_body'    => $this->input->post('letter_body'),
        ];

        $where = ['id' => $id];
        $result = $this->Db_model->updateData('tbl_letter_templates', $data, $where);

        if ($result) {
            $this->session->set_flashdata('success', 'Template updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update the template.');
        }
        redirect('Employee_Management/View_Letter_Templates/edit_template/' . $id);
    }

    public function generate_letter($id) {
        $this->load->database();

        $template = $this->Db_model->getfilteredData("SELECT * FROM tbl_letter_templates WHERE id = " . $this->db->escape($id));

        if (!empty($template)) {
            $data['template'] = $template[0];
            $data['id'] = $id;

            $this->db->select('EmpNo, Emp_Name_Int');
            $this->db->from('tbl_empmaster');
            $this->db->where('Status', 1);

            if ($this->input->post('txt_emp')) {
                $this->db->where('EmpNo', $this->input->post('txt_emp'));
            }

            if ($this->input->post('txt_emp_name')) {
                $this->db->like('Emp_Full_Name', $this->input->post('txt_emp_name'));
            }

            $data['employees'] = $this->Db_model->getfilteredData($this->db->get_compiled_select());
            $this->load->view('Employee_Management/View_Letter_Templates/generate_letter', $data);
        } else {
            $this->session->set_flashdata('error', 'Template not found.');
            redirect('Employee_Management/View_Letter_Templates/index');
        }
    }

    public function fill_letter($id) {
        $employee_no = $this->input->post('txt_emp');
        $employee_name = $this->input->post('txt_emp_name');
        $template_result = $this->Db_model->getfilteredData("SELECT * FROM tbl_letter_templates WHERE id = " . $this->db->escape($id));
        
        if (!empty($template_result)) {
            $template = $template_result[0];
            
            // Fetch the employee based on input
            $this->db->select('tbl_empmaster.*, tbl_branches.B_name, tbl_departments.Dep_Name, tbl_emp_group.EmpGroupName' );
            $this->db->from('tbl_empmaster');
            $this->db->join('tbl_branches', 'tbl_empmaster.B_id = tbl_branches.B_id', 'left');
            $this->db->join('tbl_departments', 'tbl_empmaster.Dep_ID = tbl_departments.Dep_ID', 'left');
            $this->db->join('tbl_emp_group', 'tbl_empmaster.Grp_ID = tbl_emp_group.Grp_ID', 'left');
            $this->db->where('tbl_empmaster.Status', 1);
            
            if (!empty($employee_no)) {
                $this->db->where('EmpNo', $employee_no);
            } elseif (!empty($employee_name)) {
                $this->db->like('Emp_Full_Name', $employee_name);
            }
            
            $query = $this->db->get();
            $employees = $query->result();
            
            if (!empty($employees)) {
                $employee = $employees[0];
                
                // Replacing placeholders
                $replacements = [
                    '[EMP_NO]' => $employee->EmpNo,
                    '[TITLE]' => $employee->Title,
                    '[EMP_NAME_INT]' => $employee->Emp_Name_Int,
                    '[EMP_FULL_NAME]' => $employee->Emp_Full_Name,
                    '[USER_NAME]' => $employee->username,
                    '[GENDER]' => $employee->Gender,
                    '[BRANCH]' => $employee->B_name,
                    '[DEPARTMENT]' => $employee->Dep_Name,
                    '[GROUP]' => $employee->EmpGroupName,
                    '[APPOINT_DATE]' => $employee->ApointDate,
                    '[ADDRESS]' => $employee->Address,
                    '[PHONE]' => $employee->Tel_mobile,
                    '[EMAIL]' => $employee->E_mail,
                    '[NIC]' => $employee->NIC,
                    '[BIRTHDAY]' => $employee->DOB,
                    '[RELIGION]' => $employee->Religion,
                    '[NIC_NUM]' => $employee->NIC,
                    '[OTHER_ID]' => $employee->Passport,
                    '[BASIC_SALARY]' => $employee->Basic_Salary,
                    '[TERMINATION_DATE]' => $employee->ResignDate,
                ];
                
                foreach ($replacements as $placeholder => $value) {
                    $template->letter_subject = str_replace($placeholder, $value, $template->letter_subject);
                    $template->letter_body = str_replace($placeholder, $value, $template->letter_body);
                }
                
                $data = [
                    'template' => $template,
                    'employee_address' => $employee->Address,
                    'employee_mobile' => $employee->Tel_mobile,
                    'employee_email' => $employee->E_mail,
                    'employee_no' => $employee->EmpNo,
                    'employee_name' => $employee->Emp_Full_Name,
                    'id' => $id
                ];
                
                // Render the same view with filled values
                $this->load->view('Employee_Management/View_Letter_Templates/generate_letter', $data);
            } else {
                $this->session->set_flashdata('error', 'No employee found with the given details.');
                redirect("Employee_Management/View_Letter_Templates/generate_letter/$id");
            }
        } else {
            $this->session->set_flashdata('error', 'Template not found.');
            redirect('Employee_Management/View_Letter_Templates');
        }
    }

    public function delete_template($id) {
        $this->Db_model->delete_by_id($id, 'id', 'tbl_letter_templates');
        $this->index();
    }
}