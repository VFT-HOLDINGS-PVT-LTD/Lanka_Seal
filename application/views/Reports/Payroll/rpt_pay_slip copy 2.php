<?php
// Ensure no output is sent before this point
ob_start(); // Start output buffering

$date = date("Y/m/d");

// Your existing data preparation code here...

try {
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company Name');
    $pdf->SetTitle('Pay Slip - ' . $data_set[0]->Emp_Full_Name . ' - ' . date('F Y', mktime(0, 0, 0, $data_month, 1, $data_year)));
    $pdf->SetSubject('Employee Payslip');
    $pdf->SetKeywords('Payslip, Salary, ' . $data_cmp[0]->Company_Name);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set margins
    $pdf->SetMargins(10, 10, 10, true);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Add a page
    $pdf->AddPage('P', 'A5');

    // Build dynamic sections
    $earnings_html = '';
    $total_allowances = 0;

    foreach ($allowances['fixed'] as $alw) {
        $earnings_html .= '<tr><td>' . $alw->Allowance_name . '</td><td class="amount">' . number_format($alw->Amount, 2) . '</td></tr>';
        $total_allowances += $alw->Amount;
    }
    foreach ($allowances['variable'] as $alw) {
        $earnings_html .= '<tr><td>' . $alw->Allowance_name . '</td><td class="amount">' . number_format($alw->Amount, 2) . '</td></tr>';
        $total_allowances += $alw->Amount;
    }

    $deductions_html = '';
    $total_deductions = 0;

    foreach ($deductions['fixed'] as $ded) {
        $deductions_html .= '<tr><td>' . $ded->Deduction_name . '</td><td class="amount">' . number_format($ded->Amount, 2) . '</td></tr>';
        $total_deductions += $ded->Amount;
    }
    foreach ($deductions['variable'] as $ded) {
        $deductions_html .= '<tr><td>' . $ded->Deduction_name . '</td><td class="amount">' . number_format($ded->Amount, 2) . '</td></tr>';
        $total_deductions += $ded->Amount;
    }

    // Modern HTML design
    $html = '
    <style>
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .header p {
            font-size: 12px;
            margin: 3px 0;
        }
        .employee-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            background-color: #e9ecef;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #2c3e50;
            margin-bottom: 8px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table th {
            background-color: #f1f1f1;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .amount {
            text-align: right;
            font-family: courier;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .net-salary {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            font-size: 9px;
            text-align: center;
            color: #777;
            margin-top: 20px;
        }
        .divider {
            border-top: 1px dashed #ddd;
            margin: 10px 0;
        }
    </style>

    <div class="header">
        <h1>' . htmlspecialchars($data_cmp[0]->Company_Name) . '</h1>
        <p>Employee Payslip - ' . htmlspecialchars(date('F Y', mktime(0, 0, 0, $data_month, 1, $data_year))) . '</p>
        <p>Generated on: ' . htmlspecialchars(date('d M Y, h:i A')) . '</p>
    </div>

    <div class="employee-info">
        <table width="100%">
            <tr>
                <td width="50%"><strong>Employee Name:</strong> ' . htmlspecialchars($data_set[0]->Emp_Full_Name) . '</td>
                <td width="50%"><strong>Employee ID:</strong> ' . htmlspecialchars($data_set[0]->EmpNo) . '</td>
            </tr>
            <tr>
                <td><strong>Department:</strong> ' . htmlspecialchars($data_set[0]->Dep_Name) . '</td>
                <td><strong>Branch:</strong> ' . htmlspecialchars($data_set[0]->B_name) . '</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Earnings</div>
        <table class="table">
            <tr>
                <th>Description</th>
                <th class="amount">Amount (Rs.)</th>
            </tr>
            <tr>
                <td>Basic Salary</td>
                <td class="amount">' . number_format($data_set[0]->Basic_sal, 2) . '</td>
            </tr>
            ' . $earnings_html . '
            <tr class="total-row">
                <td>Total Earnings</td>
                <td class="amount">' . number_format($total_allowances + $data_set[0]->Basic_sal, 2) . '</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="section">
        <div class="section-title">Deductions</div>
        <table class="table">
            <tr>
                <th>Description</th>
                <th class="amount">Amount (Rs.)</th>
            </tr>
            ' . $deductions_html . '
            <tr>
                <td>Salary Advance</td>
                <td class="amount">' . number_format($data_set[0]->Salary_advance, 2) . '</td>
            </tr>
            <tr>
                <td>PAYE Tax</td>
                <td class="amount">' . number_format($data_set[0]->Payee_amount, 2) . '</td>
            </tr>
            <tr>
                <td>EPF (8%)</td>
                <td class="amount">' . number_format($data_set[0]->EPF_Worker_Amount, 2) . '</td>
            </tr>
            <tr>
                <td>Stamp Duty</td>
                <td class="amount">' . number_format($data_set[0]->Stamp_duty, 2) . '</td>
            </tr>
            <tr class="total-row">
                <td>Total Deductions</td>
                <td class="amount">' . number_format($data_set[0]->tot_deduction, 2) . '</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="section">
        <div class="section-title">Employer Contributions</div>
        <table class="table">
            <tr>
                <th>Description</th>
                <th class="amount">Amount (Rs.)</th>
            </tr>
            <tr>
                <td>EPF (12%)</td>
                <td class="amount">' . number_format($data_set[0]->EPF_Employee_Amount, 2) . '</td>
            </tr>
            <tr>
                <td>ETF (3%)</td>
                <td class="amount">' . number_format($data_set[0]->ETF_Amount, 2) . '</td>
            </tr>
        </table>
    </div>

    <div class="net-salary">
        Net Salary: Rs. ' . number_format($data_set[0]->Net_salary, 2) . '
    </div>

    <div class="footer">
        This is a computer-generated payslip and does not require a signature.<br>
        ' . htmlspecialchars($data_cmp[0]->Company_Name) . ' | ' . htmlspecialchars($data_cmp[0]->Company_Address) . '
    </div>';

    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Clear any previous output
    ob_end_clean();
    
    // Close and output PDF document
    $pdf->Output('Payslip_' . $data_set[0]->EmpNo . '_' . date('F_Y', mktime(0, 0, 0, $data_month, 1, $data_year)) . '.pdf', 'I');

} catch (Exception $e) {
    // Clean any output that might have been generated
    ob_end_clean();
    
    // Display error message
    die('Error generating PDF: ' . $e->getMessage());
}