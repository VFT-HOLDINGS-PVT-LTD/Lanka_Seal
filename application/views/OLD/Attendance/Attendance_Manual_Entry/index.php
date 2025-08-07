<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">

<title>
    <?php echo $title ?>
</title>

<head>
    <!-- Styles -->
<?php $this->load->view('template/css.php'); ?>
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
                        <ol class="breadcrumb">

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <!--<li class="active"><a href="<?php echo base_url(); ?>Master/Designation/">EMPLOYEE</a></li>-->

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">MANUAL ATTENDANCE</a></li>
                                <li><a data-toggle="tab" href="#tab_upload">MANUAL ATTENDANCE UPLOAD</a>
                                </li>
                                <li><a data-toggle="tab" href="#tab2">VIEW MANUAL ATTENDANCE</a></li>


                            </ul>
                        </div>
                        <div class="container-fluid">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <h2>MANUAL ATTENDANCE</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_employee_view"
                                                                name="frm_employee_view"
                                                                action="<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/emp_manual_entry"
                                                                method="POST">

                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                <div id="spnmessage"
                                                                    class="alert alert-dismissable alert-success">
                                                                    <strong>Success !</strong>
                                                                    <?php echo $_SESSION['success_message'] ?>
                                                                </div>
                                                                <?php } ?>


                                                                <div class="from-group col-md-12">
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Emp
                                                                            No</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="txt_emp" id="txt_emp"
                                                                                placeholder="Ex: 0001">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Emp
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="txt_emp_name" id="txt_emp_name"
                                                                                placeholder="Ex: Ashan">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <!--                                                                    <div class="form-group col-sm-3">
                                                                                                                                            <label for="focusedinput" class="col-sm-4 control-label">Designation</label>
                                                                                                                                            <div class="col-sm-8">
                                                                                                                                                <select class="form-control" id="cmb_desig" name="cmb_desig" >
                                                                    
                                                                    
                                                                                                                                                    <option value="" default>-- Select --</option>
                                                                    <?php foreach ($data_desig as $t_data) { ?>
                                                                                                                                                                        <option value="<?php echo $t_data->Des_ID; ?>" ><?php echo $t_data->Desig_Name; ?></option>
                                                                                    
                                                                    <?php }
                                                                    ?>
                                                                    
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                    
                                                                                                                                        </div>
                                                                                                                                        <div class="form-group col-sm-3">
                                                                                                                                            <label for="focusedinput" class="col-sm-4 control-label">Department</label>
                                                                                                                                            <div class="col-sm-8">
                                                                                                                                                <select class="form-control" id="cmb_dep" name="cmb_dep" >
                                                                    
                                                                    
                                                                                                                                                    <option value="" default>-- Select --</option>
                                                                    <?php foreach ($data_dep as $t_data) { ?>
                                                                                                                                                                        <option value="<?php echo $t_data->Dep_ID; ?>" ><?php echo $t_data->Dep_Name; ?></option>
                                                                                    
                                                                    <?php }
                                                                    ?>
                                                                    
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                    
                                                                                                                                        </div>
                                                                                                                                        <div class="form-group col-sm-3">
                                                                                                                                            <label for="focusedinput" class="col-sm-4 control-label">Company</label>
                                                                                                                                            <div class="col-sm-8">
                                                                                                                                                <select class="form-control"  id="cmb_comp" name="cmb_comp" >
                                                                    
                                                                    
                                                                                                                                                    <option value="" default>-- Select --</option>
                                                                    <?php foreach ($data_cmp as $t_data) { ?>
                                                                                                                                                                        <option value="<?php echo $t_data->Cmp_ID; ?>" ><?php echo $t_data->Company_Name; ?></option>
                                                                                    
                                                                    <?php }
                                                                    ?>
                                                                    
                                                                                                                                                </select>
                                                                                                                                            </div>
                                                                    
                                                                                                                                        </div>-->

                                                                <div class="from-group col-md-12">
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Date</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                required="" name="att_date"
                                                                                id="att_date"
                                                                                placeholder="Ex: Select Date">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">
                                                                            Time</label>
                                                                        <div class="col-sm-8">
                                                                            <input type=time class="form-control"
                                                                                required="" name="in_time" id="in_time"
                                                                                placeholder="Ex: Select Date">
                                                                        </div>

                                                                    </div>
                                                                    <!-- <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Out
                                                                            Time</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="time" class="form-control"
                                                                                required="" name="out_time"
                                                                                id="out_time"
                                                                                placeholder="Ex: Select Date">
                                                                        </div>

                                                                    </div> -->
                                                                </div>.
                                                                <div class="from-group col-md-12">
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Reason</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                required="" name="txt_reason"
                                                                                id="txt_reason"
                                                                                placeholder="Ex: Enter Reason">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-4">
                                                                        <label
                                                                            class="col-sm-4 control-label">Status</label>
                                                                        <div class="col-sm-8">
                                                                            <label class="radio-inline icheck">
                                                                                <input type="radio" id="inlineradio1"
                                                                                    required="" value="Active"
                                                                                    name="employee_status">Check In
                                                                            </label>
                                                                            <label class="radio-inline icheck">
                                                                                <input type="radio" id="inlineradio2"
                                                                                    value="Inactive"
                                                                                    name="employee_status">
                                                                                Check Out
                                                                            </label>
                                                                        </div>

                                                                    </div>
                                                                </div>


                                                                <!--submit button-->
                                                                <?php $this->load->view('template/btn_submit.php'); ?>
                                                                <!--end submit-->


                                                            </form>



                                                            <hr>

                                                            <div id="divmessage" class="">

                                                                <div id="spnmessage"> </div>
                                                            </div>


                                                            <div id="search_body">

                                                            </div>


                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane" id="tab_upload">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <h2>MANUAL ATTENDANCE UPLOAD</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_employee_view"
                                                                name="frm_employee_view"
                                                                action="<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/download_sample"
                                                                method="POST">

                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                <div id="spnmessage"
                                                                    class="alert alert-dismissable alert-success">
                                                                    <strong>Success !</strong>
                                                                    <?php echo $_SESSION['success_message'] ?>
                                                                </div>
                                                                <?php } ?>


                                                                <div class="form-group col-md-12">
                                                                    <!-- <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Emp No</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" name="txt_emp" id="txt_emp" placeholder="Ex: 0001">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Emp Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" name="txt_emp_name" id="txt_emp_name" placeholder="Ex: Ashan">
                                                                        </div>

                                                                    </div> -->
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Designation</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_desig"
                                                                                name="cmb_desig">

                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_desig as $t_data) { ?>
                                                                                <option
                                                                                    value="<?php echo $t_data->Des_ID; ?>">
                                                                                    <?php echo $t_data->Desig_Name; ?>
                                                                                </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Department</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_dep"
                                                                                name="cmb_dep">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_dep as $t_data) { ?>
                                                                                <option
                                                                                    value="<?php echo $t_data->Dep_ID; ?>">
                                                                                    <?php echo $t_data->Dep_Name; ?>
                                                                                </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>


                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Branch</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_branch"
                                                                                name="cmb_branch">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_branch as $t_data) { ?>
                                                                                <option
                                                                                    value="<?php echo $t_data->B_id; ?>">
                                                                                    <?php echo $t_data->B_name; ?>
                                                                                </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>


                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Company</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="cmb_comp"
                                                                                name="cmb_comp">


                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_cmp as $t_data) { ?>
                                                                                <option
                                                                                    value="<?php echo $t_data->Cmp_ID; ?>">
                                                                                    <?php echo $t_data->Company_Name; ?>
                                                                                </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">From
                                                                            Date</label>
                                                                        <div class="col-sm-8">


                                                                            <input type="text" class="form-control"
                                                                                required="" id="att_date_1"
                                                                                name="txt_from_date"
                                                                                placeholder="Select Date">


                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group col-sm-6" style="">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">To
                                                                            Date</label>
                                                                        <div class="col-sm-8">


                                                                            <input type="text" class="form-control"
                                                                                required="" id="to_date"
                                                                                name="txt_to_date"
                                                                                placeholder="Select Date">


                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <button type="submit" class="btn btn-success">Download
                                                                    Format</button>
                                                                <!--submit button-->

                                                                <!--end submit-->


                                                            </form>



                                                            <hr>

                                                            <div class="upload-container">
                                                                <!-- <h4 style="font-weight: bold;">Upload Excel File</h4> -->
                                                                <form id="uploadForm" enctype="multipart/form-data"
                                                                    style="margin-left: 10px;">
                                                                    <div class="upload-container">
                                                                        <label class="upload-label">Upload Excel File
                                                                            <span class="upload-hint">(Select Excel File
                                                                                (.xlsx))</span>
                                                                        </label>
                                                                        <div class="upload-box">
                                                                            <label for="upload_excel"
                                                                                class="custom-file-label">Choose
                                                                                File</label>
                                                                            <input type="file" name="upload_excel"
                                                                                id="upload_excel" accept=".xlsx"
                                                                                required>
                                                                            <span class="file-name">No file
                                                                                chosen</span>
                                                                            <button type="submit"
                                                                                class="upload-btn">Upload</button>
                                                                        </div>
                                                                    </div>
                                                                    <div id="loading"
                                                                        style="display: none; text-align: center; padding: 10px; font-size: 16px; color: #00acc1;">
                                                                        Uploading, please wait...</div>
                                                                    <div id="message"
                                                                        style="text-align: center; padding: 10px; font-size: 16px;">
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div id="divmessage" class="">

                                                                <div id="spnmessage"> </div>
                                                            </div>


                                                            <div id="search_body">

                                                            </div>


                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>


                                <!--***************************-->
                                <div class="tab-pane" id="tab2">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h2>ATTENDANCE DEVICE</h2>
                                                        </div>
                                                        <div class="panel-body panel-no-padding">
                                                            <table id="example"
                                                                class="table table-striped table-bordered"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>

                                                                        <th>EMP NO</th>
                                                                        <th>EMP Name</th>

                                                                        <th>DATE</th>
                                                                        <th>TIME</th>
                                                                        <th>STATUS</th>
                                                                        <th>REASON</th>


                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set_att as $data) {
                                                                        if ($data->Status == 0) {
                                                                            $dataV = "Check IN";
                                                                        } elseif ($data->Status == 1) {
                                                                            $dataV = "Check OUT";
                                                                        }

                                                                        echo "<tr class='odd gradeX'>";

                                                                        echo "<td width='100'>" . $data->M_ID . "</td>";
                                                                        echo "<td width='100'>" . $data->EmpNo . "</td>";
                                                                        echo "<td width='100'>" . $data->Emp_Full_Name . "</td>";
                                                                        echo "<td width='100'>" . $data->Att_Date . "</td>";
                                                                        echo "<td width='100'>" . $data->In_Time . "</td>";
                                                                        echo "<td width='100'>" . $dataV . "</td>";
                                                                        echo "<td width='100'>" . $data->Reason . "</td>";

                                                                        echo "</tr>";
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                            <div class="panel-footer"></div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>
                                    </div>

                                </div>


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
        <!--<script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>-->


        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_employee_view").validate();
                $("#spnmessage").hide("shake", {
                    times: 6
                }, 3500);
            });
        </script>

        <!--Clear Text Boxes-->
        <script type="text/javascript">
            $("#cancel").click(function () {
                $("#txt_emp").val("");
                $("#txt_emp_name").val("");
                $("#cmb_desig").val("");
                $("#cmb_dep").val("");
                $("#cmb_comp").val("");
                $("#txt_nic").val("");
                $("#cmb_gender").val("");
                $("#cmb_status").val("");
            });
        </script>

        <script>
            $(function () {
                $('#att_date').datepicker({
                    "setDate": new Date(),
                    "autoclose": true,
                    "todayHighlight": true,
                    format: 'yyyy/mm/dd'
                });
                $('#att_date_1').datepicker({
                    "setDate": new Date(),
                    "autoclose": true,
                    "todayHighlight": true,
                    format: 'yyyy/mm/dd'
                });

                $('#to_date').datepicker({
                    "setDate": new Date(),
                    "autoclose": true,
                    "todayHighlight": true,
                    format: 'yyyy/mm/dd'
                });

            });
            $("#success_message_my").hide("bounce", 2000, 'fast');


            $("#search").click(function () {
                $('#search_body').html(
                    '<center><p><img style="width: 50;height: 50;" src="<?php echo base_url(); ?>assets/images/icon-loading.gif" /></p><center>'
                );
                $('#search_body').load(
                    "<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/search_employee", {
                        'txt_emp': $('#txt_emp').val(),
                        'txt_emp_name': $('#txt_emp_name').val(),
                        'from_date': $('#from_date').val(),
                        'to_date': $('#to_date').val(),
                        'txt_nic': $('#txt_nic').val(),
                        'cmb_status': $('#cmb_status').val(),
                        'cmb_gender': $('#cmb_gender').val()
                    });
            });
        </script>
        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_shift_allocation").validate();
                $("#spnmessage").hide("shake", {
                    times: 6
                }, 3500);
            });
        </script>


        <!--Auto complete-->
        <script type="text/javascript">
            $(function () {
                $("#txt_emp_name").autocomplete({
                    source: "<?php echo base_url(); ?>Employee_Management/View_Employees/get_auto_emp_name" // path to the get_birds method
                });
            });

            $(function () {
                $("#txt_emp").autocomplete({
                    source: "<?php echo base_url(); ?>Employee_Management/View_Employees/get_auto_emp_no" // path to the get_birds method
                });
            });
        </script>

        <script>
            document.getElementById("upload_excel").addEventListener("change", function () {
                let fileName = this.files[0] ? this.files[0].name : "No file chosen";
                document.querySelector(".file-name").textContent = fileName;
            });
        </script>
        <script>
            document.getElementById("uploadForm").addEventListener("submit", async function (event) {
                event.preventDefault();

                const fileInput = document.getElementById("upload_excel");
                if (fileInput.files.length === 0) {
                    alert("Please select a file");
                    return;
                }

                const formData = new FormData();
                formData.append("upload_excel", fileInput.files[0]);

                const loadingIndicator = document.getElementById("loading");
                const messageBox = document.getElementById("message");
                loadingIndicator.style.display = "block";
                messageBox.innerHTML = "";

                try {
                    const response = await fetch(
                        "<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/upload_sample", {
                            method: "POST",
                            body: formData
                        });

                    const result = await response.text();
                    messageBox.innerHTML = "<span style='color: green;'>✅ Upload successful!</span>";
                } catch (error) {
                    messageBox.innerHTML =
                        "<span style='color: red;'>❌ Upload failed. Please try again.</span>";
                } finally {
                    loadingIndicator.style.display = "none";
                }
            });
        </script>
</body>


</html>