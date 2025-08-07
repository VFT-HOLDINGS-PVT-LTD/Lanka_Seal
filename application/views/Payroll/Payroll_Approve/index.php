<!DOCTYPE html>


<!--Description of dashboard page

@authorAshan Rathsara-->


<html lang="en">

<title><?php echo $title ?></title>

<head>
    <!-- Styles -->
<?php $this->load->view('template/css.php'); ?>
</head>

<body class="infobar-offcanvas">

    <!--header-->

    <?php $this->load->view('template/header.php'); ?>

    <!--end header-->

    <div id="wrapper">
        <div id="layout-static">

            <!--dashboard side-->

            <?php $this->load->view('template/dashboard_side.php'); ?>

            <!--dashboard side end-->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb" style="width: 160%;">

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Pay/Payroll_Approve/">PAYROLL ROW
                                    DATA</a></li>

                        </ol>


                        <div class="page-tabs" style="width: 160%;">
                            <ul class="nav nav-tabs">

                                <!-- <li><a href="<?php echo base_url(); ?>Pay/Payroll_Edit">PAYROLL ROW DATA</a> -->
                                <li class="active"><a data-toggle="tab" href="#tab2">OLD PAYROLL ROW DATA</a></li>

                                </li>

                            </ul>
                        </div>

                    </div>
                    <div class="container-fluid">


                        <div class="tab-content">

                            <!--***************************-->
                            <!-- Grid View -->
                            <div class="tab-pane active" id="tab2">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class=" col-md-12">
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Emp No</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txt_emp" id="txt_emp"
                                                        placeholder="Ex: 0001">
                                                </div>

                                            </div>
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Emp
                                                    Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="txt_emp_name"
                                                        id="txt_emp_name" placeholder="Ex: Ashan">
                                                </div>

                                            </div>
                                            <div class="col-sm-3">
                                                <label for="focusedinput" class="col-sm-4 control-label">Month</label>
                                                <div class="col-sm-8">
                                                    <select required="" class="form-control" id="cmb_month"
                                                        name="cmb_month">
                                                        <option value="">--Select--</option>
                                                        <option value="1">January</option>
                                                        <option value="2">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="mt-4">
                                                <button type="button" onclick="searchEmployee()"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                    <span>Search</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h2>PAYROLL ROW DATA</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" cellspacing="0"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>EMP NO</th>
                                                                <th>NAME</th>
                                                                <th>BASIC SALARY</th>
                                                                <th>BR</th>
                                                                <th style="color:#dd3232;">TOTAL FOR EPF</th>
                                                                <th>FIXED</th>
                                                                <th>INCENTIVE</th>
                                                                <th>FUEL</th>
                                                                <th>TRAVELLING </th>
                                                                <th>EXTRA PAYMENT</th>
                                                                <th>ALLOWANCE I</th>
                                                                <th>ALLOWANCE II</th>
                                                                <th>OT HOURS</th>
                                                                <th>OT PAY</th>
                                                                <th>ED MINUTES</th>
                                                                <th>LATE MINUTES</th>
                                                                <th style="color:#dd3232;">GROSS PAY</th>
                                                                <th>LATE DEDUCTION</th>
                                                                <th>ED DEDUCTION</th>
                                                                <th>ADV. PAID</th>
                                                                <th>PAYEE</th>
                                                                <th>LOAN</th>
                                                                <th>STAMP D.</th>
                                                                <th>TRANSPORT</th>
                                                                <th>DEDUCTION II</th>
                                                                <th>DEDUCTION III</th>
                                                                <th>DEDUCTION IV</th>
                                                                <th>DEDUCTION V</th>
                                                                <th>NO PAY</th>
                                                                <th style="color:#dd3232;">TOT DEDUCTION</th>
                                                                <th>EPF 8%</th>
                                                                <th style="color:rgb(6 143 6);">NET SALARY</th>
                                                                <th>EPF 12%</th>
                                                                <th>ETF 3%</th>
                                                                <th>Action</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Table data goes here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- End Grid View-->


                        </div>





                    </div> <!-- .container-fluid -->
                </div>
                <!--Footer-->
                <?php $this->load->view('template/footer.php'); ?>
                <!--End Footer-->
            </div>
        </div>
    </div>



    <!-- Load site level scripts -->

    <?php $this->load->view('template/js.php'); ?>
    <!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->

    <!--Ajax-->
    <script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>

    <script type="text/javascript">
        $(function () {
            $("#txt_emp_name").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_name"
            });
        });

        $(function () {
            $("#txt_emp").autocomplete({
                source: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_In_Out/get_auto_emp_no"
            });
        });
    </script>

    <script>
        // Attach event listeners to all Edit buttons
        function attachEditButtonListeners() {
            document.querySelectorAll('.edit-btn').forEach((button) => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr'); // Get the current row
                    // Update the row cells
                    Array.from(row.cells).forEach((cell, index) => {
                        if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21,
                                22].includes(index)) {
                            const input = cell.querySelector('input');
                            if (input) {
                                cell.textContent = input.value; // Update cell content
                            }
                        }
                    });

                    // Collect updated data from the row
                    const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

                    // Send updated data to the server
                    fetch('<?php echo base_url(); ?>Pay/Payroll_Approve/edit_data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rowData
                            }), // Send the updated row data
                        })
                        .then((response) => response.json()) // Parse the JSON response
                        .then((data) => {
                            console.log(data);
                            // if (data.status === 'success') {
                            //     alert('Row updated successfully');
                            //     window.location.reload('<?php echo base_url(); ?>Pay/Payroll_Approve');
                            // } else {
                            //     alert('Error: ' + data.message);
                            // }
                        })
                        .catch((error) => {
                            console.error('Error updating row:', error);
                            alert('An unexpected error occurred: ' + error.message);
                        });
                });
            });
        }
        //////////////////////////reject approve
        function attachRejectButtonListeners() {
            document.querySelectorAll('.reject-btn').forEach((button) => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr'); // Get the current row
                    // Update the row cells
                    Array.from(row.cells).forEach((cell, index) => {
                        if (index < row.cells.length - 1 && ![0, 1, 2, 5, 13, 18, 19, 20, 21,
                                22].includes(index)) {
                            const input = cell.querySelector('input');
                            if (input) {
                                cell.textContent = input.value; // Update cell content
                            }
                        }
                    });

                    // Collect updated data from the row
                    const rowData = Array.from(row.cells).map((cell) => cell.textContent.trim());

                    // Send updated data to the server
                    fetch('<?php echo base_url(); ?>Pay/Payroll_Approve/reject_data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rowData
                            }), // Send the updated row data
                        })
                        .then((response) => response.json()) // Parse the JSON response
                        .then((data) => {
                            if (data.status === 'success') {
                                alert('Rejected');
                                window.location.reload(
                                    '<?php echo base_url(); ?>Pay/Payroll_Approve');
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error('Error updating row:', error);
                            alert('An unexpected error occurred: ' + error.message);
                        });
                });
            });
        }
        //////////////////////////reject approve


        // Function to live update Total_F_Epf, Gross_pay, DEDUCTION, and Net Salary
        function liveUpdate(row) {
            const cells = Array.from(row.cells);
            const basicSal = parseFloat(cells[3].querySelector('input') ? .value.trim()) || 0;
            const brPay = parseFloat(cells[4].querySelector('input') ? .value.trim()) || 0;

            // Update Total_F_Epf
            const totalFEpfCell = cells[5];
            totalFEpfCell.textContent = (basicSal + brPay).toFixed(2);

            // Update Gross_pay
            const fixed = parseFloat(cells[6].querySelector('input') ? .value.trim()) || 0;
            const performance = parseFloat(cells[7].querySelector('input') ? .value.trim()) || 0;
            const attendance = parseFloat(cells[8].querySelector('input') ? .value.trim()) || 0;
            const transport = parseFloat(cells[9].querySelector('input') ? .value.trim()) || 0;
            const fuel = parseFloat(cells[10].querySelector('input') ? .value.trim()) || 0;
            const traveling = parseFloat(cells[11].querySelector('input') ? .value.trim()) || 0;
            const spAllowance = parseFloat(cells[12].querySelector('input') ? .value.trim()) || 0;

            const grossPayCell = cells[13];
            grossPayCell.textContent = (
                basicSal +
                brPay +
                fixed +
                performance +
                attendance +
                transport +
                fuel +
                traveling +
                spAllowance
            ).toFixed(2);

            // Update DEDUCTION
            const Late_deduction = parseFloat(cells[14].querySelector('input') ? .value.trim()) || 0;
            const Ed_deduction = parseFloat(cells[15].querySelector('input') ? .value.trim()) || 0;
            const Salary_advance = parseFloat(cells[16].querySelector('input') ? .value.trim()) || 0;
            const no_pay_deduction = parseFloat(cells[17].querySelector('input') ? .value.trim()) || 0;

            const deductionCell = cells[18];
            deductionCell.textContent = (
                Late_deduction +
                Ed_deduction +
                Salary_advance +
                no_pay_deduction
            ).toFixed(2);

            // Update Net Salary
            const D_Salary = parseFloat(deductionCell.textContent) || 0;
            const Net_salary = parseFloat(grossPayCell.textContent) - D_Salary;

            const netSalaryCell = cells[20];
            netSalaryCell.textContent = Net_salary.toFixed(2);

            const epfdata1 = cells[19];
            const result = basicSal / 100 * 8;
            epfdata1.textContent = result.toFixed(2);

            const epfdata2 = cells[21];
            const result2 = basicSal / 100 * 12;
            epfdata2.textContent = result2.toFixed(2);

            const etfdata1 = cells[22];
            const result3 = basicSal / 100 * 3;
            etfdata1.textContent = result3.toFixed(2);
        }

        function searchEmployee() {
            // Get input values
            const empNo = document.getElementById("txt_emp").value.trim();
            const empName = document.getElementById("txt_emp_name").value.trim();
            const month = document.getElementById("cmb_month").value;

            // Prepare data to send as JSON
            const searchData = {
                "empNo": empNo,
                "empName": empName,
                "month": month,
            };

            // Send the data to CodeIgniter controller via fetch
            fetch("<?php echo base_url(); ?>Pay/Payroll_Approve/Payroll_Search", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json' // Set content type to JSON
                    },
                    body: JSON.stringify(searchData) // Convert the data to a JSON string
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // Parse the response as JSON
                })
                .then(data => {
                    console.log("Controller Response:", data); // Log the JSON response
                    if (data.status && data.status === "No data found") {
                        alert("No records found matching your search criteria.");
                    } else {
                        updateTable(data); // Call function to update the table with new data
                    }
                })
                .catch(error => {
                    console.error("Error fetching search results:", error);
                    alert("Failed to retrieve search results. Please try again.");
                });
        }

        function updateTable(data) {
            const tableBody = document.querySelector("table tbody");
            tableBody.innerHTML = ""; // Clear current table rows

            // Insert new rows from the received data
            data.forEach(row => {
                const newRow = `
            <tr class='odd gradeX' style="height: 50px;">
                <td>${row.ID || '-'}</td>
                <td>${row.EmpNo || '-'}</td>
                <td>${row.Emp_Full_Name || '-'}</td>
                <td>${row.Basic_sal || '-'}</td>
                <td>${row.Br_pay || '-'}</td>
                <td style="color:#dd3232;">${row.Total_F_Epf || '-'}</td>
                <td>${row.Fixed_Allowance || '-'}</td>
                <td>${row.Allowance_1 || '-'}</td>
                <td>${row.Allowance_2 || '-'}</td>
                <td>${row.Allowance_3 || '-'}</td>
                <td>${row.Allowance_4 || '-'}</td>
                <td>${row.Allowance_5 || '-'}</td>
                <td>${row.Allowance_6 || '-'}</td>
                <td>${row.Normal_OT_Hrs || '-'}</td>
                <td>${row.Normal_OT_Pay || '-'}</td>
                <td>${row.Ed_min || '-'}</td>
                <td>${row.Late_min || '-'}</td>
                <td style="color:#dd3232;">${row.Gross_pay || '-'}</td>
                <td>${row.Late_deduction || '-'}</td>
                <td>${row.Ed_deduction || '-'}</td>
                <td>${row.Salary_advance || '-'}</td>
                <td>${row.Payee_amount || '-'}</td>
                <td>${row.Loan_Instalment || '-'}</td>
                <td>${row.Stamp_duty || '-'}</td>
                <td>${row.Deduct_1 || '-'}</td>
                <td>${row.Deduct_2 || '-'}</td>
                <td>${row.Deduct_3 || '-'}</td>
                <td>${row.Deduct_4 || '-'}</td>
                <td>${row.Deduct_5 || '-'}</td>
                <td>${row.no_pay_deduction || '-'}</td>
                <td style="color:#dd3232;">${row.tot_deduction || '-'}</td>
                <td>${row.EPF_Worker_Amount || '-'}</td>
                <td style="color:rgb(6, 143, 6);">${row.Net_salary || '-'}</td>
                <td>${row.EPF_Employee_Amount || '-'}</td>
                <td>${row.ETF_Amount || '-'}</td>
                <td style="width:80px; text-align:right;font-weight:bold">
                    <button class="edit-btn btn btn-green">APPROVE</button>
                </td>
                <td style="width:80px; text-align:right;font-weight:bold">
                <button class="reject-btn btn btn-danger">REJECT</button>
                </td>
            </tr>`;

                tableBody.insertAdjacentHTML("beforeend", newRow);
            });

            // After the table is updated, attach event listeners to the Edit buttons
            attachEditButtonListeners();
            attachRejectButtonListeners();
        }
    </script>
    <script>

    </script>
</body>


</html>