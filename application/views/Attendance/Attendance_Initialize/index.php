<!DOCTYPE html>


<!--Description of Shift Allocation page

@author Ashan Rathsara-->


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
                                <li class="active"><a href="<?php echo base_url(); ?>Attendance/Attendance_Initialize/">ATTENDANCE INITIALIZE</a></li>

                            </ol>


                            <div class="page-tabs">
                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab1">ATTENDANCE INITIALIZE</a></li>
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
                                                            <div class="panel-heading"><h2>ATTENDANCE INITIALIZE</h2></div>
                                                            <div class="panel-body">
                                                                <form class="form-horizontal" id="frm_shift_allocation" name="frm_shift_allocation" action="<?php echo base_url(); ?>Attendance/Attendance_Initialize/initialize" method="POST">

                                                                    <!--success Message-->
                                                                    <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                        <div id="spnmessage" class="alert alert-dismissable alert-success success_redirect">
                                                                            <strong>Success !</strong> <?php echo $_SESSION['success_message'] ?>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <!--Error Message-->
                                                                    <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') { ?>
                                                                        <div id="spnmessage" class="alert alert-dismissable alert-danger error_redirect">
                                                                            <strong>Error !</strong> <?php echo $_SESSION['error_message'] ?>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <div class="form-group col-sm-12">
                                                                        <div class="col-sm-8">
                                                                            <img class="imagecss" src="<?php echo base_url(); ?>assets/images/process_remove.png" >
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

                                                                        
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">From Date</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control" required="" id="dpd1" name="txt_from_date">
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput" class="col-sm-4 control-label">To Date</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control" required="" id="dpd2" name="txt_to_date">
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

        <?php $this->load->view('template/js.php'); ?>							<!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <!--<script src="<?php echo base_url(); ?>system_js/Master/L_Types.js"></script>-->



        <!-- Load page level scripts-->



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

        <!--Dropdown selected text into label-->
        <!-- <script type="text/javascript">
            $(function () {
                $("#cmb_cat").on("change", function () {
                    $("#change").text($("#cmb_cat").find(":selected").text());
                }).trigger("change");
            });
        </script>


        <script>
            function selctcity()
            {

                var branch_code = $('#cmb_cat').val();

                $.post('<?php echo base_url(); ?>index.php/Attendance/Shift_Allocation/dropdown/',
                        {
                            cmb_cat: branch_code

                        },
                function (data)
                {

                    $('#cmb_cat2').html(data);
                });

            }

        </script> -->

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
                $("#spnmessage").hide("shake", {times: 6}, 3500);
            });
        </script>

    </body>


</html>