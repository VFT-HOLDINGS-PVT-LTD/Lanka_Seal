<?php

$date = date("Y/m/d");

$data_month;

//var_dump($data_c[0]->id);die;
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Pay_slip_Month_' . $data_month . '.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$PDF_HEADER_TITLE = $data_cmp[0]->Company_Name;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';


// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------    
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('helvetica', '', 9, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('P', 'A6');


//$pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
////Add a custom size  
//$width = 175;  
//$height = 266; 
//$orientation = ($height>$width) ? 'P' : 'L';  
//$pdf->addFormat("custom", $width, $height);  
//$pdf->reFormat("custom", $orientation);  


$pdf->SetMargins(5,5,5 ,true);

// $pdf->SetAutoPageBreak(TRUE, 5);

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Start building dynamic sections
$earnings_html = '';
$total_allowances = 0;

foreach ($allowances['fixed'] as $alw) {
    $earnings_html .= '<tr><td>' . $alw->Allowance_name . ' (Fixed)</td><td class="amount">' . number_format($alw->Amount, 2) . '</td></tr>';
    $total_allowances += $alw->Amount;
}
foreach ($allowances['variable'] as $alw) {
    $earnings_html .= '<tr><td>' . $alw->Allowance_name . ' (Variable)</td><td class="amount">' . number_format($alw->Amount, 2) . '</td></tr>';
    $total_allowances += $alw->Amount;
}

$deductions_html = '';
$total_deductions = 0;

foreach ($deductions['fixed'] as $ded) {
    $deductions_html .= '<tr><td>' . $ded->Deduction_name . ' (Fixed)</td><td class="amount">' . number_format($ded->Amount, 2) . '</td></tr>';
    $total_deductions += $ded->Amount;
}
foreach ($deductions['variable'] as $ded) {
    $deductions_html .= '<tr><td>' . $ded->Deduction_name . ' (Variable)</td><td class="amount">' . number_format($ded->Amount, 2) . '</td></tr>';
    $total_deductions += $ded->Amount;
}

$html = '
<style>
    h6 {
        text-align: center;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .meta, .section-title, .field, .table, .net, .footer {
        font-size: 10px;
        margin-bottom: 5px;
    }
    .section-title {
        font-weight: bold;
        margin-top: 10px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 5px;
        text-align: left;
    }
    .table .amount {
        text-align: right;
    }
    .net {
        font-size: 12px;
        font-weight: bold;
        margin-top: 10px;
    }
    .footer {
        font-size: 8px;
        margin-top: 10px;
    }
    hr {
        border: 0;
        border-top: 1px solid #ccc;
        margin: 10px 0;
    }
</style>

<h6>' . $data_cmp[0]->Company_Name . '</h6>

<div>
    <div class="meta">
        <strong>Payslip</strong> - ' . date('F Y', mktime(0, 0, 0, $data_month, 1, $data_year)) . '<br>
        Generated on: ' . date('d M Y') . '
    </div>

    <div class="section-title">Employee Information</div>
    <div class="field"><strong>Name:</strong> ' . $data_set[0]->Emp_Full_Name . '</div>
    <div class="field"><strong>Employee ID:</strong> ' . $data_set[0]->EmpNo . '</div>
    <div class="field"><strong>Department/Branch:</strong> ' . $data_set[0]->Dep_Name . ' - ' . $data_set[0]->B_name . '</div>

    <hr>

    <div class="section-title">Earnings</div>
    <table class="table">
        <tr><th>Description</th><th class="amount">Amount (Rs.)</th></tr>
        <tr><td>Basic Salary</td><td class="amount">' . number_format($data_set[0]->Basic_sal, 2) . '</td></tr>
        ' . $earnings_html . '
        <tr><td><strong>Total Gross</strong></td><td class="amount"><strong>' . number_format($total_allowances + $data_set[0]->Basic_sal, 2) . '</strong></td></tr>
    </table>

    <hr>

    <div class="section-title">Deductions</div>
    <table class="table">
        <tr><th>Description</th><th class="amount">Amount (Rs.)</th></tr>
        ' . $deductions_html . '
        <tr><td>Salary Advance</td><td class="amount">' . number_format($data_set[0]->Salary_advance, 2) . '</td></tr>
        <tr><td>PAYE Tax</td><td class="amount">' . number_format($data_set[0]->Payee_amount, 2) . '</td></tr>
        <tr><td>EPF (8%)</td><td class="amount">' . number_format($data_set[0]->EPF_Worker_Amount, 2) . '</td></tr>
        <tr><td>Stamp Duty</td><td class="amount">' . number_format($data_set[0]->Stamp_duty, 2) . '</td></tr>
        <tr><td><strong>Total Deductions</strong></td><td class="amount"><strong>' . number_format($data_set[0]->tot_deduction, 2) . '</strong></td></tr>
    </table>

    <hr>

    <div class="section-title">Employer Contributions</div>
    <table class="table">
        <tr><th>Description</th><th class="amount">Amount (Rs.)</th></tr>
        <tr><td>EPF (12%)</td><td class="amount">' . number_format($data_set[0]->EPF_Employee_Amount, 2) . '</td></tr>
        <tr><td>ETF (3%)</td><td class="amount">' . number_format($data_set[0]->ETF_Amount, 2) . '</td></tr>
    </table>

    <hr>

    <div class="net">Net Salary: Rs. ' . number_format($data_set[0]->Net_salary, 2) . '</div>

    <div class="footer">
        This is a system-generated payslip. No signature is required.
    </div>
</div>';



// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------    
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Pay_slip_Month_' . $data_month . '.pdf', 'I');

//============================================================+
    // END OF FILE
    //============================================================+
    
