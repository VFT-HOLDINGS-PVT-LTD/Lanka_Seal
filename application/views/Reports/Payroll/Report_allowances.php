<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->

<html lang="en">



    <head>
        <title><?php echo $title ?></title>
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

                            <li class=""><a href="">HOME</a></li>
                            <li class="active"><a href="">ALLOWANCE REPORT</a></li>

                        </ol>

                        <div class="container-fluid">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h2>ALLOWANCES</h2>
                                                </div>
                                                <div class="panel-body">

                                                    <form class="form-horizontal" id="frm_in_out_rpt"
                                                        name="frm_in_out_rpt"
                                                        action="<?php echo base_url(); ?>Reports/Payroll/Allowance_Report/Report_Allowances_by_cat"
                                                        method="POST">

                                                        <div>
                                                            <label style="font-weight: bold; color: #000">
                                                                CREATE
                                                                ALLOWANCES REPORT<span class="text-danger">*</span>
                                                            </label>
                                                        </div>

                                                        <div class="form-group col-sm-12">
                                                            <div class="col-sm-6">
                                                                <img class="imagecss"
                                                                    src="<?php echo base_url(); ?>assets/images/allowance_report.png">
                                                            </div>

                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Emp No</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        name="txt_emp" id="txt_emp"
                                                                        placeholder="Ex: 0001">
                                                                </div>

                                                            </div>
                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Emp Name</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        name="txt_emp_name" id="txt_emp_name"
                                                                        placeholder="Ex: Ashan">
                                                                </div>

                                                            </div>
                                                            <div class="form-group col-sm-3">
                                                                <label for="focusedinput"
                                                                    class="col-sm-4 control-label">Designation</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="cmb_desig"
                                                                        name="cmb_desig">

                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_desig as $t_data) { ?>
                                                                        <option value="<?php echo $t_data->Des_ID; ?>">
                                                                            <?php echo $t_data->Desig_Name; ?></option>

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


                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_dep as $t_data) { ?>
                                                                        <option value="<?php echo $t_data->Dep_ID; ?>">
                                                                            <?php echo $t_data->Dep_Name; ?></option>

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


                                                                        <option value="" default>-- Select --</option>
                                                                        <?php foreach ($data_cmp as $t_data) { ?>
                                                                        <option value="<?php echo $t_data->Cmp_ID; ?>">
                                                                            <?php echo $t_data->Company_Name; ?>
                                                                        </option>

                                                                        <?php }
                                                                            ?>

                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-sm-3" id="month_div">
                                                                <label for="cmb_month" class="col-sm-4 control-label">Month</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="cmb_month" name="cmb_month">
                                                                        <option value=""></option>
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
                                                                <label for="allowance_type" class="col-sm-4 control-label">Type</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" id="allowance_type" name="allowance_type" required>
                                                                        <option value="">-- Select --</option>
                                                                        <option value="fixed_allowance">Fixed Allowance</option>
                                                                        <option value="variable_allowance">Variable Allowance</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div class="form-group col-md-12"
                                                            style="display: flex;justify-content: end;">
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
                                                <div id="search_body">

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

    <!-- End loading page level scripts-->

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

    <!--Date Format-->
    <script>
        $('#dpd1').datepicker({
            format: "dd/mm/yyyy",
            "todayHighlight": true,
            autoclose: true,
            format: 'yyyy/mm/dd'
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
        $('#dpd2').datepicker({
            format: "dd/mm/yyyy",
            "todayHighlight": true,
            autoclose: true,
            format: 'yyyy/mm/dd'
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
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

    <script>
        document.getElementById("allowance_type").addEventListener("change", function () {
            var type = this.value;
            var monthDiv = document.getElementById("month_div");

            if (type === "fixed_allowance") {
                monthDiv.style.display = "none";
            } else {
                monthDiv.style.display = "block";
            }
        });

        // Optional: Trigger once on load in case the form is pre-filled
        window.addEventListener("DOMContentLoaded", function () {
            document.getElementById("allowance_type").dispatchEvent(new Event("change"));
        });
    </script>






</body>


</html>