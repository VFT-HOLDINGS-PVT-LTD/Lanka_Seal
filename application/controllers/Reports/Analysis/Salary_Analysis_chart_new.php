<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_Analysis_chart_new extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('login_user'))) {
            redirect(base_url() . "");
        }
        /*
         * Load Database model
         */
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Salary_Analysis_chart_new
     */

    public function index() {
        $query = $this->db->query("SELECT 
                                    YEAR AS year,
                                    MONTH AS month, 
                                    SUM(D_Salary) AS total_salary 
                                FROM tbl_salary 
                                GROUP BY YEAR, MONTH 
                                ORDER BY YEAR ASC, MONTH ASC
                            ");

        $raw_data = [];
        foreach ($query->result() as $row) {
            $raw_data[$row->year][(int)$row->month] = (float)$row->total_salary;
        }

        $final_data = [];
        foreach ($raw_data as $year => $months_data) {
            for ($m = 1; $m <= 12; $m++) {
                $final_data[$year][] = isset($months_data[$m]) ? $months_data[$m] : 0;
            }
        }

        $data['title'] = "Salary Analysis Chart | HRM System";
        $data['chart_data'] = json_encode($final_data);
        $data['years'] = json_encode(array_keys($final_data));

        $this->load->view('Reports/Analysis/Salary_Analysis_chart_new', $data);
    }
    

    /*
     * Insert Departmrnt
     */

    // public function Report_department() {

    //     $Data['data_set'] = $this->Db_model->getfilteredData("SELECT 
    //                                                                 COUNT(EmpNo) AS EmpCount, tbl_departments.Dep_ID, tbl_departments.Dep_Name
    //                                                             FROM
    //                                                                 tbl_empmaster
    //                                                                     INNER JOIN
    //                                                                 tbl_departments ON tbl_empmaster.Dep_ID = tbl_departments.Dep_ID
    //                                                             GROUP BY tbl_departments.Dep_ID");
        
    //     $this->load->view('Reports/Master/rpt_Departments', $Data);
    // }
    
    
    /*
     * Get Department data
     */

//     public function get_details() {
//         $id = $this->input->post('id');

// //                    echo "OkM " . $id;
        
//         $whereArray = array('ID' => $id);

//         $this->Db_model->setWhere($whereArray);
//         $dataObject = $this->Db_model->getData('ID,Dep_Name', 'tbl_departments');

//         $array = (array) $dataObject;
//         echo json_encode($array);
//     }
    
    
//     public function edit() {
//         $ID = $this->input->post("id", TRUE);
//         $D_Name = $this->input->post("Dep_Name", TRUE);
        

//         $data = array("Dep_Name" => $D_Name);
//         $whereArr = array("Dep_ID" => $ID);
//         $result = $this->Db_model->updateData("tbl_departments", $data, $whereArr);
//         redirect(base_url() . "Master/Department");
//     }
    
    
//     public function ajax_delete($id)
// 	{
//                 $table = "tbl_departments";
//                 $where ='id';
// 		$this->Db_model->delete_by_id($id,$where,$table);
// 		echo json_encode(array("status" => TRUE));
// 	}

}