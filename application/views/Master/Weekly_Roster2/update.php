<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">


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
                        <ol class="breadcrumb">

                            <li class=""><a href="index.html">HOME</a></li>
                            <li class="active"><a href="index.html">WEEKLY ROSTER PATTERN</a></li>
                            <li><a>UPDATE WEEKLY ROSTER PATTERN</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">UPDATE WEEKLY ROSTER PATTERN</a>
                                </li>
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
                                                            <h2>ADD WEEKLY ROSTER PATTERN</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_weekly_roster"
                                                                name="frm_weekly_roster"
                                                                action="<?php echo base_url(); ?>Master/Weekly_Roster2/update_weekly_roster_pattern"
                                                                method="POST" onsubmit="createShiftDataArr()">


                                                                <!--success Message-->
                                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                <div id="spnmessage"
                                                                    class="alert alert-dismissable alert-success">
                                                                    <strong>Success !</strong>
                                                                    <?php echo $_SESSION['success_message'] ?>
                                                                </div>
                                                                <?php } ?>

                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss"
                                                                            src="<?php echo base_url(); ?>assets/images/roster_pattern.png">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Roster
                                                                            Code</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" readonly=""
                                                                                value="<?php echo $roaster_code; ?>"
                                                                                class="form-control" id="txtRoster_Code"
                                                                                name="txtRoster_Code" placeholder="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Roster
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo $roster_name; ?>"
                                                                                id="txtRoster_Name"
                                                                                name="txtRoster_Name"
                                                                                placeholder="Ex: Office" readonly>
                                                                        </div>
                                                                    </div>



                                                                </div><br>

                                                                <?php foreach ($data_set as $index => $data) { ?>
                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label"
                                                                            style="font-weight: bold">
                                                                            <?php echo $data->DayName; ?>
                                                                        </label>


                                                                        <div class="col-sm-2">
                                                                            <select
                                                                                class="form-control shift-type-select"
                                                                                required
                                                                                id="SHType<?php echo $index; ?>"
                                                                                name="SHType<?php echo $index; ?>"
                                                                                data-index="<?php echo $index; ?>">
                                                                                <option
                                                                                    value="<?php echo $data->ShiftCode; ?>"
                                                                                    selected>
                                                                                    <?php echo $data->ShiftCodeName; ?>
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                <option
                                                                                    value="<?php echo $t_data->ShiftCode; ?>">
                                                                                    <?php echo $t_data->ShiftName; ?>
                                                                                </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>


                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo $data->ShiftCodeName;?>"
                                                                                id="txtDayType<?php echo $index; ?>"
                                                                                name="txtDayType<?php echo $index; ?>"
                                                                                placeholder="">

                                                                            <input type="hidden"
                                                                                id="DType<?php echo $index; ?>"
                                                                                name="DType<?php echo $index; ?>"
                                                                                value="<?php echo $data->DayName; ?>">
                                                                        </div>

                                                                        <!-- SType Dropdown -->
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType<?php echo $index; ?>"
                                                                                name="txtSType<?php echo $index; ?>"
                                                                                class="form-control">
                                                                                <option
                                                                                    value="<?php echo $data->ShiftType; ?>"
                                                                                    selected>
                                                                                    <?php echo $data->ShiftType; ?>
                                                                                </option>
                                                                                <option value="DU">DU</option>
                                                                                <option value="EX">EX</option>
                                                                                <option value="OFF">OFF</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>


                                                                <div id="fieldsContainer7"></div>

                                                                <!-- </div> -->

                                                                <!--Hidden Text-->
                                                                <input type="text" name="hdntext" id="hdntext"
                                                                    class="hide">



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
        <script src="<?php echo base_url(); ?>system_js/Master/Weekly_Roster.js"></script>

        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_weekly_roster").validate();
                $("#spnmessage").hide("shake", {
                    times: 4
                }, 1500);
            });
        </script>


</body>


</html>