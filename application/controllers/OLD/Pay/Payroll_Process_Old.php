<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_Process extends CI_Controller
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

        $this->load->helper('url');
        $data['title'] = "Payroll Process | HRM SYSTEM";
        $data['data_emp'] = $this->Db_model->getData('EmpNo,Emp_Full_Name', 'tbl_empmaster');
        $this->load->view('Payroll/Payroll_process/index', $data);
    }

    /*
     * Payroll Process
     */

    public function emp_payroll_process()
    {
        //die;
        date_default_timezone_set('Asia/Colombo');
        $year = date("Y");
        // $year = "2024";
        $month = $this->input->post('cmb_month');

        $date = date_create();
        $timestamp = date_format($date, 'Y-m-d H:i:s');


        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,EMP_ST_ID,Enroll_No,BR1,BR2, EPFNO,Dep_ID,Des_ID,RosterCode, Status,Is_EPF  FROM  tbl_empmaster where status=1 and Active_process=1");
        //        $dtEmp['EmpData'] = $this->Db_model->getfilteredData("SELECT EmpNo,Enroll_No, EPFNO,Dep_ID,Des_ID,RosterCode, Status  FROM  tbl_empmaster where EmpNo=3316");
        //For loop for All active employees 
        for ($x = 0; $x < count($dtEmp['EmpData']); $x++) {

            $EmpNo = $dtEmp['EmpData'][$x]->EmpNo;
            $EpfNo = $dtEmp['EmpData'][$x]->EPFNO;
            $Dep_ID = $dtEmp['EmpData'][$x]->Dep_ID;
            $Des_ID = $dtEmp['EmpData'][$x]->Des_ID;
            $br1 = $dtEmp['EmpData'][$x]->BR1;
            $br2 = $dtEmp['EmpData'][$x]->BR2;

            $Emp_ST = $dtEmp['EmpData'][$x]->EMP_ST_ID;


            $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_salary where EmpNo=$EmpNo and month=$month and year=$year");

            //            var_dump($HasRow);die;
            //IF Salary records have in Salary table update salary records into salary table

            if ($HasRow[0]->HasRow > 0) {

                //            var_dump($dtEmp['EmpData']);die;
                //Get Employee Basic Salary | Incentive
                $SalData = $this->Db_model->getfilteredData("select EmpNo,EPFNO, Is_EPF,Dep_ID, Des_ID,Basic_Salary,Fixed_Allowance,Incentive,is_nopay_calc from tbl_empmaster where EmpNo=$EmpNo");
                $BasicSal = $SalData[0]->Basic_Salary;
                $Incentive = $SalData[0]->Incentive;
                $Fixed_Allowance = $SalData[0]->Fixed_Allowance;
                $Is_EPF = $SalData[0]->Is_EPF;
                $is_no_pay = $SalData[0]->is_nopay_calc;


                //**** Get Nopay days
                $Nopay = $this->Db_model->getfilteredData("select sum(nopay) as nopay, sum(nopay_hrs) nopay_hrs,sum(Att_Allow) as Att_Allow from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year AND ShType = 'DU' ");
                $NopayDays = $Nopay[0]->nopay;

                if ($NopayDays == null) {
                    $NopayDays = 0;
                }

                //**** Calculate no pay amount
                $NopayRate = ($BasicSal + $Incentive) / 22;


                if ($is_no_pay == 1) {
                    $NopayDays = 0;
                }



                $Nopay_Deduction = $NopayRate * $NopayDays;


                //**** Get Allowance Details
                $budget_relevance = $this->Db_model->getfilteredData("select Br_ID, Amount from tbl_varialble_br where EmpNo=$EmpNo and Month=$month and Year=$year");
                // $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month=$month and Year=$year");
                $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month='0' and Year='0000'");

                // tbl_allowance_has_tbl_salary 'INSERT DATA' - start
                $SalData['SalData1'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_varialble_allowance WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");


                // FIXD_allowance_has_tbl_salary 'FIXD INSERT DATA' - start
                $SalData['SalData2'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_fixed_allowance WHERE EmpNo=$EmpNo");


                $Att_Allowance = 0;
                $tbPerformance = 0;
                $tbAttendances = 0;
                $tbFuel = 0;
                $tbTransport = 0;
                $tbTraveling = 0;
                $tbSPAllowance = 0;
                $tbIncrement = 0;
                $tbOther_OT = 0;
                $Allowance_2 = 0;
                $Allowance_3 = 0;

                $welfair_1 = 0;
                $welfair_1Fix = 0;
                if (!empty($welfair)) {
                    $welfair_1 = $welfair[0]->Amount;
                }
                /*
                 * Allowence special types
                 */

                // Easy code
                $allowanceIndices = range(0, 8); // Indices from 0 to 8

                foreach ($allowanceIndices as $index) {
                    if (
                        isset($SalData['SalData1'][$index], $SalData['SalData2'][$index]) &&
                        (($SalData['SalData1'][$index]->Alw_ID == 1 && $SalData['SalData2'][$index]->Alw_ID == 1) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 2 && $SalData['SalData2'][$index]->Alw_ID == 2) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 3 && $SalData['SalData2'][$index]->Alw_ID == 3) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 4 && $SalData['SalData2'][$index]->Alw_ID == 4) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 5 && $SalData['SalData2'][$index]->Alw_ID == 5) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 6 && $SalData['SalData2'][$index]->Alw_ID == 6) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 7 && $SalData['SalData2'][$index]->Alw_ID == 7) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 8 && $SalData['SalData2'][$index]->Alw_ID == 8))
                    ) {
                        switch ($SalData['SalData1'][$index]->Alw_ID) {
                            case 1:
                                $tbPerformance = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 2:
                                $tbAttendances = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 3:
                                $tbFuel = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 4:
                                $tbTransport = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 5:
                                $tbTraveling = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 6:
                                $tbSPAllowance = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 7:
                                $tbIncrement = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 8:
                                $tbOther_OT = $SalData['SalData1'][$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($SalData['SalData1'][$index])) {
                            switch ($SalData['SalData1'][$index]->Alw_ID) {
                                case 1:
                                    $tbPerformance = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbAttendances = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbFuel = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 4:
                                    $tbTransport = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 5:
                                    $tbTraveling = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 6:
                                    $tbSPAllowance = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 7:
                                    $tbIncrement = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 8:
                                    $tbOther_OT = $SalData['SalData1'][$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($SalData['SalData2'][$index])) {
                            switch ($SalData['SalData2'][$index]->Alw_ID) {
                                case 1:
                                    $tbPerformance = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbAttendances = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbFuel = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 4:
                                    $tbTransport = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 5:
                                    $tbTraveling = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 6:
                                    $tbSPAllowance = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 7:
                                    $tbIncrement = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 8:
                                    $tbOther_OT = $SalData['SalData2'][$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                // tbl_allowance_has_tbl_salary 'INSERT DATA' - start
                $SalData['SalData3'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_variable_deduction WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");


                // FIXD_allowance_has_tbl_salary 'FIXD INSERT DATA' - start
                $SalData['SalData4'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_fixed_deduction WHERE EmpNo=$EmpNo");


                $payee = $this->Db_model->getfilteredData("SELECT * FROM tbl_payee");
                $stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month=$month and Year=$year");
                $fixed_stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month='0' ");

                //Get Salary Advance details
                $Sal_Advance = $this->Db_model->getfilteredData("select Amount from tbl_salary_advance where Is_Approve=1 and EmpNo=$EmpNo and month=$month and year = $year");

                $Fest_Advance = $this->Db_model->getfilteredData("SELECT Amount from tbl_festivel_advance where EmpNo=$EmpNo and Month=$month and Year = $year");
                $uniform = 0;
                $other = 0;
                $deduction1 = 0;
                $deduction2 = 0;
                $tbMobile_Ded = 0;
                $tbFuel_Ded = 0;
                $tbOther_Ded = 0;

                $stamp_Duty1 = 0;
                if (!empty($stamp_Duty)) {
                    $stamp_Duty1 = $stamp_Duty[0]->Amount;
                } else if (empty($stamp_Duty) && !empty($fixed_stamp_Duty)) {
                    $stamp_Duty1 = $fixed_stamp_Duty[0]->Amount;
                }

                // Easy code
                $deductionsIndices = range(0, 3); // Indices from 0 to 8
                // $Deductions[0]->Ded_ID
                foreach ($deductionsIndices as $index) {
                    if (
                        isset($SalData['SalData3'][$index], $SalData['SalData4'][$index]) &&
                        (($SalData['SalData3'][$index]->Ded_ID == 1 && $SalData['SalData4'][$index]->Deduction_ID == 1) ||
                            ($SalData['SalData3'][$index]->Ded_ID == 2 && $SalData['SalData4'][$index]->Deduction_ID == 2) ||
                            ($SalData['SalData3'][$index]->Ded_ID == 3 && $SalData['SalData4'][$index]->Deduction_ID == 3))
                    ) {
                        switch ($SalData['SalData3'][$index]->Ded_ID) {
                            case 1:
                                $tbMobile_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                            case 2:
                                $tbFuel_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                            case 3:
                                $tbOther_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($SalData['SalData3'][$index])) {
                            switch ($SalData['SalData3'][$index]->Ded_ID) {
                                case 1:
                                    $tbMobile_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbFuel_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbOther_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($SalData['SalData4'][$index])) {
                            switch ($SalData['SalData4'][$index]->Deduction_ID) {
                                case 1:
                                    $tbMobile_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbFuel_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbOther_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                if ($Fest_Advance == null) {
                    $Festivel_Advance = 0;
                } else {
                    $Festivel_Advance = $Fest_Advance[0]->Amount;
                }

                //Get loan details
                $Loan = $this->Db_model->getfilteredData("select Loan_ID,Loan_amount,Month_Installment,FullAmount,Paid_Amount from tbl_loans where Is_Settled=0 and EmpNo=$EmpNo");

                /*
                 * Loan Details
                 */

                if (empty($Loan[0]->Loan_ID)) {
                    $LoanID = 0;
                } else {
                    $LoanID = $Loan[0]->Loan_ID;
                }


                if (empty($Loan[0]->Month_Installment)) {
                    $LoanMonth = 0;
                } else {
                    $LoanMonth = $Loan[0]->Month_Installment;
                }

                /*
                 * Salary Advance Details
                 */


                if (empty($Sal_Advance[0]->Amount)) {
                    $Sal_advance = 0;
                } else {
                    $Sal_advance = $Sal_Advance[0]->Amount;
                }
                /*
                 * Budget relevances
                 */


                if (empty($br1)) {
                    $budgetrelevance1 = 0;
                } else {
                    $budgetrelevance1 = $br1;
                }



                if (empty($br2)) {
                    $budgetrelevance2 = 0;
                } else {
                    $budgetrelevance2 = $br2;
                }



                /*
                 * Deduction Details
                 */

                if (empty($Deductions[0]->Ded_ID)) {
                    $Deduction_ID_1 = 0;
                } else {
                    $Deduction_ID_1 = $Deductions[0]->Ded_ID;
                }

                if (empty($Deductions[1]->Ded_ID)) {
                    $Deduction_ID_2 = 0;
                } else {
                    $Deduction_ID_2 = $Deductions[1]->Ded_ID;
                }

                if (empty($Deductions[2]->Ded_ID)) {
                    $Deduction_ID_3 = 0;
                } else {
                    $Deduction_ID_3 = $Deductions[2]->Ded_ID;
                }

                $Overtime_DB = $this->Db_model->getfilteredData("select sum(DOT) as D_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");
                $Overtime = $this->Db_model->getfilteredData("select sum(AfterExH) as N_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");

                $N_OT_Hours = $Overtime[0]->N_OT;

                $D_OT_Hours = $Overtime_DB[0]->D_OT;

                $OT_Rate = ((($BasicSal + $Fixed_Allowance) / 187) * 1.5);
                $N_OT_Amount = $OT_Rate * ($N_OT_Hours / 60);

                $OT_Rate_2 = ((($BasicSal + $Fixed_Allowance) / 187) * 2);
                $D_OT_Amount = $OT_Rate_2 * ($D_OT_Hours / 60);

                //*** Get Late Minutes
                $Late_Amount = 0;
                $Late_Min = $this->Db_model->getfilteredData("select sum(LateM) as LateMin from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and RYear=$year AND ShType = 'DU' ");

                //*** Late Min
                if (empty($Late_Min[0]->LateMin)) {
                    $Late_Min = 0;
                } else {
                    $Late_Min = $Late_Min[0]->LateMin;
                }

                $Late_rate = (($BasicSal / 187)) / 60;
                $Late_Amount = ($Late_rate * $Late_Min);

                $ed_amount = 0;
                $earlydepature = $this->Db_model->getfilteredData("select SUM(EarlyDepMin) as ed from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and RYear=$year AND ShType = 'DU' ");
                if (empty($earlydepature[0]->ed)) {
                    $ed_min = 0;
                } else {
                    $ed_min = $earlydepature[0]->ed;
                }

                $ed_rate = ((($BasicSal / 187)) / 60);
                $ed_amount = $ed_min * $ed_rate;

                //All budgetrelevances
                $budgetrelevances = $budgetrelevance1 + $budgetrelevance2;
                //All Allowances
                $Allowances = $tbSPAllowance + $tbTraveling + $tbTransport + $tbFuel + $tbAttendances + $tbPerformance + $tbIncrement + $tbOther_OT + $Allowance_2 + $Allowance_3;

                //Calculate Gross salary
                $Gross_sal = ($BasicSal + $Fixed_Allowance + $Incentive + $budgetrelevances + $Allowances);

                /*
                 *payee tax calculate start
                 */
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

                if (0 > $calculate_gross_pay) {
                    $payee_now_amount = 0;
                } else {
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        $payeeamount = ($calculate_gross_pay / 12) * ($payee[5]->Tax_rate / 100);
                        $calculate_gross_pay -= 500000;
                        $payee_now_amount += $payeeamount;
                    }
                }

                // echo $EmpNo . '-' . $Gross_sal. '-' . $payee_now_amount .'/';

                /*
                 *payee tax calculate end
                 */
                $grosspay = 0;
                //Calculate EPF Employee
                $EPF_Worker = (8 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);

                //Total for epf
                $tottal_for_epf = $BasicSal + $Fixed_Allowance + $budgetrelevances;

                //Calculate EPF Employer
                $EPF_Employer = (12 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);

                //Calculate ETF Employee
                $ETF = (3 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);
                // $integerwelfairValue = (int)$welfair;
                //Calculate Total Deductions
                $Tot_deductions = $payee_now_amount + $EPF_Worker + $Sal_advance + $Festivel_Advance + $uniform + $other + $deduction1 + $deduction2 + $LoanMonth + $Nopay_Deduction + $tbMobile_Ded + $tbFuel_Ded + $tbOther_Ded + $welfair_1;
                // + $Late_Amount + $ed_amount + $OT_Rate
                //Calculate Net Salary
                // $netSal = ($Gross_sal + $N_OT_Amount + $D_OT_Amount + $Att_Allowance + $Allowances) - $Tot_deductions;
                $netSal = $grosspay - $Tot_deductions;
                //calculate Gross pay
                $grosspay = $Gross_sal;

                $D_Salary = $grosspay - $Tot_deductions;

                if ($Is_EPF == 0) {
                    $EPF_Worker = 0;
                    $EPF_Employer = 0;
                    $ETF = 0;
                }

                // echo $EmpNo . '-' . $Gross_sal. '-' . $grosspay .'/';
                // $tbMobile_Ded = 1;
                $data = array(
                    'EmpNo' => $EmpNo,
                    'EPFNO' => $EpfNo,
                    'Basic_sal' => $BasicSal,
                    'Total_F_Epf' => $tottal_for_epf,
                    'Br_pay' => $budgetrelevances,
                    'Incentive' => $Incentive,
                    'Dep_ID' => $Dep_ID,
                    'Des_ID' => $Des_ID,
                    'No_Pay_days' => $NopayDays,
                    'no_pay_deduction' => $Nopay_Deduction,
                    'EPF_Worker_Rate' => 8,
                    'EPF_Worker_Amount' => $EPF_Worker,
                    'EPF_Employee_Rate' => 12,
                    'EPF_Employee_Amount' => $EPF_Employer,
                    'ETF_Rate' => 3,
                    'ETF_Amount' => $ETF,
                    'Loan_Instalment' => $LoanMonth,
                    'Salary_advance' => $Sal_advance,
                    'Ed_min' => $ed_min,
                    'Late_min' => $Late_Min,
                    'Ed_deduction' => $ed_amount,
                    'Late_deduction' => $Late_Amount,
                    'Alw_ID_1' => 0,
                    'Allowance_1' => 0,
                    'Alw_ID_2' => 0,
                    // 'Increment' => $Allowance_2,
                    'Alw_ID_3' => 0,
                    // 'Other_OT' => $Allowance_3,
                    'Alw_ID_4' => 0,
                    'Allowance_4' => 0,
                    'Alw_ID_5' => 0,
                    'Allowance_5' => 0,
                    'Performance' => $tbPerformance,
                    'Attendances' => $tbAttendances,
                    'Fuel' => $tbFuel,
                    'Transport' => $tbTransport,
                    'Traveling' => $tbTraveling,
                    'SPAllowance' => $tbSPAllowance,
                    'Increment' => $tbIncrement,
                    'Other_OT' => $tbOther_OT,
                    'Normal_OT_Hrs' => ($N_OT_Hours / 60),
                    'Normal_OT_Pay' => $N_OT_Amount,
                    'Double_OT_Hrs' => ($D_OT_Hours / 60),
                    'Double_OT_Pay' => $D_OT_Amount,
                    'Uniform' => $uniform,
                    'Ded_ID_1' => $Deduction_ID_1,
                    'Deduct_1' => $tbOther_Ded,
                    'Ded_ID_2' => $Deduction_ID_2,
                    'Deduct_2' => $deduction1,
                    'Ded_ID_3' => $Deduction_ID_3,
                    'Deduct_3' => $deduction2,
                    'Mobile_Ded' => $tbMobile_Ded,
                    'Fuel_Ded' => $tbFuel_Ded,
                    'Wellfare' => $welfair_1,
                    'Payee_amount' => $payee_now_amount,
                    'Stamp_duty' => $stamp_Duty1,
                    'tot_deduction' => $Tot_deductions,
                    'Gross_pay' => $grosspay,
                    'Gross_sal' => $Gross_sal,
                    'D_Salary' => $D_Salary,
                    'Net_salary' => $netSal
                );


                //***** Update Salary Table
                $whereArray = array("EmpNo" => $EmpNo, 'Month' => $month, 'Year' => $year);
                $result = $this->Db_model->updateData("tbl_salary", $data, $whereArray);

                // Ensure SalData1 is an array before using count()
                if (!empty($SalData['SalData1']) && is_array($SalData['SalData1'])) {
                    for ($y = 0; $y < count($SalData['SalData1']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData1'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Allowance_Status' => 'varialble_allowance'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_allowance_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_allowance_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData1'] = []; // Ensure it's an empty array
                }
                // tbl_allowance_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData2 is an array before using count()
                if (!empty($SalData['SalData2']) && is_array($SalData['SalData2'])) {
                    for ($y = 0; $y < count($SalData['SalData2']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData2'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Allowance_Status' => 'fixed_allowance'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_allowance_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_allowance_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData2'] = []; // Ensure it's an empty array
                }
                // FIXD_allowance_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData3 is an array before using count()
                if (!empty($SalData['SalData3']) && is_array($SalData['SalData3'])) {
                    for ($y = 0; $y < count($SalData['SalData3']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData3'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Deduction_Status' => 'varialble_deduction'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_deduction_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_deduction_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData3'] = []; // Ensure it's an empty array
                }
                // tbl_allowance_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData4 is an array before using count()
                if (!empty($SalData['SalData4']) && is_array($SalData['SalData4'])) {
                    for ($y = 0; $y < count($SalData['SalData4']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData4'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Deduction_Status' => 'fixed_deduction'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_deduction_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_deduction_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData4'] = []; // Ensure it's an empty array
                }
                // FIXD_allowance_has_tbl_salary 'INSERT DATA' - end
                /*
                 * Loan Amount
                 */
                if (empty($Loan[0]->Paid_Amount)) {
                    $PaidAmount = 0;
                } else {
                    $PaidAmount = $Loan[0]->Paid_Amount;
                }

                if (empty($Loan[0]->FullAmount)) {
                    $Full_Amount = 0;
                } else {
                    $Full_Amount = $Loan[0]->FullAmount;
                }

                //****** Loan Amount deduction process
                $PaidAmount_to = $PaidAmount + $LoanMonth;
                $BalanceAmount = $Full_Amount - $PaidAmount_to;

                if ($BalanceAmount <= 0) {
                    $Is_Settele = 1;
                } else {
                    $Is_Settele = 0;
                }

                $data_loan = array(
                    'EmpNo' => $EmpNo,
                    'Paid_Amount' => $PaidAmount_to,
                    'Balance_amount' => $BalanceAmount,
                    'Is_Settled' => $Is_Settele,
                );

                $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year");


                if ($LoanMonth == 0) {
                } {
                    $dataArray = array(
                        'Year' => $year,
                        'EmpNo' => $EmpNo,
                        'Month' => $month,
                        'Amount_month' => $LoanMonth,
                        'Loan_ID' => $LoanID,
                        'Time_Trans' => $timestamp,
                    );

                    $this->Db_model->insertData("tbl_loan_trans", $dataArray);
                }

                $HasRow = $this->Db_model->getfilteredData("select count(EmpNo) as HasRow from tbl_loan_trans where EmpNo=$EmpNo and month=$month and year=$year ");

                if ($HasRow[0]->HasRow) {
                } else {
                    $whereArray_loan = array("EmpNo" => $EmpNo);
                    $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);
                }
            } else {

                $SalData = $this->Db_model->getfilteredData("select EmpNo,EPFNO,Is_EPF, Dep_ID, Des_ID,Basic_Salary,Fixed_Allowance,Incentive,is_nopay_calc from tbl_empmaster where EmpNo=$EmpNo");
                $BasicSal = $SalData[0]->Basic_Salary;
                $Incentive = $SalData[0]->Incentive;
                $Fixed_Allowance = $SalData[0]->Fixed_Allowance;
                $Is_EPF = $SalData[0]->Is_EPF;
                $is_no_pay = $SalData[0]->is_nopay_calc;

                //**** Get Nopay days
                $Nopay = $this->Db_model->getfilteredData("select sum(nopay) as nopay, sum(nopay_hrs) nopay_hrs,sum(Att_Allow) as Att_Allow from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and EXTRACT(YEAR FROM FDate)=$year AND ShType = 'DU'");
                $NopayDays = $Nopay[0]->nopay;


                if ($NopayDays == null) {
                    $NopayDays = 0;
                }

                //**** Calculate no pay amount
                $NopayRate = ($BasicSal + $Incentive) / 22;

                if ($is_no_pay == 1) {
                    $NopayDays = 0;
                }

                $Nopay_Deduction = $NopayRate * $NopayDays;

                //**** Get Allowance Details
                $budget_relevance = $this->Db_model->getfilteredData("select Br_ID, Amount from tbl_varialble_br where EmpNo=$EmpNo and Month=$month and Year=$year");
                // $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month=$month and Year=$year");
                $welfair = $this->Db_model->getfilteredData("select welfair_id, Amount from tbl_variable_welfair where EmpNo=$EmpNo and Month='0' and Year='0000'");

                // tbl_allowance_has_tbl_salary 'INSERT DATA' - start
                $SalData['SalData1'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_varialble_allowance WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                

                // FIXD_allowance_has_tbl_salary 'FIXD INSERT DATA' - start
                $SalData['SalData2'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_fixed_allowance WHERE EmpNo=$EmpNo");
                

                $Att_Allowance = 0;
                $tbPerformance = 0;
                $tbAttendances = 0;
                $tbFuel = 0;
                $tbTransport = 0;
                $tbTraveling = 0;
                $tbSPAllowance = 0;
                $tbIncrement = 0;
                $tbOther_OT = 0;
                $Allowance_2 = 0;
                $Allowance_3 = 0;

                $welfair_1 = 0;
                $welfair_1Fix = 0;
                if (!empty($welfair)) {
                    $welfair_1 = $welfair[0]->Amount;
                }
                /*
                 * Allowence special types
                 */

                // Easy code
                $allowanceIndices = range(0, 8); // Indices from 0 to 8

                foreach ($allowanceIndices as $index) {
                    if (
                        isset($SalData['SalData1'][$index], $SalData['SalData2'][$index]) &&
                        (($SalData['SalData1'][$index]->Alw_ID == 1 && $SalData['SalData2'][$index]->Alw_ID == 1) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 2 && $SalData['SalData2'][$index]->Alw_ID == 2) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 3 && $SalData['SalData2'][$index]->Alw_ID == 3) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 4 && $SalData['SalData2'][$index]->Alw_ID == 4) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 5 && $SalData['SalData2'][$index]->Alw_ID == 5) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 6 && $SalData['SalData2'][$index]->Alw_ID == 6) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 7 && $SalData['SalData2'][$index]->Alw_ID == 7) ||
                            ($SalData['SalData1'][$index]->Alw_ID == 8 && $SalData['SalData2'][$index]->Alw_ID == 8))
                    ) {
                        switch ($SalData['SalData1'][$index]->Alw_ID) {
                            case 1:
                                $tbPerformance = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 2:
                                $tbAttendances = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 3:
                                $tbFuel = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 4:
                                $tbTransport = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 5:
                                $tbTraveling = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 6:
                                $tbSPAllowance = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 7:
                                $tbIncrement = $SalData['SalData1'][$index]->Amount;
                                break;
                            case 8:
                                $tbOther_OT = $SalData['SalData1'][$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($SalData['SalData1'][$index])) {
                            switch ($SalData['SalData1'][$index]->Alw_ID) {
                                case 1:
                                    $tbPerformance = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbAttendances = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbFuel = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 4:
                                    $tbTransport = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 5:
                                    $tbTraveling = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 6:
                                    $tbSPAllowance = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 7:
                                    $tbIncrement = $SalData['SalData1'][$index]->Amount;
                                    break;
                                case 8:
                                    $tbOther_OT = $SalData['SalData1'][$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($SalData['SalData2'][$index])) {
                            switch ($SalData['SalData2'][$index]->Alw_ID) {
                                case 1:
                                    $tbPerformance = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbAttendances = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbFuel = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 4:
                                    $tbTransport = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 5:
                                    $tbTraveling = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 6:
                                    $tbSPAllowance = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 7:
                                    $tbIncrement = $SalData['SalData2'][$index]->Amount;
                                    break;
                                case 8:
                                    $tbOther_OT = $SalData['SalData2'][$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                // tbl_allowance_has_tbl_salary 'INSERT DATA' - start
                $SalData['SalData3'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_variable_deduction WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                

                // FIXD_allowance_has_tbl_salary 'FIXD INSERT DATA' - start
                $SalData['SalData4'] = $this->Db_model->getfilteredData("SELECT * FROM tbl_fixed_deduction WHERE EmpNo=$EmpNo");
                

                $payee = $this->Db_model->getfilteredData("SELECT * FROM tbl_payee");
                $stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month=$month and Year=$year");
                $fixed_stamp_Duty = $this->Db_model->getfilteredData("select ID, Amount from tbl_variable_stamp where EmpNo=$EmpNo and Month='0' ");

                //Get Salary Advance details
                $Sal_Advance = $this->Db_model->getfilteredData("select Amount from tbl_salary_advance where Is_Approve=1 and EmpNo=$EmpNo and month=$month and year = $year");

                $Fest_Advance = $this->Db_model->getfilteredData("SELECT Amount from tbl_festivel_advance where EmpNo=$EmpNo and Month=$month and Year = $year");
                $uniform = 0;
                $other = 0;
                $deduction1 = 0;
                $deduction2 = 0;
                $tbMobile_Ded = 0;
                $tbFuel_Ded = 0;
                $tbOther_Ded = 0;

                $stamp_Duty1 = 0;
                if (!empty($stamp_Duty)) {
                    $stamp_Duty1 = $stamp_Duty[0]->Amount;
                } else if (empty($stamp_Duty) && !empty($fixed_stamp_Duty)) {
                    $stamp_Duty1 = $fixed_stamp_Duty[0]->Amount;
                }

                // Easy code
                $deductionsIndices = range(0, 3); // Indices from 0 to 8
                // $Deductions[0]->Ded_ID
                foreach ($deductionsIndices as $index) {
                    if (
                        isset($SalData['SalData3'][$index], $SalData['SalData4'][$index]) &&
                        (($SalData['SalData3'][$index]->Ded_ID == 1 && $SalData['SalData4'][$index]->Deduction_ID == 1) ||
                            ($SalData['SalData3'][$index]->Ded_ID == 2 && $SalData['SalData4'][$index]->Deduction_ID == 2) ||
                            ($SalData['SalData3'][$index]->Ded_ID == 3 && $SalData['SalData4'][$index]->Deduction_ID == 3))
                    ) {
                        switch ($SalData['SalData3'][$index]->Ded_ID) {
                            case 1:
                                $tbMobile_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                            case 2:
                                $tbFuel_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                            case 3:
                                $tbOther_Ded = $SalData['SalData3'][$index]->Amount;
                                break;
                        }
                    } else {
                        if (!empty($SalData['SalData3'][$index])) {
                            switch ($SalData['SalData3'][$index]->Ded_ID) {
                                case 1:
                                    $tbMobile_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbFuel_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbOther_Ded = $SalData['SalData3'][$index]->Amount;
                                    break;
                            }
                        }
                        if (!empty($SalData['SalData4'][$index])) {
                            switch ($SalData['SalData4'][$index]->Deduction_ID) {
                                case 1:
                                    $tbMobile_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                                case 2:
                                    $tbFuel_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                                case 3:
                                    $tbOther_Ded = $SalData['SalData4'][$index]->Amount;
                                    break;
                            }
                        }
                    }
                }

                if ($Fest_Advance == null) {
                    $Festivel_Advance = 0;
                } else {
                    $Festivel_Advance = $Fest_Advance[0]->Amount;
                }

                //**** Get loan Details
                $Loan = $this->Db_model->getfilteredData("select Loan_ID,Loan_amount,Month_Installment,FullAmount,Paid_Amount from tbl_loans where Is_Settled=0 and EmpNo=$EmpNo");

                /*
                 * Loan Details
                 */

                if (empty($Loan[0]->Loan_ID)) {
                    $LoanID = 0;
                } else {
                    $LoanID = $Loan[0]->Loan_ID;
                }


                if (empty($Loan[0]->Month_Installment)) {
                    $LoanMonth = 0;
                } else {
                    $LoanMonth = $Loan[0]->Month_Installment;
                }

                /*
                 * Salary Advance Details
                 */

                if (empty($Sal_Advance[0]->Amount)) {
                    $Sal_advance = 0;
                } else {
                    $Sal_advance = $Sal_Advance[0]->Amount;
                }

                /*
                 * Budget relevances
                 */
                if (empty($br1)) {
                    $budgetrelevance1 = 0;
                } else {
                    $budgetrelevance1 = $br1;
                }

                if (empty($br2)) {
                    $budgetrelevance2 = 0;
                } else {
                    $budgetrelevance2 = $br2;
                }

                /*
                 * Allowance Details
                 */


                /*
                 * Deduction Details
                 */

                if (empty($Deductions[0]->Ded_ID)) {
                    $Deduction_ID_1 = 0;
                } else {
                    $Deduction_ID_1 = $Deductions[0]->Ded_ID;
                }

                if (empty($Deductions[1]->Ded_ID)) {
                    $Deduction_ID_2 = 0;
                } else {
                    $Deduction_ID_2 = $Deductions[1]->Ded_ID;
                }

                if (empty($Deductions[2]->Ded_ID)) {
                    $Deduction_ID_3 = 0;
                } else {
                    $Deduction_ID_3 = $Deductions[2]->Ded_ID;
                }

                $Overtime_DB = $this->Db_model->getfilteredData("select sum(DOT) as D_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");
                $Overtime = $this->Db_model->getfilteredData("select sum(AfterExH) as N_OT from tbl_individual_roster where EmpNo='$EmpNo' and EXTRACT(MONTH FROM FDate)=$month and  EXTRACT(YEAR FROM FDate) =$year");

                $N_OT_Hours = $Overtime[0]->N_OT;

                $D_OT_Hours = $Overtime_DB[0]->D_OT;

                $OT_Rate = ((($BasicSal + $Fixed_Allowance) / 187) * 1.5);
                $N_OT_Amount = $OT_Rate * ($N_OT_Hours / 60);

                $OT_Rate_2 = ((($BasicSal + $Fixed_Allowance) / 187) * 2);
                $D_OT_Amount = $OT_Rate_2 * ($D_OT_Hours / 60);

                //*** Get Late Minutes
                $Late_Amount = 0;
                $Late_Min = $this->Db_model->getfilteredData("select sum(LateM) as LateMin from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and RYear=$year AND ShType = 'DU' ");

                //*** Late Min
                if (empty($Late_Min[0]->LateMin)) {
                    $Late_Min = 0;
                } else {
                    $Late_Min = $Late_Min[0]->LateMin;
                }

                $Late_rate = (($BasicSal / 187)) / 60;
                $Late_Amount = ($Late_rate * $Late_Min);

                $ed_amount = 0;
                $earlydepature = $this->Db_model->getfilteredData("select SUM(EarlyDepMin) as ed from tbl_individual_roster where EmpNo=$EmpNo and EXTRACT(MONTH FROM FDate)=$month and RYear=$year AND ShType = 'DU' ");
                if (empty($earlydepature[0]->ed)) {
                    $ed_min = 0;
                } else {
                    $ed_min = $earlydepature[0]->ed;
                }

                $ed_rate = ((($BasicSal / 187)) / 60);
                $ed_amount = $ed_min * $ed_rate;

                //All budgetrelevances
                $budgetrelevances = $budgetrelevance1 + $budgetrelevance2;
                //All Allowances
                $Allowances = $tbSPAllowance + $tbTraveling + $tbTransport + $tbFuel + $tbAttendances + $tbPerformance + $tbIncrement + $tbOther_OT + $Allowance_2 + $Allowance_3;

                //Calculate Gross salary
                $Gross_sal = ($BasicSal + $Fixed_Allowance + $Incentive + $budgetrelevances + $Allowances);

                /*
                 *payee tax calculate start
                 */
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

                if (0 > $calculate_gross_pay) {
                    $payee_now_amount = 0;
                } else {
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[0]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[1]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[2]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[3]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        if ($calculate_gross_pay >= 500000) {
                            $payeeamount = (500000 / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        } else if (0 < $calculate_gross_pay && $calculate_gross_pay < 500000) {
                            $payeeamount = ($calculate_gross_pay / 12) * ($payee[4]->Tax_rate / 100);
                            $calculate_gross_pay -= 500000;
                            $payee_now_amount += $payeeamount;
                        }
                    }
                    if (0 < $calculate_gross_pay) {
                        $payeeamount = ($calculate_gross_pay / 12) * ($payee[5]->Tax_rate / 100);
                        $calculate_gross_pay -= 500000;
                        $payee_now_amount += $payeeamount;
                    }
                }
                /*
                 *payee tax calculate end
                 */

                $grosspay = 0;
                //Calculate EPF Employee
                $EPF_Worker = (8 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);

                //Total for epf
                $tottal_for_epf = $BasicSal + $Fixed_Allowance + $budgetrelevances;

                //Calculate EPF Employer
                $EPF_Employer = (12 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);

                //Calculate ETF Employee
                $ETF = (3 / 100) * ($BasicSal + $Fixed_Allowance + $budgetrelevances);
                // $integerwelfairValue = (int)$welfair;
                //Calculate Total Deductions
                $Tot_deductions = $payee_now_amount + $EPF_Worker + $Sal_advance + $Festivel_Advance + $uniform + $other + $deduction1 + $deduction2 + $LoanMonth + $Nopay_Deduction + $tbMobile_Ded + $tbFuel_Ded + $tbOther_Ded + $welfair_1;
                // + $Late_Amount + $ed_amount + $OT_Rate
                //Calculate Net Salary
                // $netSal = ($Gross_sal + $N_OT_Amount + $D_OT_Amount + $Att_Allowance + $Allowances) - $Tot_deductions;
                $netSal = $grosspay - $Tot_deductions;
                //calculate Gross pay
                $grosspay = $Gross_sal;

                $D_Salary = $grosspay - $Tot_deductions;

                if ($Is_EPF == 0) {
                    $EPF_Worker = 0;
                    $EPF_Employer = 0;
                    $ETF = 0;
                }

                $data = array(
                    array(
                        'EmpNo' => $EmpNo,
                        'EPFNO' => $EpfNo,
                        'Month' => $month,
                        'Year' => $year,
                        'Basic_sal' => $BasicSal,
                        'Total_F_Epf' => $tottal_for_epf,
                        'Br_pay' => $budgetrelevances,
                        'Incentive' => $Incentive,
                        'Dep_ID' => $Dep_ID,
                        'Des_ID' => $Des_ID,
                        'No_Pay_days' => $NopayDays,
                        'no_pay_deduction' => $Nopay_Deduction,
                        'EPF_Worker_Rate' => 8,
                        'EPF_Worker_Amount' => $EPF_Worker,
                        'EPF_Employee_Rate' => 12,
                        'EPF_Employee_Amount' => $EPF_Employer,
                        'ETF_Rate' => 3,
                        'ETF_Amount' => $ETF,
                        'Loan_Instalment' => $LoanMonth,
                        'Salary_advance' => $Sal_advance,
                        'Ed_min' => $ed_min,
                        'Late_min' => $Late_Min,
                        'Ed_deduction' => $ed_amount,
                        'Late_deduction' => $Late_Amount,
                        'Alw_ID_1' => 0,
                        'Allowance_1' => 0,
                        'Alw_ID_2' => 0,
                        'Increment' => 0,
                        'Alw_ID_3' => 0,
                        'Other_OT' => 0,
                        'Alw_ID_4' => 0,
                        'Allowance_4' => 0,
                        'Alw_ID_5' => 0,
                        'Allowance_5' => 0,
                        'Performance' => $tbPerformance,
                        'Attendances' => $tbAttendances,
                        'Fuel' => $tbFuel,
                        'Transport' => $tbTransport,
                        'Traveling' => $tbTraveling,
                        'SPAllowance' => $tbSPAllowance,
                        'Normal_OT_Hrs' => ($N_OT_Hours / 60),
                        'Normal_OT_Pay' => $N_OT_Amount,
                        'Double_OT_Hrs' => ($D_OT_Hours / 60),
                        'Double_OT_Pay' => $D_OT_Amount,
                        'Uniform' => $uniform,
                        'Ded_ID_1' => $Deduction_ID_1,
                        'Deduct_1' => $other,
                        'Ded_ID_2' => $Deduction_ID_2,
                        'Deduct_2' => $deduction1,
                        'Ded_ID_3' => $Deduction_ID_3,
                        'Deduct_3' => $deduction2,
                        'Wellfare' => $welfair_1,
                        'Payee_amount' => $payee_now_amount,
                        'Stamp_duty' => $stamp_Duty1,
                        'tot_deduction' => $Tot_deductions,
                        'Gross_pay' => $grosspay,
                        'Gross_sal' => $Gross_sal,
                        'D_Salary' => $D_Salary,
                        'Net_salary' => $netSal
                    )
                );

                $this->db->insert_batch('tbl_salary', $data);

                // Ensure SalData1 is an array before using count()
                if (!empty($SalData['SalData1']) && is_array($SalData['SalData1'])) {
                    for ($y = 0; $y < count($SalData['SalData1']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData1'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Allowance_Status' => 'varialble_allowance'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_allowance_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_allowance_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData1'] = []; // Ensure it's an empty array
                }
                // tbl_allowance_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData2 is an array before using count()
                if (!empty($SalData['SalData2']) && is_array($SalData['SalData2'])) {
                    for ($y = 0; $y < count($SalData['SalData2']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData2'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_allowance_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Allowance_Status' => 'fixed_allowance'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_allowance_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_allowance_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_allowance_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData2'] = []; // Ensure it's an empty array
                }
                // FIXD_allowance_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData3 is an array before using count()
                if (!empty($SalData['SalData3']) && is_array($SalData['SalData3'])) {
                    for ($y = 0; $y < count($SalData['SalData3']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData3'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Deduction_Status' => 'varialble_deduction'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_deduction_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_deduction_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData3'] = []; // Ensure it's an empty array
                }
                // tbl_deduction_has_tbl_salary 'INSERT DATA' - end

                // Ensure SalData4 is an array before using count()
                if (!empty($SalData['SalData4']) && is_array($SalData['SalData4'])) {
                    for ($y = 0; $y < count($SalData['SalData4']); $y++) {
                        $SalAllowanceDataID = $SalData['SalData4'][$y]->ID ?? 0;
                        if ($SalAllowanceDataID != 0) {
                            $SalDataQuery = $this->Db_model->getfilteredData("SELECT * FROM tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year");
                            if (!empty($SalDataQuery) && is_array($SalDataQuery)) {
                                $SalDataCount1 = $SalDataQuery[0]->ID ?? 0;
                                $dataArray = array(
                                    'tbl_varialble_deduction_ID' => $SalAllowanceDataID,
                                    'tbl_salary_ID' => $SalDataCount1,
                                    'Year' => $year,
                                    'Month' => $month,
                                    'EmpNo' => $EmpNo,
                                    'Deduction_Status' => 'fixed_deduction'
                                );
                                $SalhasData = $this->Db_model->getfilteredData("SELECT * FROM tbl_deduction_has_tbl_salary WHERE EmpNo=$EmpNo AND Month=$month AND Year=$year AND tbl_varialble_deduction_ID=$SalAllowanceDataID");
                                if (empty($SalhasData)) {
                                    $this->Db_model->insertData("tbl_deduction_has_tbl_salary", $dataArray);
                                }
                            }
                        }
                    }
                } else {
                    // Handle the case when no data is found
                    $SalData['SalData4'] = []; // Ensure it's an empty array
                }
                // FIXD_deduction_has_tbl_salary 'INSERT DATA' - end

                if (empty($Loan[0]->Paid_Amount)) {
                    $PaidAmount = 0;
                } else {
                    $PaidAmount = $Loan[0]->Paid_Amount;
                }

                $PaidAmount_to = $PaidAmount + $LoanMonth;

                if (empty($Loan[0]->FullAmount)) {
                    $Full_Amount = 0;
                } else {
                    $Full_Amount = $Loan[0]->FullAmount;
                }

                $BalanceAmount = $Full_Amount - $PaidAmount_to;

                if ($BalanceAmount <= 0) {
                    $Is_Settele = 1;
                } else {
                    $Is_Settele = 0;
                }

                $data_loan = array(
                    'EmpNo' => $EmpNo,
                    'Paid_Amount' => $PaidAmount_to,
                    'Balance_amount' => $BalanceAmount,
                    'Is_Settled' => $Is_Settele,
                );

                $whereArray_loan = array("EmpNo" => $EmpNo);
                $result = $this->Db_model->updateData("tbl_loans", $data_loan, $whereArray_loan);

                $this->session->set_flashdata('success_message', 'Allovance added successfully');
            }
        }

        $this->session->set_flashdata('success_message', 'Payroll Process successfully');
        redirect(base_url() . 'Pay/Payroll_Process');
    }
}
