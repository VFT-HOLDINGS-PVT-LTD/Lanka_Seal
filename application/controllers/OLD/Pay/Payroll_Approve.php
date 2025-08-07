<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_Approve extends CI_Controller
{

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
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Payroll Edit | HRM System";
        $currentMonth = date('m');
        // date('M');
        $data['data_set'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary INNER JOIN tbl_empmaster ON tbl_salary.EmpNo = tbl_empmaster.EmpNo WHERE tbl_salary.Month = '" . $currentMonth . "' AND tbl_empmaster.EmpNo != '00009000'");
        $currentMonth2 = date('F');
        $data['months'] = $currentMonth2;
        $this->load->view('Payroll/Payroll_Approve/index', $data);
    }



    //  public function edit_data() {  
    //     // Decode the JSON input sent via POST
    //     $postData = json_decode(file_get_contents('php://input'), true);

    //     // Check if data is received
    //     if ($postData) {
    //         // Log the received data (for debugging purposes)
    //         log_message('info', 'Row data received: ' . print_r($postData, true));

    //         // Perform the desired update operation here (e.g., database update)
    //         // Example: Assuming $postData['row'] contains the updated row data
    //         $rowData = $postData['row'];

    //         // Sample response after processing
    //         $response = [
    //             'status' => 'success',
    //             'message' => 'Row updated successfully',
    //             'data' => $rowData // Echo back the received data for verification
    //         ];
    //     } else {
    //         // Handle case when no data is received
    //         $response = [
    //             'status' => 'error',
    //             'message' => 'No data received'
    //         ];
    //     }

    //     // Send response back as JSON
    //     echo json_encode($response);
    // }

    // public function edit_data() {
    //     header('Content-Type: application/json'); // Ensure JSON response

    //     $postData = json_decode(file_get_contents('php://input'), true);

    //     if ($postData) {
    //         // Process the data as before
    //         $rowData = $postData['row'];
    //         $tableName = 'tbl_salary';

    //         $dataArray = [
    //             'Emp_Full_Name'     => $rowData[1],
    //             'Basic_sal'         => $rowData[2],
    //             'Fixed_Allowance'   => $rowData[3],
    //             'Br_pay'            => $rowData[4],
    //             'Incentive'         => $rowData[5],
    //             'No_Pay_days'       => $rowData[6],
    //             'no_pay_deduction'  => $rowData[7],
    //             'Gross_sal'         => $rowData[8],
    //         ];

    //         $whereArray = ['EmpNo' => $rowData[0]];

    //         $this->load->model('Db_model'); // Ensure model is loaded
    //         $result = $this->Db_model->updateData($tableName, $dataArray, $whereArray);

    //         if ($result) {
    //             echo json_encode([
    //                 'status' => 'success',
    //                 'message' => 'Row updated successfully',
    //                 'updatedData' => $dataArray
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'status' => 'error',
    //                 'message' => 'Failed to update the row'
    //             ]);
    //         }
    //     } else {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'No data received'
    //         ]);
    //     }
    // }

    public function reject_data()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Get the JSON input
            $postData = file_get_contents('php://input');
        
            // Decode the JSON string into a PHP array
            $data = json_decode($postData, true);
        
            $ID = $data['rowData'][0];
        
            // Check if a row with the given ID exists in tbl_salary_edited
            $edit_salary = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary_edited WHERE tbl_salary_edited.Salary_t_id = '$ID'");
        
            if (!empty($edit_salary)) {
                // Prepare data array for updating tbl_salary
                $dataArray = array(
                    'Edited' => 0,
                    'Approved' => 1,
                );
        
                // Update the existing row in tbl_salary
                $whereArray = array("ID" => $ID);
                $result = $this->Db_model->updateData("tbl_salary", $dataArray, $whereArray);
                
                $dataArray_1 = array(
                    'Edited' => 0,
                );
                $whereArray_1 = array("ID" => $ID);
                $result = $this->Db_model->updateData("tbl_salary_edited", $dataArray_1, $whereArray_1);
        
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Row updated successfully',
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update row'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No matching record found in tbl_salary_edited'
                ]);
            }
        } else {
            // Respond with error for non-POST requests
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
    }
    public function edit_data()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Get the JSON input
            $postData = file_get_contents('php://input');
        
            // Decode the JSON string into a PHP array
            $data = json_decode($postData, true);
        
            $ID = $data['rowData'][0];
        
            // Check if a row with the given ID exists in tbl_salary_edited
            $edit_salary = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary_edited WHERE tbl_salary_edited.ID = '$ID'");
        


            // echo json_encode($edit_salary[0]->Salary_t_id);
            // echo $ID;

            if (!empty($edit_salary)) {
                $update_sl_id = $edit_salary[0]->Salary_t_id;
                // Prepare data array for updating tbl_salary
                $dataArray = array(
                    'Basic_sal' => $edit_salary[0]->Basic_sal,
                    'Total_F_Epf' => $edit_salary[0]->Total_F_Epf,
                    'Allowance_1' => $edit_salary[0]->Allowance_1,
                    'Allowance_2' => $edit_salary[0]->Allowance_2,
                    'Allowance_3' => $edit_salary[0]->Allowance_3,
                    'Allowance_4' => $edit_salary[0]->Allowance_4,
                    'Gross_pay' => $edit_salary[0]->Gross_pay,
                    'Late_deduction' => $edit_salary[0]->Late_deduction,
                    'Ed_deduction' => $edit_salary[0]->Ed_deduction,
                    'Deduct_1' => $edit_salary[0]->Deduct_1,
                    'Deduct_2' => $edit_salary[0]->Deduct_2,
                    'Deduct_3' => $edit_salary[0]->Deduct_3,
                    'Deduct_4' => $edit_salary[0]->Deduct_4,
                    'no_pay_deduction' => $edit_salary[0]->no_pay_deduction,
                    'tot_deduction' => $edit_salary[0]->tot_deduction,
                    'Net_salary' => $edit_salary[0]->Net_salary,
                    'Edited' => 1,
                    'Approved' => 1,
                );
        
                // Update the existing row in tbl_salary
                $whereArray = array("ID" => $update_sl_id);
                $result = $this->Db_model->updateData("tbl_salary", $dataArray, $whereArray);
                
                $dataArray_1 = array(
                    'Edited' => 0,
                );
                $whereArray_1 = array("ID" => $ID);
                $result = $this->Db_model->updateData("tbl_salary_edited", $dataArray_1, $whereArray_1);
        
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Row updated successfully',
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update row'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No matching record found in tbl_salary_edited'
                ]);
            }
        } else {
            // Respond with error for non-POST requests
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
    }

    // public function Payroll_Search() {
    //       // Decode the incoming JSON input
    // $input = json_decode(file_get_contents("php://input"), true);

    // // Check if the expected data is present
    // $empNo = isset($input['txt_emp']) ? $input['txt_emp'] : '';
    // $empName = isset($input['txt_emp_name']) ? $input['txt_emp_name'] : '';
    // $month = isset($input['cmb_month']) ? $input['cmb_month'] : '';

    // // Create an example response
    // $response = [
    //     'empNo' => $empNo,
    //     'empName' => $empName,
    //     'month' => $month,
    //     'status' => 'success', // Example status
    // ];

    // // Set the content type to application/json
    // header('Content-Type: application/json');

    // // Send the response as JSON
    // echo json_encode($response);
    //     // $data = '';
    //     // Get JSON input
    //     // $input = json_decode(file_get_contents("php://input"), true);
    //     // $inputdata = json_decode($input, true);

    //     // $empNo = $inputdata['empNo'];
    //     // $empName = $inputdata['empName'];
    //     // $data = $inputdata['month'];
    //     // $monthNumber = date('n', strtotime($month)); // Converts month name to its number

    //     // $month = $data;

    //     // if ($data) {
    //     //     echo json_encode($data); // Send JSON response
    //     // } else {
    //     //     echo json_encode(["status" => "No data found"]);
    //     // }
    //     // $filter = '';

    //     // if (($this->input->post("cmb_month")) && ($this->input->post("cmb_year"))) {
    //     //     if ($filter == '') {
    //     //         $filter = "where tbl_salary.Month = '$month'";
    //     //     } else {
    //     //         $filter .= " AND  tbl_salary.Month = '$month'";
    //     //     }
    //     // }
    //     // if (($this->input->post("txt_emp"))) {
    //     //     if ($filter == null) {
    //     //         $filter = " where ir.EmpNo =$empNo";
    //     //     } else {
    //     //         $filter .= " AND ir.EmpNo =$empNo";
    //     //     }
    //     // }

    //     // if (($this->input->post("txt_emp_name"))) {
    //     //     if ($filter == null) {
    //     //         $filter = " where Emp.Emp_Full_Name ='$empName'";
    //     //     } else {
    //     //         $filter .= " AND Emp.Emp_Full_Name ='$empName'";
    //     //     }
    //     // }

    //     // // Call your model to get filtered results
    //     // // $data = $this->Payroll_Model->searchPayroll($empNo, $empName, $month);
    //     // $data = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary INNER JOIN tbl_empmaster ON tbl_salary.EmpNo = tbl_empmaster.EmpNo {$filter} AND tbl_empmaster.EmpNo != '00009000' ");


    //     // // Return data as JSON
    //     // echo json_encode($data);
    // }


    /*
     * Get data
     */
    public function Payroll_Search()
    {
        // echo json_encode("Payroll_Search function called");
        // Decode JSON input
        $input = json_decode(file_get_contents("php://input"), true);

        // Retrieve data from the decoded input
        $empNo = isset($input['empNo']) ? $input['empNo'] : '';
        $empName = isset($input['empName']) ? $input['empName'] : '';
        $month = isset($input['month']) ? $input['month'] : '';

        // Initialize filter for SQL query
        $filter = '';

        // Build filter query based on provided values
        if (!empty($month)) {
            $filter .= " WHERE tbl_salary_edited.Month = '$month'";
        }
        if (!empty($empNo)) {
            $filter .= (empty($filter) ? " WHERE " : " AND ") . "tbl_salary_edited.EmpNo = '$empNo'";
        }
        if (!empty($empName)) {
            $filter .= (empty($filter) ? " WHERE " : " AND ") . "tbl_empmaster.Emp_Full_Name LIKE '%$empName%'";
        }

        // SQL query to fetch filtered data
        $sql = "SELECT * FROM tbl_salary_edited 
                INNER JOIN tbl_empmaster 
                ON tbl_salary_edited.EmpNo = tbl_empmaster.EmpNo 
                $filter AND tbl_empmaster.EmpNo != '00009000' AND tbl_salary_edited.Edited = '1' ";

        // Call model to execute the query and get filtered data
        $data = $this->Db_model->getfilteredData($sql);
        // echo json_encode($sql);
        // Return data as JSON response
        if ($data) {
            echo json_encode($data);  // Send the data as JSON
        } else {
            echo json_encode(["status" => "No data found"]);  // Send a "No data found" status if no results are returned
        }
    }

    public function get_details()
    {
        $id = $this->input->post('id');

        //                    echo "OkM " . $id;

        $whereArray = array('ID' => $id);

        $this->Db_model->setWhere($whereArray);
        $dataObject = $this->Db_model->getData('ID,Desig_Name,Desig_Order', 'tbl_designations');

        $array = (array) $dataObject;
        echo json_encode($array);
    }

    /*
     * Edit Data
     */

    public function edit()
    {
        $ID = $this->input->post("id", TRUE);
        $D_Name = $this->input->post("Desig_Name", TRUE);
        $D_Order = $this->input->post("Desig_Order", TRUE);

        $data = array("Desig_Name" => $D_Name, 'Desig_Order' => $D_Order);
        $whereArr = array("id" => $ID);
        $result = $this->Db_model->updateData("tbl_designations", $data, $whereArr);
        redirect(base_url() . "Master/Designation");
    }

    /*
     * Delete Data
     */

    public function ajax_delete($id)
    {
        $table = "tbl_designations";
        $where = 'id';
        $this->Db_model->delete_by_id($id, $where, $table);
        echo json_encode(array("status" => TRUE));
    }
}