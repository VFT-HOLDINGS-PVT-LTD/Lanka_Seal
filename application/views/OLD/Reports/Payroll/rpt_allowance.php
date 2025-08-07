<?php

$date = date("Y/m/d");

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Allowance Report.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$PDF_HEADER_TITLE = $data_cmp[0]->Company_Name;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';

// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 14, '', true);
$pdf->AddPage();
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Detect if Year/Month columns exist
$has_year = false;
$has_month = false;
if (!empty($data_set)) {
    $first = $data_set[0];
    $has_year = property_exists($first, 'Year');
    $has_month = property_exists($first, 'Month');
}

$html = '
    <div style="margin-left:200px; text-align:center; font-size:13px;">ALLOWANCE REPORT</div>
    <div style="font-size: 11px; float: left; border-bottom: solid #000 1px;"></div></font><br>
    <table cellpadding="3" style="width:100%;">
        <thead style="border-bottom: #000 solid 1px;">
            <tr style="border-bottom: 1px solid black;"> 
                <th style="font-size:11px;border-bottom: 1px solid black;">EMP NO</th>
                <th style="font-size:11px;border-bottom: 1px solid black;">NAME</th>
                <th style="font-size:11px;border-bottom: 1px solid black;">ALLOWANCE TYPE</th>
                <th style="font-size:11px;border-bottom: 1px solid black;">AMOUNT</th>';
if ($has_year) {
    $html .= '<th style="font-size:11px;border-bottom: 1px solid black;">YEAR</th>';
}
if ($has_month) {
    $html .= '<th style="font-size:11px;border-bottom: 1px solid black;">MONTH</th>';
}
$html .= '
            </tr>
        </thead>
        <tbody>';

foreach ($data_set as $data) {
    $html .= '<tr>
        <td style="font-size:10px;">' . $data->EmpNo . '</td>
        <td style="font-size:10px;">' . $data->Emp_Full_Name . '</td>
        <td style="font-size:10px;">' . $data->Allowance_name . '</td>
        <td style="font-size:10px;">' . $data->Amount . '</td>';
    if ($has_year) {
        $html .= '<td style="font-size:10px;">' . (isset($data->Year) ? $data->Year : '') . '</td>';
    }
    if ($has_month) {
        $html .= '<td style="font-size:10px;">' . (isset($data->Month) ? $data->Month : '') . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</tbody>
    </table>
    <br>
';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Close and output PDF document
$pdf->Output('Allowance Report.pdf', 'I');