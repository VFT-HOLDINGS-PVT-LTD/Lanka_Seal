<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class Department extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->model('Db_model', '', TRUE);
        $this->load->library("pdf_library");
    }

    /*
     * Index page in Departmrnt
     */

    public function index() {

        $data['title'] = "Departmrnt | HRM System";
        $data['data_set'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $this->load->view('Master/Department/index', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function insertDepartment() {

        $data = array(
            'Dep_Name' => $this->input->post('txt_dep_name')
        );

        $result = $this->Db_model->insertData("tbl_departments", $data);


        $this->session->set_flashdata('success_message', 'New Department has been added successfully');

        
        redirect(base_url() . 'Master/Department/');
    }

    /*
     * Get Department data
     */

    public function get_details() {
        $id = $this->input->post('id');
        $whereArray = array('Dep_ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit() {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("Dep_Name", TRUE);


        $data = array("Dep_Name" => $D_Name);
        $whereArr = array("Dep_ID" => $ID);
        $result = $this->Db_model->updateData("tbl_departments", $data, $whereArr);
        redirect(base_url() . "Master/Department");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id) {
        $table = "tbl_departments";
        $where = 'Dep_ID';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }

    /*
     * Excell | download report
     */

    public function download_department_report() {

        $data['data_set'] = $this->Db_model->getfilteredData("SELECT
                                                                Dep_ID,
                                                                Dep_Name,
                                                                Is_Delete,
                                                                Trans_time 
                                                            FROM tbl_departments");
        //var_dump($data['data_set']);die;

        //create excell sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'D') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }
        $sheet->setCellValue('A1', 'Dep_ID');
        $sheet->setCellValue('B1', 'Dep_Name');
        $sheet->setCellValue('C1', 'Is_Delete');
        $sheet->setCellValue('D1', 'Trans_time');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

         //check data exists or not
         if (!empty($data['data_set'])) {
                
            $x = 2;

            foreach ($data['data_set'] as $row) {

                //set db value to columns
                $sheet->setCellValue('A' . $x, $row->Dep_ID);
                $sheet->setCellValue('B' . $x, $row->Dep_Name);
                $sheet->setCellValue('C' . $x, $row->Is_Delete);
                $sheet->setCellValue('D' . $x, $row->Trans_time);

                $x++;
            }
            
            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="departments_table.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error_message', 'No Data Found.');
            redirect(base_url() . "Master/Department");
        }


    }

    /*
     * Excell | Upload report
     */

    function uploadDoc()
    {
        //excel file upload
        $uploadPath = 'assets/uploads/imports/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, TRUE); // FOR CREATING DIRECTORY IF ITS NOT EXIST
        }
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'csv|xlsx|xls';
        $config['max_size'] = 1000000;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_excel')) {
            $fileData = $this->upload->data();
            $data['file_name'] = $fileData['file_name'];
            // $this->db->insert('excel_file', $data);
            // $insert_id = $this->db->insert_id();
            // $_SESSION['lastid'] = $insert_id;
            return $fileData['file_name'];
        } else {
            return false;
        }
    }
    
    function upload_department_report()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            ////call this function heare
            $upload_status = $this->uploadDoc();

            if ($upload_status != false) {
                
                $inputFileName = 'assets/uploads/imports/' . $upload_status;
                $inputTileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputTileType);
                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getSheet(0);
                $count_Rows = 0;

                // print_r($sheet->getRowIterator());
                // die;

                $counter = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($counter++ == 0) continue;
                    $Dep_ID = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getValue();
                    $Dep_Name = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getValue();

                    $data = array(
                        'Dep_ID' => $Dep_ID,
                        'Dep_Name' => $Dep_Name,
                    );

                    $HasRow_department = $this->Db_model->getfilteredData("SELECT tbl_departments.Dep_ID FROM tbl_departments WHERE
                                                                tbl_departments.Dep_ID = '$Dep_ID' ");
                    if (!empty($HasRow_department[0]->Dep_ID)) {
                        // echo "Already Exist";
                        $this->db->where('Dep_ID', $HasRow_department[0]->Dep_ID);
                        $this->db->update('tbl_departments', $data);
                    } else {
                        // echo "Not Exist";
                        $this->db->insert('tbl_departments', $data);
                    }
                    
                    $count_Rows++;
                    // echo json_encode($data);
                    // echo "<br/>";
                }
                $this->session->set_flashdata('success_message', 'Upload Successfully');
                redirect(base_url() . "Master/Department/");
            } else {
                $this->session->set_flashdata('error_message', 'Upload Failed');
                redirect(base_url() . "Master/Department/");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Upload Failed');
            redirect(base_url() . "Master/Department/");
        }
    }

}
