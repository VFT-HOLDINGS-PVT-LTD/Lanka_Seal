<!DOCTYPE html>


<!--Description of Shift Allocation page-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

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
                            <li class="active"><a href="<?php echo base_url(); ?>Attendance/Shift_Allocation/">SHIFT ALLOCATION</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">SHIFT ALLOCATION</a></li>
                                <!--<li><a data-toggle="tab" href="#tab2">VIEW LEAVE TYPES</a></li>-->


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
                                                            <h2>SHIFT ALLOCATION</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_shift_allocation"
                                                                name="frm_shift_allocation"
                                                                action="<?php echo base_url(); ?>Attendance/Shift_Allocation/shift_allocation"
                                                                method="POST">

                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                    <div id="spnmessage"
                                                                        class="alert alert-dismissable alert-success success_redirect">
                                                                        <strong>Success !</strong>
                                                                        <?php echo $_SESSION['success_message'] ?>
                                                                    </div>
                                                                <?php } ?>

                                                                <!--Error Message-->
                                                                <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') { ?>
                                                                    <div id="spnmessage"
                                                                        class="alert alert-dismissable alert-danger error_redirect">
                                                                        <strong>Error !</strong>
                                                                        <?php echo $_SESSION['error_message'] ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <div id="search_body">

                                                                </div>

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss"
                                                                            src="<?php echo base_url(); ?>assets/images/shift_allocation.png">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Category</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" required
                                                                                id="cmb_cat" name="cmb_cat"
                                                                                onchange="selctcity()">
                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <option value="Employee">Employee
                                                                                </option>
                                                                                <option value="Department">Department
                                                                                </option>
                                                                                <option value="Designation">Designation
                                                                                </option>
                                                                                <option value="Employee_Group">
                                                                                    Employee_Group</option>
                                                                                <option value="Company">Company</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div id="dynamic-fields"></div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">
                                                                            Roster</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" required
                                                                                id="cmb_roster" name="cmb_roster">

                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($data_roster as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->RosterCode; ?>">
                                                                                        <?php echo $t_data->RosterName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">From
                                                                            Date</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                required="" id="dpd1"
                                                                                name="txt_from_date">
                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">To
                                                                            Date</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                required="" id="dpd2"
                                                                                name="txt_to_date">
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <hr>
                                                                <!--submit button-->
                                                                <?php $this->load->view('template/btn_submit.php'); ?>
                                                                <!--end submit-->
                                                            </form>

                                                            <hr>
                                                            <div id="divmessage" class="">

                                                                <div id="spnmessage"> </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--***************************-->
                            </div>

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

    <!--Ajax-->
    <!--<script src="<?php echo base_url(); ?>system_js/Master/L_Types.js"></script>-->



    <!-- Load page level scripts-->


    <!--Ajax-->
    <script src="<?php echo base_url(); ?>system_js/Master/L_Types.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
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

            $('#txt_nic').on('change', function () {
                var empNo = $(this).val();
                if (empNo) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/get_mem_data/' + empNo,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            if (data.length > 0) {
                                $('#txt_emp_name').val(data[0].Emp_Full_Name);
                            }
                        }
                    });
                }
            });

            $('#cmb_cat').on('change', function () {
                var selectedValue = $(this).val();
                var dynamicFields = $('#dynamic-fields');
                dynamicFields.empty();

                if (selectedValue === 'Employee') {
                    dynamicFields.html(`
                        <div class="form-group col-sm-6">
                            <label for="" class="col-sm-4 control-label">Emp Number</label>
                            <div class="col-sm-8">
                                <select type="text" required="required" autocomplete="off" class="form-control txt_nic itemName" name="txt_nic" id="txt_nic" placeholder="">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="txt_emp_name" class="col-sm-4 control-label">Selected Emp Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="txt_emp_name" name="txt_emp_name" placeholder="Selected Emp Name" readonly>
                            </div>
                        </div>
                    `);

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

                    $('#txt_nic').on('change', function () {
                        var empNo = $(this).val();
                        if (empNo) {
                            $.ajax({
                                url: '<?php echo base_url(); ?>Leave_Transaction/Leave_Entry/get_mem_data/' + empNo,
                                type: "GET",
                                dataType: "json",
                                success: function (data) {
                                    if (data.length > 0) {
                                        $('#txt_emp_name').val(data[0].Emp_Full_Name);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    dynamicFields.html(`
                        <div class="form-group col-sm-6">
                            <label for="" class="col-sm-4 control-label">Select</label>
                            <div class="col-sm-8" id="cat_div">
                                <select class="form-control" required id="cmb_cat2" name="cmb_cat2">
                                </select>
                            </div>
                        </div>
                    `);

                    $.post('<?php echo base_url(); ?>index.php/Pay/Allowance/dropdown/', { cmb_cat: selectedValue }, function (data) {
                        $('#cmb_cat2').html(data);
                    });
                }
            });

            $("#cmb_cat").trigger("change");
        });
    </script>

    <!-- Load page level scripts-->
    <script src="<?php echo base_url(); ?>assets/plugins/clockface/js/clockface.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Color Picker -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <!-- Datepicker -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/bootstrap-timepicker.js"></script>
    <!-- Timepicker -->
    <script
        src="<?php echo base_url(); ?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <!-- DateTime Picker -->
    <script src="<?php echo base_url(); ?>assets/plugins/form-daterangepicker/moment.min.js"></script>
    <!-- Moment.js for Date Range Picker -->
    <script src="<?php echo base_url(); ?>assets/plugins/form-daterangepicker/daterangepicker.js"></script>
    <!-- Date Range Picker -->
    <script src="<?php echo base_url(); ?>assets/demo/demo-pickers.js"></script>

    <!--Dropdown selected text into label-->
    <script type="text/javascript">
        $(function () {
            $("#cmb_cat").on("change", function () {
                $("#change").text($("#cmb_cat").find(":selected").text());
            }).trigger("change");
        });
    </script>

    <!--JQuery Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_leave_allocatione").validate();
            $("#spnmessage").hide("shake", { times: 6 }, 3000);
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
            $("#frm_shift_allocation").validate();
            $("#spnmessage").hide("shake", { times: 6 }, 3500);
        });
    </script>

    <script>
        $("#success_message_my").hide("bounce", 2000, 'fast');
        $("#submit").click(function () {
            $('#search_body').html('<center><p><img style="width: 50;height: 50;" src="<?php echo base_url(); ?>assets/images/processing.gif" /></p><center>');

        });
    </script>

</body>


</html>