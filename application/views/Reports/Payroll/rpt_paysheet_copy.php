<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Sheet</title>
    <style>
        @media print {
            .page-break {
                page-break-before: always;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid black;
            font-size: 8px;
            padding: 5px;
        }
        th {
            font-weight: bold;
            border-top: 1px solid black;
        }
        td {
            text-align: right;
        }
        td:first-child, td:nth-child(2), td:nth-child(3) {
            text-align: left;
        }
    </style>
</head>
<body>
    <h6 style="text-align:center; margin: 0;">PAY SHEET</h6>
    <div style="font-size: 11px; border-bottom: 1px solid #000; margin-bottom: 10px;">
        Year: <?= htmlspecialchars($data_year) ?> &nbsp; 
        Month: <?= date('F', mktime(0, 0, 0, $data_month)) ?>
    </div>
    <button id="exportBtn">Export to Excel</button>

    <table cellpadding="6" id="payrollTable" border="1">
        <thead>
            <tr>
                <th>EMP NO</th>
                <th>BRANCH</th>
                <th>DEP NAME</th>
                <th>NAME</th>
                <th>BASIC SALARY</th>
                <th>BR</th>
                <th>TOTAL FOR EPF</th>
                <th>PERFORMANCE</th>
                <th>ATTENDANCES</th>
                <th>TRANSPORT</th>
                <th>FUEL</th>
                <th>TRAVELING</th>
                <th>SPALLOWANCE</th>
                <th>INCREMENT</th>
                <th>OTHER OT</th>
                <th>GROSS PAY</th>
                <th>ADV. PAID</th>
                <th>P.A.Y.E</th>
                <th>LATE</th>
                <th>ED</th>
                <th>NO PAY</th>
                <th>EPF 8%</th>
                <th>TOT DEDUCTION</th>
                <th>NET SALARY</th>
                <th>EPF 12%</th>
                <th>ETF 3%</th>
                <th>BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data_set)) : ?>
                <?php foreach ($data_set as $row) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row->EmpNo) ?></td>
                        <td><?= htmlspecialchars($row->B_name) ?></td>
                        <td><?= htmlspecialchars($row->Dep_Name) ?></td>
                        <td><?= htmlspecialchars($row->Emp_Full_Name) ?></td>
                        <td><?= number_format($row->Basic_sal, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Br_pay, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Total_F_Epf, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Performance, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Attendances, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Transport, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Fuel, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Traveling, 2, '.', ',') ?></td>
                        <td><?= number_format($row->SPAllowance, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Increment, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Other_OT, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Gross_pay, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Salary_advance, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Payee_amount, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Late_deduction, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Ed_deduction, 2, '.', ',') ?></td>
                        <td><?= number_format($row->no_pay_deduction, 2, '.', ',') ?></td>
                        <td><?= number_format($row->EPF_Worker_Amount, 2, '.', ',') ?></td>
                        <td><?= number_format($row->tot_deduction, 2, '.', ',') ?></td>
                        <td><?= number_format($row->Net_salary, 2, '.', ',') ?></td>
                        <td><?= number_format($row->EPF_Employee_Amount, 2, '.', ',') ?></td>
                        <td><?= number_format($row->ETF_Amount, 2, '.', ',') ?></td>
                        <td><?= number_format($row->D_Salary, 2, '.', ',') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="27" style="text-align: center;">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        document.getElementById('exportBtn').addEventListener('click', function () {
            // Get the table
            var table = document.getElementById('payrollTable');

            // Convert table to a workbook
            var workbook = XLSX.utils.table_to_book(table, { sheet: "Payroll Data" });

            // Export workbook to Excel
            XLSX.writeFile(workbook, 'Payroll_Report.xlsx');
        });
    </script>
</body>
</html>
