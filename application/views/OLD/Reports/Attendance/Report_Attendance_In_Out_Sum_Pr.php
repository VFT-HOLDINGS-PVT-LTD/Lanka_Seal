<!DOCTYPE html>
<!--Description of dashboard page

@author Ashan Rathsara-->

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
                            <li class="active"><a href="<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum_Pr/">PRESENT REPORT</a></li>
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
                                                            <h2>PRESENT REPORT</h2>
                                                        </div>

                                                        <div class="panel-body">
                                                            <div style="font-size: 15px; font-weight: bold;"
                                                                class="bg-bright">REPORT BY CATEGORIES</div>

                                                             <form
                                                                action="<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum_Pr/Attendance_Report_By_Cat"
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
                                                                <?php
                                                                $newDateTime = new DateTime("now", new DateTimeZone("Asia/Colombo"));
                                                                $timestamp1 = date_format($newDateTime, 'Y/m/d');
                                                                ?>
                                                  

                                                            <div class="form-group col-md-6">
                                                                <div class="form-group col-sm-6">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">From
                                                                        Date</label>
                                                                    <div class="col-sm-8">

                                                                            <!--id="dpd1"-->
                                                                        <input type="text" class="form-control"
                                                                            required="" name="txt_from_date"
                                                                            placeholder="Select Date" value="<?php echo $timestamp1 ?>" readonly>


                                                                    </div>

                                                                </div>

                                                                <div class="form-group col-sm-6" style="">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">To
                                                                        Date</label>
                                                                    <div class="col-sm-8">

                                                                        <!--id="dpd2"-->
                                                                        <input type="text" class="form-control"
                                                                            required="" name="txt_to_date"
                                                                            placeholder="Select Date" value="<?php echo $timestamp1 ?>" readonly>


                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <div class="col-sm-6">
                                                                <!--<input type="submit" onclick="get();" id="search"-->
                                                                <!--    name="search" formtarget="_new"-->
                                                                <!--    class="btn-green btn fa fa-check"-->
                                                                <!--    value="&nbsp;&nbsp;VIEW&nbsp; EXCEL REPORT">-->
                                                                <input type="submit"  id="search"
                                                                    name="search" formtarget="_new"
                                                                    class="btn-green btn fa fa-check"
                                                                    value="&nbsp;&nbsp;VIEW&nbsp;PDF REPORT">

                                                                <input type="button" id="cancel" name="cancel"
                                                                    class="btn-danger-alt btn fa fa-check"
                                                                    value="&nbsp;&nbsp;CLEAR">
                                                            </div>
                                                             </form> 


                                                            <center>
                                                                <div id="loadingContainer" style="width: 30%;"></div>
                                                            </center>
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

    <script>
        // function getPDF() {
        //     var emp = document.getElementById("txt_emp").value;
        //     var empName = document.getElementById("txt_emp_name").value;
        //     var desig = document.getElementById("cmb_desig").value;
        //     var dep = document.getElementById("cmb_dep").value;
        //     var branch = document.getElementById("cmb_branch").value;
        //     var comp = document.getElementById("cmb_comp").value;
        //     var dp1 = document.getElementById("dpd1").value;
        //     var dp2 = document.getElementById("dpd2").value;

        //     var form = new FormData();
        //     form.append("txt_emp", emp);
        //     form.append("txt_emp_name", empName);
        //     form.append("cmb_desig", empName);
        //     form.append("cmb_dep", empName);
        //     form.append("cmb_branch", empName);
        //     form.append("cmb_comp", empName);
        //     form.append("dpd1", empName);
        //     form.append("dpd2", empName);
        //     var r = new XMLHttpRequest();
        //     r.onreadystatechange = function () {
        //         if (r.readyState == 4) {
        //             var text = r.responseText;
        //             // alert(text);
        //             console.log(text);
        //             // Optionally redirect to the generated Excel file link
        //             window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat';
        //         }
        //     }

        //     r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat", true);
        //     r.send(form);
        // }

        function getPDF() {
            // Create a div element to contain the loading indicator
            var loadingContainer = document.getElementById("loadingContainer");
            loadingContainer.innerHTML = "";  // Clear any previous content

            // Show loading indicator inside the div
            var loadingImg = document.createElement("img");
            loadingImg.src = "https://nerp.vft.lk/assets/images/icon-loading.gif";
            loadingImg.id = "loadingIndicator";
            loadingContainer.appendChild(loadingImg);

            var emp = document.getElementById("txt_emp").value;
            var empName = document.getElementById("txt_emp_name").value;
            var desig = document.getElementById("cmb_desig").value;
            var dep = document.getElementById("cmb_dep").value;
            var branch = document.getElementById("cmb_branch").value;
            var comp = document.getElementById("cmb_comp").value;
            var dp1 = document.getElementById("dpd1").value;
            var dp2 = document.getElementById("dpd2").value;

            var form = new FormData();
            form.append("txt_emp", emp);
            form.append("txt_emp_name", empName);
            form.append("cmb_desig", desig);
            form.append("cmb_dep", dep);
            form.append("cmb_branch", branch);
            form.append("cmb_comp", comp);
            form.append("dpd1", dp1);
            form.append("dpd2", dp2);

            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4) {
                    // Remove loading indicator
                    // var loadingIndicator = document.getElementById("loadingIndicator");
                    // if (loadingIndicator) {
                    //     loadingIndicator.parentNode.removeChild(loadingIndicator);
                    // }

                    // Remove loading indicator
                    loadingContainer.innerHTML = "";

                    var text = r.responseText;
                    console.log(text);
                    // Optionally redirect to the generated Excel file link
                    window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat';
                }
            }

            r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/Attendance_Report_By_Cat", true);
            r.send(form);
        }

        function get() {
            // Create a div element to contain the loading indicator
            var loadingContainer = document.getElementById("loadingContainer");
            loadingContainer.innerHTML = "";  // Clear any previous content

            // Show loading indicator inside the div
            var loadingImg = document.createElement("img");
            loadingImg.src = "https://nerp.vft.lk/assets/images/icon-loading.gif";
            loadingImg.id = "loadingIndicator";
            loadingContainer.appendChild(loadingImg);

            var emp = document.getElementById("txt_emp").value;
            var empName = document.getElementById("txt_emp_name").value;
            var desig = document.getElementById("cmb_desig").value;
            var dep = document.getElementById("cmb_dep").value;
            var branch = document.getElementById("cmb_branch").value;
            var comp = document.getElementById("cmb_comp").value;
            var dp1 = document.getElementById("dpd1").value;
            var dp2 = document.getElementById("dpd2").value;

            var form = new FormData();
            form.append("txt_emp", emp);
            form.append("txt_emp_name", empName);
            form.append("cmb_desig", desig);
            form.append("cmb_dep", dep);
            form.append("cmb_branch", branch);
            form.append("cmb_comp", comp);
            form.append("dpd1", dp1);
            form.append("dpd2", dp2);

            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4) {
                    // Remove loading indicator
                    // var loadingIndicator = document.getElementById("loadingIndicator");
                    // if (loadingIndicator) {
                    //     loadingIndicator.parentNode.removeChild(loadingIndicator);
                    // }

                    // Remove loading indicator
                    loadingContainer.innerHTML = "";

                    var text = r.responseText;
                    console.log(text);
                    // Optionally redirect to the generated Excel file link
                    window.location.href = '<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel';
                }
            }

            r.open("POST", "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel", true);
            r.send(form);
        }

    </script>

    <!-- <script>
        function get() {
            var htmlContent = $(".form-group.col-md-12").html();

            // Send AJAX request
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>Reports/Attendance/Report_Attendance_ATT_Sum/exportToExcel",
                data: { htmlContent: htmlContent },
                dataType: 'json',
                success: function (response) {
                    console.log("AJAX request successful");
                    // Run the exportToExcel function after the AJAX request is successful
                    exportToExcel();
                },
                error: function (error) {
                    console.error("AJAX request failed", error);
                }
            });
        }
    </script> -->
</body>


</html>