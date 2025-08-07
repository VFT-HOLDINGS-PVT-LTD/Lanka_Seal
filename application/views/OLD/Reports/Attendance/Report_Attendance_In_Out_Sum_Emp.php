<?php
$currentUser = $this->session->userdata('login_user');
?>
<!DOCTYPE html>
<!--Description of dashboard page

@author Ashan Rathsara-->

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
                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum_Emp/">INTUITIVE EMPLOYEE ATTENDANCE REPORT</a></li>
                        </ol>

                        <div class="container-fluid">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h2>INTUITIVE EMPLOYEE ATTENDANCE REPORT</h2>
                                                        </div>

                                                        <div class="panel-body">
                                                            <div style="font-size: 15px; font-weight: bold;"
                                                                class="bg-bright">REPORT BY CATEGORIES</div>
                                                            <form
                                                                action="<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum_Emp/Attendance_Report_By_Cat"
                                                                class="form-horizontal" id="frm_in_out_rpt"
                                                                name="frm_in_out_rpt" method="POST">

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-6">
                                                                        <img class="imagecss1"
                                                                            src="<?php echo base_url(); ?>assets/images/attendance_inout.png">
                                                                    </div>

                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-3">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Emp
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_emp_name"
                                                                                name="txt_emp_name"
                                                                                readonly=""
                                                                                value="<?php echo "" . $currentUser[0]->Emp_Full_Name; ?>"
                                                                                required="required">
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
                                                                                required="" id="dpd1"
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
                                                                                required="" id="dpd2" name="txt_to_date"
                                                                                placeholder="Select Date">


                                                                        </div>

                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-6">
                                                                    <input type="submit" id="search" name="search"
                                                                        formtarget="_new"
                                                                        class="btn-green btn fa fa-check"
                                                                        value="&nbsp;&nbsp;VIEW&nbsp; REPORT">
                                                                    <input type="button" id="cancel" name="cancel"
                                                                        class="btn-danger-alt btn fa fa-check"
                                                                        value="&nbsp;&nbsp;CLEAR">
                                                                </div>
                                                            </form>
                                                            <hr>


                                                        </div>

                                                    </div>

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

    <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

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
            $("#spnmessage").hide("shake", { times: 4 }, 1500);
        });
    </script>


    <!--Auto complete-->
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





</body>


</html>