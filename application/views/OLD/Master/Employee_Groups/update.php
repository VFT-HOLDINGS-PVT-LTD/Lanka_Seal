<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 22px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #009ebb;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
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

                            <li class=""><a href="index.html">HOME</a></li>
                            <li class="active"><a href="index.html">EMPLOYEE GROUPS</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li><a href="<?php echo base_url(); ?>Master/Employee_Groups">EMPLOYEE GROUPS</a></li>
                                <li class="active"><a data-toggle="tab" href="#tab2">UPDATE EMPLOYEE GROUPS</a></li>


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
                                                            <h2>EMPLOYEE GROUP UPDATE</h2>
                                                        </div>

                                                        <div class="panel-body">
                                                            <div> <label
                                                                    style="font-weight: bold; color: #000">ATTENDANCE
                                                                    SETTING <span class="text-danger">*</span></label>
                                                            </div>

                                                            <div class="modal-body">

                                                                <form class="form-horizontal" id="frm_emp_group"
                                                                    name="frm_emp_group"
                                                                    action="<?php echo base_url(); ?>Master/Employee_Groups/edit"
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
                                                                    <div class="form-group col-sm-12">
                                                                        <div class="col-sm-4">
                                                                            <img class="imagecss"
                                                                                src="<?php echo base_url(); ?>assets/images/employee_group.png">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-12">

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Group
                                                                                Name</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text"
                                                                                    class="form-control hidden" readonly
                                                                                    id="txt_group_id"
                                                                                    value="<?php echo $data_set[0]->Grp_ID; ?>"
                                                                                    name="txt_group_id"
                                                                                    placeholder="Ex: Office">

                                                                                <input type="text" class="form-control"
                                                                                    id="txt_group_name"
                                                                                    value="<?php echo $data_set[0]->EmpGroupName; ?>"
                                                                                    name="txt_group_name"
                                                                                    placeholder="Ex: Office">
                                                                            </div>

                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Group
                                                                                Supervisor</label>
                                                                            <div class="col-sm-8">
                                                                                <select class="form-control"
                                                                                    id="cmb_Supervisor"
                                                                                    name="cmb_Supervisor">

                                                                                    <option value="" default>--
                                                                                        Select --</option>
                                                                                    <?php foreach ($emp_sup as $t_data) {
                                                                                        if ($t_data->EmpNo == $data_set[0]->EmpNo) {
                                                                                    ?>
                                                                                            <option selected
                                                                                                value="<?php echo $t_data->EmpNo; ?>">
                                                                                                <?php echo $t_data->Emp_Full_Name; ?>
                                                                                            </option>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <option
                                                                                                value="<?php echo $t_data->EmpNo; ?>">
                                                                                                <?php echo $t_data->Emp_Full_Name; ?>
                                                                                            </option>
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>



                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">OT Morning</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Ot_m == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="ot_m" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="ot_m" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>

                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">OT Evening</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Ot_e == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="ot_e" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="ot_e" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Min
                                                                                Time to Morning OT</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->Min_time_t_ot_m ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size" name="min_t_ot"
                                                                                    placeholder="Ex: 120">
                                                                            </div>
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Min
                                                                                Time to Evening OT</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->Min_time_t_ot_e ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size"
                                                                                    name="min_t_e_ot"
                                                                                    placeholder="Ex: 120">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">Late</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Late == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="late" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="late" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>

                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">ED</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Ed == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="ed" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="ed" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">Late
                                                                                decuct for Leave in Half Day</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Hd_d_from == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="sh_lv" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="sh_lv" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">Late
                                                                                deduct from OT</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Ot_d_Late == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="late_ot" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="late_ot" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">Double
                                                                                OT for Holiday</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Dot_f_holyday == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="dot_holyday" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="dot_holyday" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">Double
                                                                                OT for OFF Day</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Dot_f_offday == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="dot_offday" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="dot_offday" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Round</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->Round ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size" name="round"
                                                                                    placeholder="Ex: 120">
                                                                            </div>
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Late
                                                                                Grace Period</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->late_Grs_prd ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size" name="late_gp"
                                                                                    placeholder="Ex: 120">
                                                                            </div>

                                                                        </div>


                                                                    </div>

                                                                    <div class="form-group col-md-12"
                                                                        style="margin-bottom: 2vh;margin-top: 2vh;">
                                                                        <label
                                                                            style="font-weight: bold; color: #000">PAYROLL
                                                                            SETTING
                                                                            <span
                                                                                class="text-danger">*</span></label>
                                                                    </div>

                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">No
                                                                            Pay</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Nopay == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_nopay" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_nopay" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">Attendance
                                                                            Alowance</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Att_allow == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_att_allow" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_att_allow" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">Daily
                                                                            Rate</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Daily_Rate == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_daily_rate" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_daily_rate" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Daily
                                                                                Rate</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->Daily_Rate ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size"
                                                                                    name="daily_rate"
                                                                                    placeholder="Ex: 120">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">OT
                                                                            Calculate</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Ot_Cal == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_ot_cal" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_ot_cal" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label class="col-sm-4 control-label">DOT
                                                                            Calculate</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Dot_Cal == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_dot_cal" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_dot_cal" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label class="col-sm-4 control-label">OT Fixed
                                                                            Amount Rate</label>
                                                                            <div class="col-sm-2">
                                                                                <label class="switch">
                                                                                    <?php
                                                                                    if ($data_set[0]->Is_Ot_Fixed_Amount_Rate == 1) {
                                                                                    ?>
                                                                                        <input type="checkbox" checked name="is_ot_fixed_amount_rate" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="checkbox" name="is_ot_fixed_amount_rate" id="chk_1st">
                                                                                        <span class="slider"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </label>
                                                                            </div>
                                                                            <label for="focusedinput"
                                                                                class="col-sm-4 control-label">Fixed
                                                                                Amount
                                                                                Rate</label>
                                                                            <div class="col-sm-2">
                                                                                <input type="number"
                                                                                    value="<?php echo $data_set[0]->Ot_Rate ?>"
                                                                                    class="form-control"
                                                                                    id="txt_max_l_size" name="ot_rate"
                                                                                    placeholder="Ex: 120">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-12">
                                                                        <div class="form-group col-sm-12">
                                                                            <div class="modal-footer"
                                                                                style="margin-right:2vh;">
                                                                                <a
                                                                                    href="<?php echo base_url(); ?>Master/Employee_Groups"><button
                                                                                        type="button"
                                                                                        class="btn btn-default"
                                                                                        data-dismiss="modal">Close</button>
                                                                                </a>
                                                                                <button type="submit" id="submit"
                                                                                    class="btn btn-primary">Update
                                                                                    changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <!--submit button-->
                                                                    <!-- <?php $this->load->view('template/btn_submit.php'); ?> -->
                                                                    <!--end submit-->


                                                                </form>
                                                            </div>



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
        <script src="<?php echo base_url(); ?>system_js/Master/OT_Pattern.js"></script>
        <script>
            function createOTPatternArr2() {
                myData = [];
                $("[id^='Day'").each(function() {

                    elementIndex = this.id.replace("Day", "");

                    myData.push({
                        "Day": $("#Day" + elementIndex).val(),
                        "Type": $("#Type" + elementIndex).val(),
                        "chkBSH": $("#chkBSH" + elementIndex).val(),
                        "MinTw": $("#MinTw" + elementIndex).val(),
                        "ChkASH": $("#chkASH" + elementIndex).val(),
                        "ASH_MinTw": $("#ASH_MinTw" + elementIndex).val(),
                        "RoundUp": $("#RoundUp" + elementIndex).val()


                    });

                });

                $("#hdntext2").val(JSON.stringify(myData));
                console.log(JSON.stringify(myData));
            }
        </script>

</body>


</html>