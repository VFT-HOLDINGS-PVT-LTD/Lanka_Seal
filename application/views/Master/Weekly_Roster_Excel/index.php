<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/css.php'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Main container */
        .upload-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            /* font-family: "Poppins", sans-serif; */
        }

        /* Label for title */
        .upload-label {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
            color: #333;
        }

        /* Hint text */
        .upload-hint {
            color: #777;
            margin-bottom: 10px;
            font-size: 11px;
        }

        /* Box to hold file input, file name, and button */
        .upload-box {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 8px 12px;
            background: #f9f9f9;
            width: 100%;
            max-width: 450px;
            transition: 0.3s ease-in-out;
            margin-top: 7px;
        }

        /* Custom file label */
        .custom-file-label {
            background: #8bc34a;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Hide default input */
        .upload-box input {
            display: none;
        }

        /* File name text */
        .file-name {
            flex-grow: 1;
            color: #444;
            font-size: 0.95rem;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Upload button (on the same line) */
        .upload-btn {
            background: #8bc34a;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.3s;
        }

        /* Hover effects */
        .upload-btn:hover,
        .custom-file-label:hover {
            background: rgb(120, 168, 64);
        }

        /* Interactive effect when box is active */
        .upload-box:hover {
            border-color: #8bc34a;
        }
    </style>
</head>

<body class="infobar-offcanvas">
    <?php $this->load->view('template/header.php'); ?>
    <div id="wrapper">
        <div id="layout-static">
            <?php $this->load->view('template/dashboard_side.php'); ?>
            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li><a href="">HOME</a></li>
                            <li class="active"><a href="">MONTHLY ROSTER PATTERN EXCEL</a></li>
                        </ol>
                        <div class="container-fluid">
                            <div class="tab-pane active" id="tab1">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <h2>DOWNLOAD / UPLOAD MONTHLY ROSTER PATTERNS</h2>
                                                    </div>
                                                    <div class="panel-body">
                                                        <form class="form-horizontal" id="frm_weekly_roster"
                                                            action="<?php echo base_url(); ?>Master/Weekly_Roster_Excel/download_excel"
                                                            method="POST">
                                                            <?php if ($this->session->flashdata('success')): ?>
                                                            <div id="spnmessage" class="alert alert-success">
                                                                <strong>Success!</strong>
                                                                <?php echo $this->session->flashdata('success'); ?>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="form-group col-sm-12">
                                                                <div class="col-sm-8">
                                                                    <img class="imagecss"
                                                                        src="https://i.pinimg.com/originals/f9/85/78/f98578a4f210b726dfea429f68c0c05b.gif"
                                                                        style="transform: scale(1.5);">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <div class="form-group col-sm-4">
                                                                    <label class="col-sm-4 control-label">Roster
                                                                        Code</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" readonly
                                                                            value="<?php echo $serial; ?>"
                                                                            class="form-control" id="txtRoster_Code"
                                                                            name="txtRoster_Code">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label class="col-sm-4 control-label">Select
                                                                        Month</label>
                                                                    <div class="col-sm-8">
                                                                        <select id="txtMType" name="txt_MType"
                                                                            class="form-control" required>
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                                $months = [
                                                                                    "January", "February", "March", "April", "May", "June",
                                                                                    "July", "August", "September", "October", "November", "December"
                                                                                ];
                                                                                foreach ($months as $month) {
                                                                                    echo "<option value=\"$month\">$month</option>";
                                                                                }
                                                                                ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-sm-4">
                                                                    <label
                                                                        class="col-sm-4 control-label">Category</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control" required
                                                                            id="cmb_cat" name="cmb_cat">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="Individual Employee">
                                                                                Individual Employee</option>
                                                                            <option value="OnlyGroup">Only Group
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div id="dynamic-fields"></div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-sm-11 text-right">
                                                                    <button type="submit" class="btn btn-success"
                                                                        title="Download Excel">
                                                                        <i class="fa fa-file-excel-o"></i> Download
                                                                        Excel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <hr>

                                                        <div class="upload-container">
                                                            <form id="uploadForm" enctype="multipart/form-data"
                                                                style="margin-left: 10px;" method="post"
                                                                action="<?php echo base_url('Master/Weekly_Roster_Excel/upload_excel'); ?>">
                                                                <label class="upload-label">Upload Excel File
                                                                    <span class="upload-hint">(Select Excel File (.xlsx,
                                                                        .xls, .csv))</span>
                                                                </label>
                                                                <div class="upload-box">
                                                                    <input type="file" name="upload_excel"
                                                                        id="upload_excel" accept=".xlsx,.xls,.csv"
                                                                        required>
                                                                    <label for="upload_excel" class="custom-file-label">
                                                                        <i class="fa fa-file-excel-o"></i> Choose Excel
                                                                        File
                                                                    </label>
                                                                    <span class="file-name" id="file-name-label">No file
                                                                        chosen</span>
                                                                    <button type="submit" class="upload-btn"
                                                                        title="Upload Excel">
                                                                        <i class="fa fa-upload"></i> Upload
                                                                    </button>
                                                                </div>
                                                            </form>

                                                            <!-- Conflict Modal -->
                                                            <?php $conflicts = $this->session->flashdata('conflicts'); ?>
                                                            <?php if (!empty($conflicts)): ?>
                                                            <div class="modal show d-block" tabindex="-1" role="dialog"
                                                                id="conflictModal">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title">Conflicting Records
                                                                                Found</h5>
                                                                        </div>
                                                                        <div class="modal-body"
                                                                            style="max-height: 60vh; overflow-y: auto;">
                                                                            <p>The following records already exist. Do
                                                                                you want to replace them?</p>
                                                                            <form method="post"
                                                                                action="<?= base_url('Master/Weekly_Roster_Excel/replace_conflicts') ?>">
                                                                                <input type="hidden" name="rows"
                                                                                    value='<?= htmlspecialchars(json_encode($conflicts), ENT_QUOTES, 'UTF-8') ?>'>
                                                                                <div class="table-responsive">
                                                                                    <table
                                                                                        class="table table-bordered table-sm">
                                                                                        <thead class="thead-dark">
                                                                                            <tr>
                                                                                                <th>RosterCode</th>
                                                                                                <th>RosterName</th>
                                                                                                <th>Date</th>
                                                                                                <th>ShiftCode</th>
                                                                                                <th>DayName</th>
                                                                                                <th>ShiftType</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php foreach ($conflicts as $row): ?>
                                                                                            <tr>
                                                                                                <td><?= htmlspecialchars($row['RosterCode']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['RosterName']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['Date']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['ShiftCode']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['DayName']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['ShiftType']) ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php endforeach; ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit"
                                                                                        class="btn btn-success">Replace</button>
                                                                                    <a href="<?= base_url('Master/Weekly_Roster_Excel') ?>"
                                                                                        class="btn btn-danger">Cancel</a>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <!-- Non-Conflict Modal -->
                                                            <?php $non_conflicts = $this->session->flashdata('non_conflicts'); ?>
                                                            <?php if (!empty($non_conflicts)): ?>
                                                            <div class="modal show d-block" tabindex="-1" role="dialog"
                                                                id="nonConflictModal">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-info text-white">
                                                                            <h5 class="modal-title">Insert New Records
                                                                            </h5>
                                                                        </div>
                                                                        <div class="modal-body"
                                                                            style="max-height: 60vh; overflow-y: auto;">
                                                                            <p>The following records do not exist. Do
                                                                                you want to insert them?</p>
                                                                            <form method="post"
                                                                                action="<?= base_url('Master/Weekly_Roster_Excel/insert_non_conflicts') ?>">
                                                                                <input type="hidden" name="rows"
                                                                                    value='<?= htmlspecialchars(json_encode($non_conflicts), ENT_QUOTES, 'UTF-8') ?>'>
                                                                                <div class="table-responsive">
                                                                                    <table
                                                                                        class="table table-bordered table-sm">
                                                                                        <thead class="thead-dark">
                                                                                            <tr>
                                                                                                <th>RosterCode</th>
                                                                                                <th>RosterName</th>
                                                                                                <th>Date</th>
                                                                                                <th>ShiftCode</th>
                                                                                                <th>DayName</th>
                                                                                                <th>ShiftType</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php foreach ($non_conflicts as $row): ?>
                                                                                            <tr>
                                                                                                <td><?= htmlspecialchars($row['RosterCode']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['RosterName']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['Date']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['ShiftCode']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['DayName']) ?>
                                                                                                </td>
                                                                                                <td><?= htmlspecialchars($row['ShiftType']) ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php endforeach; ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Insert</button>
                                                                                    <a href="<?= base_url('Master/Weekly_Roster_Excel') ?>"
                                                                                        class="btn btn-secondary">Cancel</a>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <script>
                                                                document.getElementById('upload_excel')
                                                                    .addEventListener('change', function () {
                                                                        const fileNameLabel = document
                                                                            .getElementById('file-name-label');
                                                                        fileNameLabel.textContent = this.files
                                                                            .length > 0 ? this.files[0].name :
                                                                            'No file chosen';
                                                                    });
                                                            </script>
                                                        </div>


                                                        <script>
                                                            document.getElementById('upload_excel').addEventListener(
                                                                'change',
                                                                function (e) {
                                                                    const fileName = e.target.files.length ? e
                                                                        .target.files[0].name : 'No file chosen';
                                                                    document.getElementById('file-name-label')
                                                                        .textContent = fileName;
                                                                });
                                                        </script>

                                                        <?php if ($this->session->flashdata('success')): ?>
                                                        <div class="alert alert-success" id="spnmessage">
                                                            <strong>Success!</strong>
                                                            <?php echo $this->session->flashdata('success'); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($this->session->flashdata('error')): ?>
                                                        <div class="alert alert-danger" id="spnmessage">
                                                            <strong>Error!</strong>
                                                            <?php echo $this->session->flashdata('error'); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($this->session->flashdata('warning')): ?>
                                                        <div class="alert alert-warning" id="spnmessage">
                                                            <strong>Warning!</strong>
                                                            <?php echo $this->session->flashdata('warning'); ?>
                                                        </div>
                                                        <?php endif; ?>

                                                        <div id="divmessage">
                                                            <div id="spnmessage"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- .container-fluid -->
                        </div>
                        <?php $this->load->view('template/footer.php'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('template/js.php'); ?>
        <script src="<?php echo base_url(); ?>system_js/Master/Weekly_Roster.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script>
            $(function () {
                function initSelect2() {
                    $('.itemName').select2({
                        placeholder: '--- Find ---',
                        ajax: {
                            url: "<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/search",
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                }
                $('#cmb_cat').on('change', function () {
                    var selectedValue = $(this).val();
                    var dynamicFields = $('#dynamic-fields');
                    dynamicFields.empty();
                    if (selectedValue === 'Individual Employee') {
                        dynamicFields.html(`
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">Emp Number</label>
                            <div class="col-sm-8">
                                <select required class="form-control itemName" name="txt_nic" id="txt_nic"></select>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">Selected Emp Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="txt_emp_name" name="txt_emp_name" placeholder="Selected Emp Name" readonly>
                            </div>
                        </div>
                    `);
                        initSelect2();
                        $('#txt_nic').on('change', function () {
                            var empNo = $(this).val();
                            if (empNo) {
                                $.get('<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/get_mem_data/' +
                                    empNo,
                                    function (data) {
                                        if (data.length > 0) {
                                            $('#txt_emp_name').val(data[0].Emp_Full_Name);
                                        }
                                    }, 'json');
                            }
                        });
                    } else if (selectedValue === 'OnlyGroup') {
                        dynamicFields.html(`
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">Roster Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="txtRoster_Name" name="txtRoster_Name" placeholder="Ex: Office">
                            </div>
                        </div>
                    `);
                    }
                });
                $("#cmb_cat").trigger("change");
                $("#frm_weekly_roster").validate();
                $("#spnmessage").hide("shake", {
                    times: 4
                }, 1500);
            });
        </script>

</body>

</html>