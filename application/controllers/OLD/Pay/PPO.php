<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_Process extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login_user')) {
            redirect(base_url());
        }
        $this->load->model('Db_model', 'Db_model', TRUE);
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = "Payroll Process | HRM SYSTEM";
        $data['data_emp'] = $this->Db_model->getData('EmpNo, Emp_Full_Name', 'tbl_empmaster');
        $this->load->view('Payroll/Payroll_process/index', $data);
    }

    public function emp_payroll_process()
    {
        date_default_timezone_set('Asia/Colombo');

        $month = (int) $this->input->post('cmb_month');
        $year = (int) $this->input->post('cmb_year');

        $employees = $this->Db_model->getfilteredData("
            SELECT EmpNo, Dep_ID, Basic_Salary, BR1, BR2, is_nopay_calc, Is_EPF, Emp_Full_Name,Incentive
            FROM tbl_empmaster 
            WHERE Status = 1 AND Active_process = 1
        ");

        $payee_slabs = $this->Db_model->getfilteredData("SELECT * FROM tbl_payee ORDER BY id ASC");

        $finalPayroll = [];

        foreach ($employees as $emp) {

            $EmpNo = (int) $emp->EmpNo;
            $Dep_ID = (int) $emp->Dep_ID;

            // Get Department Name
            $dep = $this->Db_model->getfilteredData("SELECT Dep_Name FROM tbl_departments WHERE Dep_ID = $Dep_ID");
            $DepName = isset($dep[0]->Dep_Name) ? $dep[0]->Dep_Name : 'Unknown';

            // Get NoPay
            $nopay = $this->Db_model->getfilteredData("
                SELECT SUM(nopay) AS nopay 
                FROM tbl_individual_roster 
                WHERE EmpNo = $EmpNo 
                  AND EXTRACT(MONTH FROM FDate) = $month 
                  AND EXTRACT(YEAR FROM FDate) = $year 
                  AND ShType = 'DU'
            ");
            $NopayDays = isset($nopay[0]->nopay) ? (float) $nopay[0]->nopay : 0;

            // Get Salary Advance
            $advance = $this->Db_model->getfilteredData("
                SELECT Amount 
                FROM tbl_salary_advance 
                WHERE Is_Approve = 1 AND EmpNo = $EmpNo 
                  AND month = $month AND year = $year
            ");
            $AdvanceAmount = isset($advance[0]->Amount) ? (float) $advance[0]->Amount : 0;

            //Get Variable Allowances
            // $variable_allowances = $this->Db_model->getfilteredData("
            //     SELECT *
            //     FROM tbl_varialble_allowance 
            //     WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
            // ");
            // $VariableAllowancesAmount = isset($variable_allowances[0]->Amount) ? (float) $variable_allowances[0]->Amount : 0;

            $variable_allowances = $this->Db_model->getfilteredData("
                SELECT *
                FROM tbl_varialble_allowance 
                WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
            ");

            $VariableAllowancesAmount = 0;
            if (!empty($variable_allowances)) {
                foreach ($variable_allowances as $allowance) {
                    $VariableAllowancesAmount += (float) $allowance->Amount;
                }
            }

            // Get Fixed Allowances
            $fixed_allowances = $this->Db_model->getfilteredData("
                SELECT * 
                FROM tbl_fixed_allowance 
                WHERE EmpNo = $EmpNo
            ");
            $FixedAllowancesAmount = 0;
            if (!empty($fixed_allowances)) {
                foreach ($fixed_allowances as $allowance) {
                    $FixedAllowancesAmount += (float) $allowance->Amount;
                }
            }

            // Get Variable Deductions
            $variable_deductions = $this->Db_model->getfilteredData("
                SELECT * 
                FROM tbl_variable_deduction 
                WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
            ");
            $VariableDeductionAmount = 0;
            if (!empty($variable_deductions)) {
                foreach ($variable_deductions as $deduction) {
                    $VariableDeductionAmount += (float) $deduction->Amount;
                }
            }

            // Get Fixed Deductions
            $fixed_deductions = $this->Db_model->getfilteredData("
                SELECT * 
                FROM tbl_fixed_deduction 
                WHERE EmpNo = $EmpNo
            ");
            $FixedDeductionAmount = 0;
            if (!empty($fixed_deductions)) {
                foreach ($fixed_deductions as $deduction) {
                    $FixedDeductionAmount += (float) $deduction->Amount;
                }
            }

            // Perform Payroll Calculations
            $salaryData = $this->calculate_salary(
                (float) $emp->Basic_Salary,
                (float) $emp->BR1,
                (float) $emp->BR2,
                (float) $emp->Incentive,
                $emp->is_nopay_calc,
                $NopayDays,
                $AdvanceAmount,
                $payee_slabs,
                $VariableAllowancesAmount,
                $FixedAllowancesAmount,
                $VariableDeductionAmount,
                $FixedDeductionAmount
            );

            $salaryData['EmpNo'] = $EmpNo;
            $salaryData['month'] = $month;
            $salaryData['year'] = $year;

            // Remove VariableAllowances and FixedAllowances from the data to be inserted/updated
            unset($salaryData['Allowances'], $salaryData['Deductions']);

            // Check if already exists
            $HasRow = $this->Db_model->getfilteredData("
                SELECT COUNT(*) AS HasRow 
                FROM tbl_salary 
                WHERE EmpNo = $EmpNo AND month = $month AND year = $year
            ");

            if (!empty($HasRow[0]->HasRow)) {
                $this->Db_model->updateData('tbl_salary', $salaryData, [
                    'EmpNo' => $EmpNo,
                    'month' => $month,
                    'year' => $year
                ]);
            } else {
                try {
                    $this->Db_model->insertData('tbl_salary', $salaryData);
                } catch (Exception $e) {
                    log_message('error', 'Payroll insert failed: ' . $e->getMessage());
                }
            }

            // Push into final array
            $finalPayroll[] = [
                'EMP_NO' => $EmpNo,
                'DEP_NAME' => $DepName,
                'NAME' => $emp->Emp_Full_Name,
                'BASIC_SALARY' => number_format($salaryData['Basic_sal'], 2),
                'BR' => number_format($salaryData['Br_pay'], 2),
                'TOTAL_FOR_EPF' => number_format($salaryData['Total_F_Epf'], 2),
                'GROSS_PAY' => number_format($salaryData['Gross_pay'], 2),
                'ADVANCE_PAID' => number_format($salaryData['Salary_advance'], 2),
                'PAYE' => number_format($salaryData['Payee_amount'], 2),
                'NO_PAY' => number_format($salaryData['no_pay_deduction'], 2),
                'STAMP_D' => number_format($salaryData['Stamp_duty'], 2),
                'EPF_8' => number_format($salaryData['EPF_Worker_Amount'], 2),
                'TOT_DEDUCTION' => number_format($salaryData['tot_deduction'], 2),
                'NET_SALARY' => number_format($salaryData['Net_salary'], 2),
                'EPF_12' => number_format($salaryData['EPF_Employee_Amount'], 2),
                'ETF_3' => number_format($salaryData['ETF_Amount'], 2),
                'BALANCE' => number_format($salaryData['D_Salary'], 2),
            ];

            // Handle Variable Allowances
            $this->handle_variable_allowances($EmpNo, $month, $year, $variable_allowances);

            // Handle Fixed Allowances
            $this->handle_fixed_allowances($EmpNo, $month, $year, $fixed_allowances);

            // Handle Variable Deductions
            $this->handle_variable_deductions($EmpNo, $month, $year, $variable_deductions);

            // Handle Fixed Deductions
            $this->handle_fixed_deductions($EmpNo, $month, $year, $fixed_deductions);

        }

        // Set flash data for success message
        $this->session->set_flashdata('success_message', 'Payroll Process successfully');

        // // Redirect to the same page
        redirect('Pay/Payroll_Process');

        // echo json_encode($finalPayroll);
    }

    // Function to calculate salary
    private function calculate_salary($BasicSal, $BR1, $BR2, $Incentive, $is_no_pay_calc, $NopayDays, $AdvanceAmount, $payee_slabs, $VariableAllowancesAmount, $FixedAllowancesAmount, $VariableDeductionAmount, $FixedDeductionAmount)
    {

        // Calculate TotalForEPF
        $TotalForEPF = $BasicSal + $BR1 + $BR2 + $Incentive;

        $GrossSal = $TotalForEPF + $VariableAllowancesAmount + $FixedAllowancesAmount;

        //calculate nopay deduction
        $NopayRate = $BasicSal / 30; // Assuming 30 days in a month
        $NopayDeduction = ($is_no_pay_calc == 1) ? 0 : ($NopayDays * $NopayRate);

        //payee tax calculation is on below function
        $payeeTax = $this->calculate_payee_tax($GrossSal, $payee_slabs);

        // Stamp duty calculation, stamp duty is rs.25 of the gross salary if gross salary is above 25000
        $StampDeduction = ($GrossSal > 25000) ? 25 : 0;

        // EPF and ETF calculation
        $EPF_8 = 0.08 * $TotalForEPF;
        $EPF_12 = 0.12 * $TotalForEPF;
        $ETF_3 = 0.03 * $TotalForEPF;

        $TotalDeduction = $AdvanceAmount + $payeeTax + $NopayDeduction + $StampDeduction + $EPF_8 + $VariableDeductionAmount + $FixedDeductionAmount;
        $NetSalary = $GrossSal - $TotalDeduction;
        $Balance = $NetSalary;

        return [
            'Basic_sal' => $BasicSal,
            'Br_pay' => $BR1 + $BR2,
            'Incentive' => $Incentive,
            'Allowances' => $VariableAllowancesAmount + $FixedAllowancesAmount,
            'Total_F_Epf' => $TotalForEPF,
            'Gross_pay' => $GrossSal,
            'Net_salary' => $NetSalary,
            'Deductions' => $VariableDeductionAmount + $FixedDeductionAmount,
            'tot_deduction' => $TotalDeduction,
            'EPF_Worker_Amount' => $EPF_8,
            'EPF_Employee_Amount' => $EPF_12,
            'ETF_Amount' => $ETF_3,
            'Salary_advance' => $AdvanceAmount,
            'Payee_amount' => $payeeTax,
            'no_pay_deduction' => $NopayDeduction,
            'Stamp_duty' => $StampDeduction,
            'D_Salary' => $Balance
        ];
    }

    //calculate payee tax
    private function calculate_payee_tax($Gross_sal, $payee)
    {
        if ($Gross_sal > 140000) {
            $gross_for_payee = 140000;
        } else {
            $gross_for_payee = $Gross_sal;
        }

        $st_gross_Pay = $gross_for_payee * 12;

        $free_rate = 100000;
        $anual_freee_rate = $free_rate * 12;
        $payee_now_amount = 0;

        $calculate_gross_pay = $st_gross_Pay - $anual_freee_rate;

        if ($calculate_gross_pay > 0) {
            foreach ($payee as $slab) {
                if ($calculate_gross_pay <= 0)
                    break;

                $slab_limit = 500000;
                $taxable_amount = min($calculate_gross_pay, $slab_limit);
                $payee_now_amount += ($taxable_amount / 12) * ($slab->Tax_rate / 100);
                $calculate_gross_pay -= $taxable_amount;
            }
        }

        // print_r( $Gross_sal. '-' . $payee_now_amount . '<br/>');
        return $payee_now_amount;
    }

    // Function to handle variable allowances
    private function handle_variable_allowances($EmpNo, $month, $year, $VariableAllowancesAmount)
    {
        if (!empty($VariableAllowancesAmount) && is_array($VariableAllowancesAmount)) {
            foreach ($VariableAllowancesAmount as $allowance) {
                $SalAllowanceDataID = $allowance->ID ?? 0;
                if ($SalAllowanceDataID != 0) {
                    $SalDataQuery = $this->Db_model->getfilteredData("
                        SELECT * FROM tbl_salary 
                        WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
                    ");
                    if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                        $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                        $dataArray = [
                            'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                            'tbl_salary_ID' => $SalDataCount1,
                            'Year' => $year,
                            'Month' => $month,
                            'EmpNo' => $EmpNo,
                            'Allowance_Status' => 'varialble_allowance'
                        ];
                        $SalhasData = $this->Db_model->getfilteredData("
                            SELECT * FROM tbl_allowance_has_tbl_salary 
                            WHERE EmpNo = $EmpNo 
                              AND Month = $month 
                              AND Year = $year 
                              AND tbl_varialble_allowance_ID = $SalAllowanceDataID
                        ");
                        $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                    }
                }
            }
        } else {
            // Handle the case when no data is found
            $VariableAllowancesAmount = []; // Ensure it's an empty array
        }
    }

    // Function to handle fixed allowances
    private function handle_fixed_allowances($EmpNo, $month, $year, $FixedAllowancesAmount)
    {
        if (!empty($FixedAllowancesAmount) && is_array($FixedAllowancesAmount)) {
            foreach ($FixedAllowancesAmount as $allowance) {
                $SalAllowanceDataID = $allowance->ID ?? 0;
                if ($SalAllowanceDataID != 0) {
                    $SalDataQuery = $this->Db_model->getfilteredData("
                        SELECT * FROM tbl_salary 
                        WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
                    ");
                    if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                        $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                        $dataArray = [
                            'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                            'tbl_salary_ID' => $SalDataCount1,
                            'Year' => $year,
                            'Month' => $month,
                            'EmpNo' => $EmpNo,
                            'Allowance_Status' => 'fixed_allowance'
                        ];

                        // Check if already inserted
                        $SalhasData = $this->Db_model->getfilteredData("
                            SELECT * FROM tbl_allowance_has_tbl_salary 
                            WHERE EmpNo = $EmpNo 
                              AND Month = $month 
                              AND Year = $year 
                              AND tbl_varialble_allowance_ID = $SalAllowanceDataID
                        ");
                        $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                    }
                }
            }
        }
    }

    // Function to handle variable deductions
    private function handle_variable_deductions($EmpNo, $month, $year, $VariableDeductionAmount)
    {
        if (!empty($VariableDeductionAmount) && is_array($VariableDeductionAmount)) {
            foreach ($VariableDeductionAmount as $deduction) {
                $SalAllowanceDataID = $deduction->ID ?? 0;
                if ($SalAllowanceDataID != 0) {
                    $SalDataQuery = $this->Db_model->getfilteredData("
                        SELECT * FROM tbl_salary 
                        WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
                    ");
                    if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                        $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                        $dataArray = [
                            'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                            'tbl_salary_ID' => $SalDataCount1,
                            'Year' => $year,
                            'Month' => $month,
                            'EmpNo' => $EmpNo,
                            'Deduction_Status' => 'varialble_deduction'
                        ];
                        $SalhasData = $this->Db_model->getfilteredData("
                            SELECT * FROM tbl_deduction_has_tbl_salary 
                            WHERE EmpNo = $EmpNo 
                              AND Month = $month 
                              AND Year = $year 
                              AND tbl_varialble_deduction_ID = $SalAllowanceDataID
                        ");
                        $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                    }
                }
            }
        } else {
            // Handle the case when no data is found
            $VariableDeductionAmount = []; // Ensure it's an empty array
        }
    }

    // Function to handle fixed deductions
    private function handle_fixed_deductions($EmpNo, $month, $year, $FixedDeductionAmount)
    {
        if (!empty($FixedDeductionAmount) && is_array($FixedDeductionAmount)) {
            foreach ($FixedDeductionAmount as $deduction) {
                $SalAllowanceDataID = $deduction->ID ?? 0;
                if ($SalAllowanceDataID != 0) {
                    $SalDataQuery = $this->Db_model->getfilteredData("
                        SELECT * FROM tbl_salary 
                        WHERE EmpNo = $EmpNo AND Month = $month AND Year = $year
                    ");
                    if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                        $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                        $dataArray = [
                            'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                            'tbl_salary_ID' => $SalDataCount1,
                            'Year' => $year,
                            'Month' => $month,
                            'EmpNo' => $EmpNo,
                            'Deduction_Status' => 'fixed_deduction'
                        ];
                        $SalhasData = $this->Db_model->getfilteredData("
                            SELECT * FROM tbl_deduction_has_tbl_salary 
                            WHERE EmpNo = $EmpNo 
                              AND Month = $month 
                              AND Year = $year 
                              AND tbl_varialble_deduction_ID = $SalAllowanceDataID
                        ");
                        $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                    }
                }
            }
        } else {
            // Handle the case when no data is found
            $FixedDeductionAmount = []; // Ensure it's an empty array
        }
    }

}
?>