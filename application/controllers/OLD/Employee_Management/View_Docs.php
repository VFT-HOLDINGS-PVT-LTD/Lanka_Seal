<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_Docs extends CI_Controller {

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
        $this->load->library('session');
        $this->load->library(['upload', 'session']);
        $this->load->helper('url');
        $this->load->helper('url');
        $this->load->helper('form');
    }


    public function index() {
        $this->load->view('Employee_Management/View_Docs/index');
    }

    public function fetch_docs()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    
        $emp_no = $this->input->post('txt_emp_view');
        $emp_name = $this->input->post('txt_emp_name_view');
    
        if (empty($emp_no) && empty($emp_name)) {
            echo '<div class="alert alert-warning">Please enter Employee No or Name.</div>';
            return;
        }
    
        // Get Enroll_No
        $this->db->select('Enroll_No');
        $this->db->from('tbl_empmaster');
        if (!empty($emp_no)) {
            $this->db->where('Enroll_No', $emp_no);
        } else {
            $this->db->where('Emp_Full_Name', $emp_name);
        }
        $emp = $this->db->get()->row();
    
        if (!$emp) {
            echo '<div class="alert alert-danger">Employee not found.</div>';
            return;
        }
    
        $this->db->where('Enroll_No', $emp->Enroll_No);
        $docs = $this->db->get('tbl_emp_docs')->result();

        if (!$docs) {
            echo '<div class="alert alert-info">No documents found for this employee.</div>';
            return;
        }
    
        // Return HTML view from separate file
        $data['docs'] = $docs;
        $this->load->view('employee_management/View_Docs/view_doc_table', $data);
    }
      


    /*
     * upload documents
     */
    public function upload_docs()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('upload');
    
            $employee_no = $this->input->post('txt_emp');
            $employee_name = $this->input->post('txt_emp_name');
            $file_names = $this->input->post('file_name');
            $files = $_FILES['user_files'];
    
            if (empty($employee_no) && empty($employee_name)) {
                $this->session->set_flashdata('error', 'Either Employee No or Employee Name is required.');
                redirect(base_url('Employee_Management/View_Docs'));
            }
    
            if (empty($file_names)) {
                $this->session->set_flashdata('error', 'File names are required.');
                redirect(base_url('Employee_Management/View_Docs'));
            }
    
            // 🔍 Fetch Enroll_No from tbl_empmaster
            $this->db->select('Enroll_No');
            $this->db->from('tbl_empmaster');
    
            if (!empty($employee_no)) {
                $this->db->where('EmpNo', $employee_no);
            } else {
                $this->db->where('Emp_Full_Name', $employee_name);
            }
    
            $query = $this->db->get();
            $employee = $query->row();
    
            if (!$employee) {
                $this->session->set_flashdata('error', 'Employee not found.');
                redirect(base_url('Employee_Management/View_Docs'));
            }
    
            $enroll_no = $employee->Enroll_No;
    
            $upload_path = './assets/userDocs/';
            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0755, true);
            }
    
            for ($i = 0; $i < count($file_names); $i++) {
                if (!empty($files['name'][$i])) {
                    $_FILES['file']['name'] = $files['name'][$i];
                    $_FILES['file']['type'] = $files['type'][$i];
                    $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['file']['error'] = $files['error'][$i];
                    $_FILES['file']['size'] = $files['size'][$i];
    
                    $config['upload_path'] = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
                    $config['max_size'] = 10240;
                    $config['file_name'] = $file_names[$i] . '_' . time();
    
                    $this->upload->initialize($config);
    
                    if ($this->upload->do_upload('file')) {
                        $data = $this->upload->data();
    
                        $insert_data = [
                            'Enroll_No' => $enroll_no,
                            'file_name' => $file_names[$i],
                            'file_path' => 'assets/userDocs/' . $data['file_name'],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ];
    
                        $result = $this->Db_model->insertData('tbl_emp_docs', $insert_data);
                        if (!$result) {
                            log_message('error', 'Insert failed: ' . json_encode($insert_data));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'File upload failed: ' . $this->upload->display_errors());
                        redirect(base_url('Employee_Management/View_Docs'));
                    }
                }
            }
    
            $this->session->set_flashdata('success', 'Files uploaded successfully.');
            redirect(base_url('Employee_Management/View_Docs'));
        } else {
            show_error('Invalid request method.', 405);
        }
    }

    /*
     * Delete Document
     */
public function delete_doc($id)
{
    // Fetch the document record by ID
    $this->db->where('id', $id);
    $query = $this->db->get('tbl_emp_docs');
    $doc = $query->row();

    if ($doc) {
        $file_path = FCPATH . $doc->file_path; // Full system path to the file

        // Delete the file from storage if it exists
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the DB record
        $this->db->where('id', $id);
        $this->db->delete('tbl_emp_docs');

        $this->session->set_flashdata('success', 'Document deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Document not found.');
    }

    redirect(base_url('Employee_Management/View_Docs'));
}

    
        /*
        * Edit Document
        */
public function Edit_Doc()
{
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $this->load->library('upload');

        $doc_id = $this->input->post('doc_id');
        $new_file_name = $this->input->post('file_name');
        $files = $_FILES['user_file'];

        // Fetch existing document
        $this->db->where('id', $doc_id);
        $doc = $this->db->get('tbl_emp_docs')->row();

        if (!$doc) {
            $this->session->set_flashdata('error', 'The specified document could not be found.');
            return redirect(base_url('Employee_Management/View_Docs'));
        }

        $changes_made = false;
        $messages = [];

        // Check and update file name only if it's different
        if (!empty($new_file_name) && $new_file_name !== $doc->file_name) {
            $this->db->where('id', $doc_id);
            $this->db->update('tbl_emp_docs', ['file_name' => $new_file_name]);
            $changes_made = true;
            $messages[] = 'File name updated.';
        }

        // Handle file replacement
        if (!empty($files['name'])) {
            $upload_path = './assets/userDocs/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            // Remove old file
            if (!empty($doc->file_path) && file_exists(FCPATH . $doc->file_path)) {
                unlink(FCPATH . $doc->file_path);
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
            $config['max_size'] = 10240;
            $config['file_name'] = (!empty($new_file_name) ? $new_file_name : 'doc') . '_' . time();

            $this->upload->initialize($config);

            if ($this->upload->do_upload('user_file')) {
                $upload_data = $this->upload->data();
                $new_file_path = 'assets/userDocs/' . $upload_data['file_name'];

                // Update file path immediately
                $this->db->where('id', $doc_id);
                $this->db->update('tbl_emp_docs', ['file_path' => $new_file_path]);
                $changes_made = true;
                $messages[] = 'File replaced successfully.';
            } else {
                $this->session->set_flashdata('error', 'File upload failed: ' . $this->upload->display_errors());
                return redirect(base_url('Employee_Management/View_Docs'));
            }
        }

        // Flash messages
        if ($changes_made) {
            $this->session->set_flashdata('success', implode(' ', $messages));
        } else {
            $this->session->set_flashdata('info', 'No changes were made.');
        }

        return redirect(base_url('Employee_Management/View_Docs'));
    } else {
        $this->session->set_flashdata('error', 'Invalid request method.');
        return redirect(base_url('Employee_Management/View_Docs'));
    }
}

    /*
     * Auto Complete by Employee Name
     */



    function get_auto_emp_name() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    /*
     * Auto Complete by Employee No
     */

    function get_auto_emp_no() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }
    
}