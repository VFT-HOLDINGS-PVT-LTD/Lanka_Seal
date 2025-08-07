<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('application/views/Master/Employee_Groups/styles.css'); ?>">
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

                                <li class="active"><a data-toggle="tab" href="#tab1">EMPLOYEE GROUPS</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW EMPLOYEE GROUPS</a></li>
                                <li><a data-toggle="tab" href="#tab3">COMPANY HIERARCHY</a></li>


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
                                                            <h2>ADD EMPLOYEE GROUPS</h2>
                                                        </div>

                                                        <div class="panel-body">

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

                                                            <div> <label
                                                                    style="font-weight: bold; color: #000">ATTENDANCE
                                                                    SETTING <span class="text-danger">*</span></label>
                                                            </div>

                                                            <form class="form-horizontal" id="frm_emp_group"
                                                                name="frm_emp_group"
                                                                action="<?php echo base_url(); ?>Master/Employee_Groups/insert_Data"
                                                                method="POST">


                                                                <div class="form-group col-sm-12">
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss"
                                                                            src="<?php echo base_url(); ?>assets/images/employee_group.png">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 col-md-4 control-label">Group
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                id="txt_group_name"
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

                                                                                <option value="" default>-- Select --
                                                                                </option>
                                                                                <?php foreach ($emp_sup as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->EmpNo; ?>">
                                                                                        <?php echo $t_data->Emp_Full_Name; ?>
                                                                                    </option>

                                                                                <?php }
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
                                                                                <input type="checkbox" name="ot_m" id="chk_1st">
                                                                                <span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-sm-4 control-label">OT Evening</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="ot_e"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Min Time to
                                                                            Morning OT</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="txt_max_l_size" name="min_t_ot"
                                                                                placeholder="Ex: 120">
                                                                        </div>
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Min Time to
                                                                            Evening OT</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="txt_max_l_size" name="min_t_e_ot"
                                                                                placeholder="Ex: 120">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">Late</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="late"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-sm-4 control-label">ED</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="ed"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">Late decuct
                                                                            for Leave in Half Day</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="sh_lv"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-sm-4 control-label">Late deduct
                                                                            from OT</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="late_ot"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">Double OT for
                                                                            Holiday</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="dot_holyday"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                       <label class="col-sm-4 control-label">Double OT for
                                                                            OFF Day</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="dot_offday"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Round
                                                                            Up</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="txt_max_l_size" name="round"
                                                                                placeholder="Ex: 120">
                                                                        </div>
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Late Grace
                                                                            Period</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="txt_max_l_size" name="round"
                                                                                placeholder="Ex: 120">
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <div class="form-group col-md-12"><label
                                                                        style="font-weight: bold; color: #000;margin-top:5vh;">PAYROLL
                                                                        SETTING <span
                                                                            class="text-danger">*</span></label>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-12">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-2 control-label">Currency</label>
                                                                        <div class="col-sm-4">
                                                                            <select class="form-control"
                                                                                id="currency"
                                                                                name="currency">
                                                                                <option value="LKR" default>LKR</option>
                                                                                <option value="INR" default>INR</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">No
                                                                            Pay</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_nopay"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-sm-4 control-label">Attendance
                                                                            Alowance</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_att_allow"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">Daily
                                                                            Rate</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_daily_rate"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Daily
                                                                            Rate</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="daily_rate" name="daily_rate"
                                                                                placeholder="Ex: 120">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">OT
                                                                            Calculate</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_ot_cal"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-sm-4 control-label">DOT
                                                                            Calculate</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_dot_cal"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label class="col-sm-4 control-label">OT Fixed
                                                                            Amount Rate</label>
                                                                        <div class="col-sm-2 ">
                                                                            <label class="switch"><input type="checkbox"
                                                                                    name="is_ot_Fixed_amount_rate"
                                                                                    id="chk_1st"><span class="slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">Fixed Amount
                                                                            Rate</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="number" class="form-control"
                                                                                id="txt_max_l_size" name="ot_rate"
                                                                                placeholder="Ex: 120">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group col-sm-12">
                                                                        <!--submit button-->
                                                                        <?php $this->load->view('template/btn_submit.php'); ?>
                                                                        <!--end submit-->
                                                                    </div>
                                                                </div>


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
                                <!-- Grid View -->

                                <div class="tab-pane" id="tab2">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h2>USER LEVEL DETAILS</h2>
                                                            <div class="panel-ctrls">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body panel-no-padding">
                                                            <table id="example"
                                                                class="table table-striped table-bordered"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>NAME</th>
                                                                        <th>OT MORNING</th>
                                                                        <th>OT EVENING</th>
                                                                        <th>LATE</th>
                                                                        <th>EARLY DEPARTURE</th>
                                                                        <th>GRACE PERIOD</th>
                                                                        <th>SUPERVISOR NAME</th>

                                                                        <th>IS DAILY RATE</th>
                                                                        <th>DAILY RATE</th>
                                                                        <th>IS OT FIXED RATE</th>
                                                                        <th>OT RATE</th>


                                                                        <th>EDIT</th>
                                                                        <th>DELETE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set as $data) {
                                                                    ?>
                                                                        <tr class='odd gradeX'>
                                                                            <td width='100'>
                                                                                <?php echo $data->Grp_ID; ?>
                                                                            </td>
                                                                            <td width='100'>
                                                                                <?php echo $data->EmpGroupName; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $ot_mor = ($data->Ot_m == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $ot_eve = ($data->Ot_e == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='100'>
                                                                                <?php echo $late = ($data->Late == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $late = ($data->Ed == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $data->late_Grs_prd; ?>
                                                                            </td>
                                                                            <td width='200'>
                                                                                <?php echo $data->Emp_Full_Name; ?>
                                                                            </td>


                                                                            <td width='50'>
                                                                                <?php echo $is_daily_rate = ($data->Is_Daily_Rate == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $data->Daily_Rate; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $is_ot_rate = ($data->Is_Ot_Fixed_Amount_Rate == 1) ? "YES" : "NO"; ?>
                                                                            </td>
                                                                            <td width='50'>
                                                                                <?php echo $data->Ot_Rate; ?>
                                                                            </td>


                                                                            <td width='15'>
                                                                                <?php $url = base_url() . "Master/Employee_Groups/updateAttView?id=$data->Grp_ID"; ?>
                                                                                <a class="edit_data btn btn-green"
                                                                                    href="<?php echo $url; ?>" title="EDIT">
                                                                                    <i class="fa fa-edit"></i> </a>
                                                                            </td>
                                                                            <td width='15'>
                                                                                <button class='action_comp btn btn-danger'
                                                                                    data-toggle='modal'
                                                                                    href='javascript:void()' title='DELETE'
                                                                                    onclick='delete_id([Grp_ID])'>
                                                                                    <i class='fa fa-times-circle'></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
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
                                <!-- End Grid View -->
                                <!--***************************-->
                                <!-- HIERARCHY View -->
                                <div class="tab-pane" id="tab3">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h2>COMPANY HIERARCHY OVERVIEW </h2> <button type="submit" id="ss_btn"
                                                class="btn btn-primary" style="margin-left: 10px; font-weight: bold;">Capture</button>

                                        </div>
                                    </div>
                                    <div class="tree" id="content">
                                        <!-- <h3>Company Hierarchy Overview</h3> -->
                                        <ul>
                                            <li>
                                                <span>
                                                    <?php
                                                    if (!empty($hierarchy)) {
                                                        echo htmlspecialchars($hierarchy[0]->Company_Name);
                                                    }
                                                    ?>
                                                </span>
                                                <ul>
                                                    <?php
                                                    // Default image path (relative)
                                                    $defaultImage = 'assets/images/noimage.png';

                                                    // Process hierarchy data
                                                    $groupedData = [];
                                                    foreach ($hierarchy as $row) {
                                                        $supervisorImage = !empty($row->SupervisorImage) && file_exists(FCPATH . "assets/images/Employees/" . $row->SupervisorImage)
                                                            ? "assets/images/Employees/" . $row->SupervisorImage
                                                            : $defaultImage;

                                                        $employeeImage = !empty($row->EmployeeImage) && file_exists(FCPATH . "assets/images/Employees/" . $row->EmployeeImage)
                                                            ? "assets/images/Employees/" . $row->EmployeeImage
                                                            : $defaultImage;

                                                        $groupedData[$row->EmpGroupName][$row->SupervisorName]['supervisor_image'] = $supervisorImage;
                                                        $groupedData[$row->EmpGroupName][$row->SupervisorName]['employees'][] = [
                                                            'name' => $row->EmployeeName,
                                                            'image' => $employeeImage
                                                        ];
                                                    }

                                                    // Render hierarchy tree
                                                    foreach ($groupedData as $groupName => $supervisors) {
                                                        echo "<li><span>" . htmlspecialchars($groupName) . "</span><ul>";
                                                        foreach ($supervisors as $supervisorName => $data) {
                                                            $supervisorImageURL = base_url($data['supervisor_image']);
                                                            echo "<li><span><img src='" . $supervisorImageURL . "' alt='' style='width:50px;height:50px;border-radius:50%;margin-right:5px;'>" . htmlspecialchars($supervisorName) . "</span><ul>";

                                                            foreach ($data['employees'] as $employee) {
                                                                $employeeImageURL = base_url($employee['image']);
                                                                echo "<li><span><img src='" . $employeeImageURL . "' alt='' style='width:50px;height:50px;border-radius:50%;margin-right:5px;'>" . htmlspecialchars($employee['name']) . "</span></li>";
                                                            }

                                                            echo "</ul></li>";
                                                        }
                                                        echo "</ul></li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>


                                </div>


                                <!-- End Grid View -->
                                <!--***************************-->








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

        <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <!-- <script src="<?php echo base_url(); ?>system_js/Master/Emp_Group.js"></script> -->

        <!-- Include html2pdf library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</body>

<!--snap screen-->
<script>
    const ss_button = document.getElementById('ss_btn');
    const content = document.getElementById('content');

    ss_button.addEventListener('click', async function() {
        try {
            const canvas = await html2canvas(content, {
                scale: 2,
                useCORS: true
            });
            const image = canvas.toDataURL('image/jpeg', 1.0);

            const link = document.createElement('a');
            link.href = image;
            link.download = 'Hierarchy.jpg';
            link.click();
        } catch (error) {
            console.error('Error capturing snapshot:', error.message);
        }
    });
</script>
<!--end snap screen-->

<script>
    $("#success_message_my").hide("bounce", 2000, 'fast');
    $("#submit").click(function() {
        $('#search_body').html('<center><p><img style="width: 50;height: 50;" src="<?php echo base_url(); ?>assets/images/processing.gif" /></p><center>');

    });
</script>

</html>