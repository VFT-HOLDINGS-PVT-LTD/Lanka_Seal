<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Payee_Tax_Report extends CI_Controller
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
        $this->load->library("pdf_library");
        $this->load->model('Db_model', '', TRUE);
    }

    /*
     * Index page
     */

    public function index()
    {

        $data['title'] = "Deduction Report | HRM System";
        $data['data_dep'] = $this->Db_model->getData('Dep_ID,Dep_Name', 'tbl_departments');
        $data['data_desig'] = $this->Db_model->getData('Des_ID,Desig_Name', 'tbl_designations');
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $data['data_branch'] = $this->Db_model->getData('B_id,B_name', 'tbl_branches');
        $data['data_group'] = $this->Db_model->getData('Grp_ID,EmpGroupName', 'tbl_emp_group');
        $this->load->view('Reports/Payroll/Payee_Tax_Report', $data);
    }

    public function create_payee_tax_report()
    {
        //get company profile
        $data['data_cmp'] = $this->Db_model->getData('Cmp_ID,Company_Name', 'tbl_companyprofile');

        //get inputa data
        $emp_no = $this->input->post("emp_no");
        $emp_name = $this->input->post("emp_name");
        $designation = $this->input->post("designation");
        $department = $this->input->post("department");
        $branch = $this->input->post("branch");
        $year = $this->input->post("year");
        $month = $this->input->post("month");
        $group = $this->input->post("group");

        //create excell sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach (range('A', 'Q') as $columID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columID)->setAutoSize(true);
        }

        //List of employee statement
        $sheet->mergeCells('A3:Q3');
        $sheet->setCellValue('A3', 'List of Employees whose Remuneration below Rs. 100,000.00 per Month');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A3')->getFont()->setUnderline(true);

        //merge celles A9 and A10
        $sheet->mergeCells('A9:A10');

        //Add A to Q letters 
        foreach (range('A', 'Q') as $col) {
            $sheet->mergeCells("{$col}6:{$col}7");
            $sheet->setCellValue("{$col}6", $col);
            $sheet->getStyle("{$col}6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("{$col}6")->getFont()->setBold(true);
            $sheet->getStyle("{$col}6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("{$col}6:{$col}7")->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => Color::COLOR_BLACK],],],]);
        }

        //add column title in English 
        $sheet->setCellValue('A8', 'Serial No. of PAYE Pay Sheet');
        $sheet->setCellValue('B8', 'Serial No. of Pay Sheet');
        $sheet->setCellValue('C8', 'Name of Employee with Initials');
        $sheet->setCellValue('D8', 'Designation');
        $sheet->setCellValue('E8', 'Employment From Date');
        $sheet->setCellValue('F8', 'Employment to Date');
        $sheet->setCellValue('G8', 'Cash Payment');
        $sheet->setCellValue('H8', 'Non-Cash Benefits');
        $sheet->setCellValue('I8', 'Total Remuneration');
        $sheet->setCellValue('J8', 'Total Tax Exempt/Excluded Income');
        $sheet->setCellValue('K8', 'Tax deducted under primary Employment');
        $sheet->setCellValue('L8', 'Tax deducted under Secondary Employment');
        $sheet->setCellValue('M8', 'Total Tax Deducted');
        $sheet->setCellValue('N8', 'Employee NIC No');
        $sheet->setCellValue('O8', 'Employee Passport No.');
        $sheet->setCellValue('P8', 'Employee TIN');
        $sheet->setCellValue('Q8', 'Employee Address');
        $sheet->getStyle('A8:Q8')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A8:Q8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:Q8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:Q8')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);


        //add column names in sinhala
        $sheet->setCellValue('B9', 'වැටුප් පත්‍රයේ අනුක්‍රමික අංකය');
        $sheet->setCellValue('C9', 'මුලකුරු සමඟ සේවා නියුක්තිකයාගේ නම');
        $sheet->setCellValue('D9', 'තනතුර');
        $sheet->setCellValue('E9', 'සේවා නියුක්තිය..........දින සිට');
        $sheet->setCellValue('F9', 'සේවා නියුක්තිය..........දින දක්වා');
        $sheet->setCellValue('G9', 'මුදලින් ගෙවිම්');
        $sheet->setCellValue('H9', 'මුල්‍යමය නොවන ප්‍රතිලාභ');
        $sheet->setCellValue('I9', 'මුළු පාරිශ්‍රමිකය');
        $sheet->setCellValue('J9', 'බද්දෙන් නිදහස් කළ/බැහැර කළ මුළු ආදායම');
        $sheet->setCellValue('K9', 'ප්‍රාථමික සේවා නියුක්තිය යටතේ අඩු කළ බද්ද');
        $sheet->setCellValue('L9', 'ද්විතියික සේවා නියුක්තිය යටතේ අඩු කළ බද්ද');
        $sheet->setCellValue('M9', 'අඩු කළ මුළු බදු ප්‍රමාණය');
        $sheet->setCellValue('N9', 'සේවා නියුක්තිකයාගේ ජාතික හැදුනුම් පත්‍ අංකය');
        $sheet->setCellValue('O9', 'සේවා නියුක්තිකයාගේ විදේශ ගමන් බලපත්‍ර අංකය');
        $sheet->setCellValue('P9', 'සේවා නියුක්තිකයාගේ බදු ගෙවන්නා හදුනා ගැනීමේ අංකය');
        $sheet->setCellValue('Q9', 'ලිපිනය');
        $sheet->getStyle('B9:Q9')->getFont()->setSize(10);
        $sheet->getStyle('A9:Q9')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A9:Q9')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        //add column names in tamil
        $sheet->setCellValue('B10', 'சம்பளப் பட்டியலின் தொடர் இலக்கம்');
        $sheet->setCellValue('C10', 'முதலெழுத்துக்களுடன் ஊழியரின் பெயர்');
        $sheet->setCellValue('D10', 'பதவி');
        $sheet->setCellValue('E10', 'தொழில்........ ஆந் திகதியில் இருந்து');
        $sheet->setCellValue('F10', 'தொழில்........ ஆந் திகதி வரை');
        $sheet->setCellValue('G10', 'பணக் கொடுப்பனவு');
        $sheet->setCellValue('H10', 'பணமல்லாத கொடுப்பனவுகள்');
        $sheet->setCellValue('I10', 'மொத்த  உழைப்பூதியம்');
        $sheet->setCellValue('J10', 'மொத்த வரி விலக்களிக்கப்பட்ட/நீக்கப்பட்ட வருமானம்');
        $sheet->setCellValue('K10', 'ஆரம்பநிலை தொழிலின் கீழ் கழிக்கப்பட்ட வரி');
        $sheet->setCellValue('L10', 'இரண்டாம்நிலை தொழிலின் கீழ் கழிக்கப்பட்ட வரி');
        $sheet->setCellValue('M10', 'கழிக்கப்பட்ட மொத்த வரித் தொகை');
        $sheet->setCellValue('N10', 'ஊழியரின் தேசிய அடையாள அட்டை இல');
        $sheet->setCellValue('O10', 'ஊழியரின் கடவுச்சீட்டு இல');
        $sheet->setCellValue('P10', 'ஊழியரின் வரி செலுத்துநர் அடையாள இலக்கம்');
        $sheet->setCellValue('Q10', 'முகவரி');
        $sheet->getStyle('B10:Q10')->getFont()->setSize(8);
        $sheet->getStyle('A10:Q10')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // get data based on month or year
        if (!empty($year) && (empty($month))) {

            $filter = '';

            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }
            if (($this->input->post("year"))) {
                if ($filter == '') {
                    $filter = " where  sa.Year =$year ";
                } else {
                    $filter .= " AND  sa.Year =$year ";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where sa.EmpNo =$emp_no";
                } else {
                    $filter .= " AND sa.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }

            $current_date = date("Y-m-d");
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    sa.ID,
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    dsg.Desig_Name,
                                                                    Emp.ApointDate,
                                                                    SUM(sa.Payee_amount) as cash_amount,
                                                                    '$current_date' as 'current_date',
                                                                    Emp.NIC,
                                                                    Emp.Address
                                                                FROM
                                                                    tbl_salary sa
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = sa.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.Dep_id
                                                                LEFT JOIN
	                                                                tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID    
                                                                {$filter} AND sa.Payee_amount != 0
                                                                GROUP BY Emp.EmpNo ORDER BY Emp.EmpNo");
            //check data exists or not
            if (!empty($data['data_set'])) {

                $x = 11;

                foreach ($data['data_set'] as $row) {

                    //set db value to columns
                    $sheet->setCellValue('A' . $x, '');
                    $sheet->setCellValue('B' . $x, $row->ID);
                    $sheet->setCellValue('C' . $x, $row->Emp_Full_Name);
                    $sheet->setCellValue('D' . $x, $row->Desig_Name);
                    $sheet->setCellValue('E' . $x, $row->ApointDate);
                    $sheet->setCellValue('F' . $x, $row->current_date);
                    $sheet->setCellValue('G' . $x,  number_format($row->cash_amount, 0, '.', ','));
                    $sheet->setCellValue('H' . $x, ' ');
                    $sheet->setCellValue('I' . $x, ' ');
                    $sheet->setCellValue('J' . $x, ' ');
                    $sheet->setCellValue('K' . $x, ' ');
                    $sheet->setCellValue('L' . $x, ' ');
                    $sheet->setCellValue('M' . $x, ' ');
                    $sheet->setCellValueExplicit('N' . $x, $row->NIC , DataType::TYPE_STRING);
                    $sheet->setCellValue('O' . $x, ' ');
                    $sheet->setCellValue('P' . $x, ' ');
                    $sheet->setCellValue('Q' . $x, $row->Address);

                    $sheet->getStyle('A' . $x . ':Q' . $x)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => Color::COLOR_BLACK],
                            ],
                        ],
                    ]);

                    $x++;
                }



                if (ob_get_contents()) ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="paytaxreport.xlsx"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                $this->session->set_flashdata('error_message', 'No Data Found.');
                redirect(base_url() . "Reports/Payroll/Payee_Tax_Report");
            }
        } else {
            $filter = '';

            $first_month = 1;
            $middle_month = 6;
            $last_month = 12;

            if (!empty($month)) {
                if ($month == 13) {
                    $filter = " where sa.Month BETWEEN $first_month AND $middle_month ";
                } elseif ($month == 14) {
                    $filter = " where sa.Month BETWEEN " . ($middle_month + 1) . " AND $last_month";
                } else {
                    $filter = " where sa.Month = $month";
                }
            }
            if (($this->input->post("emp_no"))) {
                if ($filter == null) {
                    $filter = " where sa.EmpNo =$emp_no";
                } else {
                    $filter .= " AND sa.EmpNo =$emp_no";
                }
            }
            if (($this->input->post("emp_name"))) {
                if ($filter == null) {
                    $filter = " where Emp.Emp_Full_Name ='$emp_name'";
                } else {
                    $filter .= " AND Emp.Emp_Full_Name ='$emp_name'";
                }
            }
            if (($this->input->post("designation"))) {
                if ($filter == null) {
                    $filter = " where dsg.Des_ID  ='$designation'";
                } else {
                    $filter .= " AND dsg.Des_ID  ='$designation'";
                }
            }
            if (($this->input->post("department"))) {
                if ($filter == null) {
                    $filter = " where dep.Dep_id  ='$department'";
                } else {
                    $filter .= " AND dep.Dep_id  ='$department'";
                }
            }
            if (($this->input->post("branch"))) {
                if ($filter == null) {
                    $filter = " where bra.B_id  ='$branch'";
                } else {
                    $filter .= " AND bra.B_id  ='$branch'";
                }
            }
            if (($this->input->post("group"))) {
                if ($filter == null) {
                    $filter = " where gr.Grp_ID  ='$group'";
                } else {
                    $filter .= " AND gr.Grp_ID  ='$group'";
                }
            }

            $current_date = date("Y-m-d");
            $data['data_set'] = $this->Db_model->getfilteredData("SELECT 
                                                                    sa.ID,
                                                                    Emp.EmpNo,
                                                                    Emp.Emp_Full_Name,
                                                                    dsg.Desig_Name,
                                                                    Emp.ApointDate,
                                                                    SUM(sa.Payee_amount) as cash_amount,
                                                                    '$current_date' as 'current_date',
                                                                    Emp.NIC,
                                                                    Emp.Address

                                                                FROM
                                                                    tbl_salary sa
                                                                LEFT JOIN
                                                                    tbl_empmaster Emp ON Emp.EmpNo = sa.EmpNo
                                                                LEFT JOIN
                                                                    tbl_designations dsg ON dsg.Des_ID = Emp.Des_ID
                                                                LEFT JOIN
                                                                    tbl_departments dep ON dep.Dep_id = Emp.Dep_id
                                                                LEFT JOIN
                                                                    tbl_branches bra ON bra.B_id = Emp.Dep_id
                                                                LEFT JOIN
	                                                                tbl_emp_group gr ON gr.Grp_ID = Emp.Grp_ID    
                                                                {$filter} AND sa.Payee_amount != 0

                                                                GROUP BY Emp.EmpNo ORDER BY Emp.EmpNo");
            //check data exists or not
            if (!empty($data['data_set'])) {

                $x = 11;
                foreach ($data['data_set'] as $row) {

                    //set db value to columns
                    $sheet->setCellValue('A' . $x, '');
                    $sheet->setCellValue('B' . $x, $row->ID);
                    $sheet->setCellValue('C' . $x, $row->Emp_Full_Name);
                    $sheet->setCellValue('D' . $x, $row->Desig_Name);
                    $sheet->setCellValue('E' . $x, $row->ApointDate);
                    $sheet->setCellValue('F' . $x, $row->current_date);
                    $sheet->setCellValue('G' . $x, number_format($row->cash_amount, 0, '.', ','));
                    $sheet->setCellValue('H' . $x, '');
                    $sheet->setCellValue('I' . $x, '');
                    $sheet->setCellValue('J' . $x, '');
                    $sheet->setCellValue('K' . $x, '');
                    $sheet->setCellValue('L' . $x, '');
                    $sheet->setCellValue('M' . $x, '');
                    $sheet->setCellValueExplicit('N' . $x, $row->NIC , DataType::TYPE_STRING);
                    $sheet->setCellValue('O' . $x, '');
                    $sheet->setCellValue('P' . $x, '');
                    $sheet->setCellValue('Q' . $x, $row->Address);

                    $x++;
                }

                if (ob_get_contents()) ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="paytaxreport.xlsx"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                $this->session->set_flashdata('error_message', 'No Data Found.');
                redirect(base_url() . "Reports/Payroll/Payee_Tax_Report");
            }
        }
    }

    //Filter Data by name jquery part
    function get_auto_emp_name()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_name($q);
        }
    }

    function get_auto_emp_no()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->Db_model->get_auto_emp_no($q);
        }
    }
}
