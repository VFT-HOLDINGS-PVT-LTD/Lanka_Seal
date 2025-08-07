<?php
$date = date("Y/m/d");


//var_dump($data_c[0]->id);die;


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Payee Tax Report.pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


$PDF_HEADER_TITLE = $data_cmp[0]->EmpGroupName;
$PDF_HEADER_LOGO_WIDTH = '0';
$PDF_HEADER_LOGO = '';
$PDF_HEADER_STRING = '';

// set default header data
$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE . '', $PDF_HEADER_STRING, array(0, 64,
255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
$pdf->SetFont('helvetica', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.0, 'depth_h' => 0.0, 'color' => array(196, 196, 196),
'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
$html = '
<div style="margin-left:200px; text-align:center; font-size:13px;">PAYEE TAX REPORT</div>
<div style="font-size: 11px; float: left; border-bottom: solid #000 1px;">Year : '.$year.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generated on : '.$generated_date.'</div>
<br>
<table cellpadding="3" style="border-collapse: collapse; width: 100%;">
    <thead style="border-bottom: 1px solid #000;">
        <tr>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:100px">EMP NO</th>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:180px;">EMP NAME</th>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:90px;">NIC NO</th>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:90px;">DESIGNATION</th>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:90px;">GROSS SALARY</th>
            <th style="font-size:11px; border-bottom: 1px solid #000; text-align: left;width:90px;">PAYEE TAX DEDUCTED</th>
        </tr>
    </thead>
    <tbody>';
        $i = 0;
        foreach ($data_set as $data) {
        $i++;
        $html .= '<tr>
            <td style="font-size:10px;width:100px;">' . $data->EmpNo . '</td>
            <td style="font-size:10px;width:180px;">' . $data->Emp_Full_Name . '</td>
            <td style="font-size:10px;width:90px;">' . $data->NIC . '</td>
            <td style="font-size:10px;width:90px;">' . $data->Desig_Name . '</td>
            <td style="font-size:10px;width:90px;">' . $data->Gross_Salary . '</td>
            <td style="font-size:10px;width:90px;">' . number_format($data->Payee_Amount, 0, '.', ',') . '</td>
        </tr>'
        ;
        }
        $html .= '<div style="font-size:11px; font-weight:bold; text-align:left; margin-top:10px;margin-right:10px;">
            Total Records: ' . $i . '
          </div><br>';



        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('Payee Tax Report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+