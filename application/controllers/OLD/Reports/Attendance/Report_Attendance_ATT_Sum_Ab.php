<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report_Attendance_ATT_Sum_Ab extends CI_Controller {

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
     * Index page in Departmrnt
     */

    public function index() {

        $data['title'] = "Attendance In Out Report Summery | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');
        // $data['data_group'] = $this->Db_model->getData('id,super_gname', 'tbl_super_group');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        // $data['emp_date']= $this->session->userdata('login_user');
        // $data['emp_master'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_empmaster where EmpNo = '".$data['emp_date'][0]->EmpNo."'");
        // if ($data['emp_master'][0]->user_p_id == "1") {
        //     $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        // }else{
        //     $data['data_branch'] = $this->Db_model->getfilteredData("select * from tbl_branches inner join tbl_empmaster on tbl_empmaster.B_id = tbl_branches.B_id WHERE tbl_empmaster.user_p_id = '3' AND tbl_branches.B_id = '".$data['emp_master'][0]->B_id."' AND tbl_empmaster.EmpNo = '".$data['emp_master'][0]->EmpNo."';");
        // }
        $this->load->view('Reports/Attendance/Report_Attendance_In_Out_Sum_Ab', $data);
    }

    /*
     * Insert Departmrnt
     */

    public function Report_department() {

        $Data['data_set'] = $this->Db_model->getData('id,Dep_Name', 'tbl_departments');

        $this->load->view('Reports/Master/rpt_Departments', $Data);
    }

    public function Attendance_Report_By_Cat() {


        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        // $grop = $this->input->post("cmb_grop");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        // $branch = $this->input->post("cmb_branch");


        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;


        // Filter Data by categories
        $filter = '';
        
        if (!empty($from_date) && !empty($to_date)) {
            $filter = " AND att.AttDate BETWEEN '$from_date' AND '$to_date'";
        }

        // if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
        //     if ($filter == '') {
        //         $filter = " where  tbl_u_attendancedata.AttDate between '$from_date' and '$to_date'";
        //     } else {
        //         $filter .= " AND  tbl_u_attendancedata.AttDate between '$from_date' and '$to_date'";
        //     }
        // }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where Emp.EmpNo =$emp";
            } else {
                $filter .= " AND Emp.EmpNo =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where Emp.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
            }
        }
        // if (($this->input->post("cmb_grop"))) {
        //     if ($filter == null) {
        //         $filter = " where Emp.SupGrp_ID =$grop";
        //     } else {
        //         $filter .= " AND Emp.SupGrp_ID =$grop";
        //     }
        // }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where dsg.Des_ID  ='$desig'";
            } else {
                $filter .= " AND dsg.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where dep.Dep_id  ='$dept'";
            } else {
                $filter .= " AND dep.Dep_id  ='$dept'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where br.B_id  ='$branch'";
            } else {
                $filter .= " AND br.B_id  ='$branch'";
            }
        }



        // $data['data_set2'] = $this->Db_model->getfilteredData("SELECT 
        //                                                             ir.EmpNo,
        //                                                             Emp.Emp_Full_Name,
        //                                                             ir.FDate,
        //                                                             ir.ShiftDay,
        //                                                             ir.ShType,
        //                                                             ir.FTime,
        //                                                             ir.TTime,
        //                                                             ir.InTime,
        //                                                             ir.OutTime,
        //                                                             ir.DayStatus,
        //                                                             ir.ApprovedExH,
        //                                                              ir.LateM,
        //                                                             br.B_name
        //                                                         FROM
        //                                                             tbl_individual_roster ir
        //                                                                 LEFT JOIN
        //                                                             tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
        //                                                                 LEFT JOIN
        //                                                             tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
        //                                                                 LEFT JOIN
        //                                                             tbl_departments dep ON dep.Dep_id = Emp.Dep_id
        //                                                             inner join
        //                                                             tbl_branches br on Emp.B_id = br.B_id 

        
        //                                                             {$filter} AND STATUS='1' AND ir.DayStatus = 'AB' AND Emp.EmpNo != '00009000' order by ir.FDate,ir.InTime;");
        
        $data['data_set2'] = $this->Db_model->getfilteredData("SELECT 
                                                                Emp.EmpNo,
                                                                Emp.Emp_Full_Name,
                                                                '$from_date' AS AttDate,
                                                                NULL AS First_In,
                                                                NULL AS Last_Out
                                                            FROM `tbl_empmaster` Emp
                                                            LEFT JOIN `tbl_u_attendancedata` att 
                                                                ON Emp.Enroll_No = att.Enroll_No 
                                                                AND att.AttDate = '$from_date'
                                                            WHERE 
                                                                att.Enroll_No IS NULL
                                                                AND Emp.EmpNo != '00009000'
                                                            ORDER BY 
                                                                Emp.EmpNo;;
                                                            ");

//        var_dump($data);die;

        $this->load->view('Reports/Attendance/rpt_In_Out_Sum_Ab', $data);
    }

    function get_auto_emp_name() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    function get_auto_emp_no() {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }

    
 public function exportToExcel()
    {

        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        $emp = $this->input->post("txt_emp");
        $emp_name = $this->input->post("txt_emp_name");
        $desig = $this->input->post("cmb_desig");
        $dept = $this->input->post("cmb_dep");
        $from_date = $this->input->post("txt_from_date");
        $to_date = $this->input->post("txt_to_date");
        $branch = $this->input->post("cmb_branch");


        $data['f_date'] = $from_date;
        $data['t_date'] = $to_date;


        // Filter Data by categories
        $filter = '';

        if (($this->input->post("txt_from_date")) && ($this->input->post("txt_to_date"))) {
            if ($filter == '') {
                $filter = " where  ir.FDate between '$from_date' and '$to_date'";
            } else {
                $filter .= " AND  ir.FDate between '$from_date' and '$to_date'";
            }
        }
        if (($this->input->post("txt_emp"))) {
            if ($filter == null) {
                $filter = " where ir.EmpNo =$emp";
            } else {
                $filter .= " AND ir.EmpNo =$emp";
            }
        }

        if (($this->input->post("txt_emp_name"))) {
            if ($filter == null) {
                $filter = " where Emp.Emp_Full_Name ='$emp_name'";
            } else {
                $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
            }
        }
        if (($this->input->post("cmb_desig"))) {
            if ($filter == null) {
                $filter = " where dsg.Des_ID  ='$desig'";
            } else {
                $filter .= " AND dsg.Des_ID  ='$desig'";
            }
        }
        if (($this->input->post("cmb_dep"))) {
            if ($filter == null) {
                $filter = " where dep.Dep_id  ='$dept'";
            } else {
                $filter .= " AND dep.Dep_id  ='$dept'";
            }
        }

        if (($this->input->post("cmb_branch"))) {
            if ($filter == null) {
                $filter = " where br.B_id  ='$branch'";
            } else {
                $filter .= " AND br.B_id  ='$branch'";
            }
        }


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach(range('A','F') as $coulumID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

        }
        $sheet->setCellValue('A1','EmpNo');
        $sheet->setCellValue('B1','Emp_Full_Name');
        $sheet->setCellValue('C1','Date');
        $sheet->setCellValue('D1','IN TIME');
        $sheet->setCellValue('E1','OUT TIME');
        $sheet->setCellValue('F1','ST');
        $sheet->setCellValue('G1','LATE');
        // $sheet->setCellValue('H1','Working (H:M:S)');


        $data_set_query = $this->Db_model->getfilteredData1("SELECT 
        ir.EmpNo,
        Emp.Emp_Full_Name,
        ir.FDate,
        ir.ShiftDay,
        ir.ShType,
        ir.FTime,
        ir.TTime,
        ir.InTime,
        ir.OutTime,
        ir.DayStatus,
        ir.ApprovedExH,
        ir.NetLateM,
        br.B_name
    FROM
        tbl_individual_roster ir
        LEFT JOIN tbl_empmaster Emp ON Emp.EmpNo = ir.EmpNo
        LEFT JOIN tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
        LEFT JOIN tbl_departments dep ON dep.Dep_id = Emp.Dep_id
        INNER JOIN tbl_branches br ON Emp.B_id = br.B_id
        {$filter} GROUP BY ir.FDate, Emp.EmpNo ORDER BY Emp.Emp_Full_Name, ir.FDate;");
    
    $data_set = $data_set_query->result_array();
        $x=2; //start from row 2
        foreach($data_set as $row)

        // $outTime = strtotime($row['OutTime']);
        // $inTime = strtotime($row['InTime']);

        // // Calculate the difference in seconds
        // $timeDifferenceInSeconds = $outTime - $inTime;

        // // Convert the time difference back to HH:MM:SS format
        // $timeDifferenceFormatted = gmdate("H:i:s", $timeDifferenceInSeconds);
        {
            $sheet->setCellValue('A'.$x, $row['EmpNo']);
            $sheet->setCellValue('B'.$x, $row['Emp_Full_Name']);
            $sheet->setCellValue('C'.$x, $row['FDate']);
            $sheet->setCellValue('D'.$x, $row['InTime']);
            $sheet->setCellValue('E'.$x, $row['OutTime']);
            $sheet->setCellValue('F'.$x, $row['ShType']);
            $sheet->setCellValue('G'.$x, $row['NetLateM']);
            // $sheet->setCellValue('H'.$x, $timeDifferenceFormatted);

            $x++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName='users_details_export2022.xlsx';
        //$writer->save($fileName);  //this is for save in folder


        /* for force download */
        header('Content-Type: appliction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        $writer->save('php://output');
    }

}
