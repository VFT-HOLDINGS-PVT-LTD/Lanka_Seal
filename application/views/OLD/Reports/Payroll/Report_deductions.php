<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->

<html lang="en">



<head>
    <title>
        <?php echo $title ?>
    </title>
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
                        <ol class="breadcrumb">

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Reports/Payroll/Deduction_Report/">DEDUCTION REPORT</a></li>

                        </ol>

                        <div class="container-fluid">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h2>DEDUCTION REPORT</h2>
                                                </div>
                                                <div class="panel-body">

                                                    <form class="form-horizontal" id="frm_in_out_rpt"
                                                        name="frm_in_out_rpt"
                                                        action="<?php echo base_url(); ?>Reports/Payroll/Deduction_Report/Report_deduction_by_type"
                                                        method="POST">

                                                        <div>
                                                            <label style="font-weight: bold; color: #000"> CREATE
                                                                DEDUCTION REPORT<span class="text-danger">*</span>
                                                            </label>
                                                        </div>

                                                        <!-- Display Success Message -->
                                                        <?php if ($this->session->flashdata('success_message')) { ?>
                                                        <div id="spnmessage"
                                                            class="alert alert-dismissable alert-success success_redirect">
                                                            <strong>Success!</strong>
                                                            <?php echo $this->session->flashdata('success_message'); ?>
                                                        </div>
                                                        <?php } ?>

                                                        <!-- Display Error Message -->
                                                        <?php if ($this->session->flashdata('error_message')) { ?>
                                                        <div id="spnmessage"
                                                            class="alert alert-dismissable alert-danger error_redirect">
                                                            <strong>Error!</strong>
                                                            <?php echo $this->session->flashdata('error_message'); ?>
                                                        </div>
                                                        <?php } ?>

                                                        <div class="form-group col-sm-12" style="margin-top: 2vh;">
                                                            <div class="col-sm-6">
                                                                <img class="imagecss"
                                                                    src="<?php echo base_url(); ?>assets/images/deduction.png">
                                                            </div>

                                                        </div>

                                                        <div class="form-group col-md-12">

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Emp No</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        name="emp_no" id="emp_no"
                                                                        placeholder="Ex: 0001">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Emp Name</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        name="emp_name" id="emp_name"
                                                                        placeholder="Ex: Ashan">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Designation</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="designation"
                                                                        name="designation">
                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_desig as $t_data) { ?>
                                                                        <option value="<?php echo $t_data->Des_ID; ?>">
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
                                                                    <select class="form-control" id="department"
                                                                        name="department">
                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_dep as $t_data) { ?>
                                                                        <option value="<?php echo $t_data->Dep_ID; ?>">
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
                                                                    <select class="form-control" id="branch"
                                                                        name="branch">
                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_branch as $b_data) { ?>
                                                                        <option value="<?php echo $b_data->B_id; ?>">
                                                                            <?php echo $b_data->B_name; ?>
                                                                        </option>
                                                                        <?php }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Group</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="branch"
                                                                        name="group">
                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_group as $g_data) { ?>
                                                                        <option value="<?php echo $g_data->Grp_ID; ?>">
                                                                            <?php echo $g_data->EmpGroupName; ?>
                                                                        </option>
                                                                        <?php }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Year</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="year" name="year">
                                                                        <option value="" default>-- Select --</option>
                                                                        <?php 
                                                                        for ($i = date("Y") + 5; $i >= 1900; $i--) { ?>
                                                                        <option value="<?php echo $i; ?>">
                                                                            <?php echo $i; ?>
                                                                        </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Month</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="month"
                                                                        name="month">
                                                                        <option value="" default>-- Select --</option>
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

                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Type</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="deduction_type"
                                                                        name="deduction_type" required>
                                                                        <option value="" default>-- Select --</option>
                                                                        <option value="loan">Loan</option>
                                                                        <option value="festival">Festival</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-group col-md-12"
                                                            style="display: flex;justify-content: end;margin-top: 2vh;">
                                                            <div class="col-sm-6">
                                                                <input type="submit" formtarget="_new" id="search"
                                                                    name="search" class="btn-green btn fa fa-check"
                                                                    value="&nbsp;&nbsp;VIEW&nbsp; REPORT">
                                                                <input type="button" id="cancel" name="cancel"
                                                                    class="btn-danger-alt btn fa fa-check"
                                                                    value="&nbsp;&nbsp;CLEAR">
                                                            </div>
                                                        </div>

                                                    </form>

                                                    <hr>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- .container-fluid -->
                        </div>
                    </div>
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


    <!--Clear Text Boxes-->
    <script type="text/javascript">
        $("#cancel").click(function () {

            $("#emp_no").val("");
            $("#emp_name").val("");
            $("#designation").val("");
            $("#department").val("");
            $("#branch").val("");
            $("#year").val("");
            $("#month").val("");
            $("#deduction_type").val("");


        });
    </script>

    <!--JQuary Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_in_out_rpt").validate();
            $("#spnmessage").hide("shake", {
                times: 4
            }, 1500);
        });
    </script>


    <!--Auto complete-->
    <script type="text/javascript">
        $(function () {
            $("#txt_emp_name").autocomplete({
                source: "<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/get_auto_emp_name"
            });
        });

        $(function () {
            $("#txt_emp").autocomplete({
                source: "<?php echo base_url(); ?>Attendance/Attendance_Manual_Entry/get_auto_emp_no"
            });
        });
    </script>


</body>

</html>