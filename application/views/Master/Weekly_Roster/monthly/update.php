<!DOCTYPE html>


<!--Description of dashboard page

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

                            <li class=""><a href="index.html">HOME</a></li>
                            <li class="active"><a href="index.html">MONTHLY ROSTER PATTERN</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">MONTHLY ROSTER PATTERN</a></li>
                                <!-- <li><a data-toggle="tab" href="#tab2">VIEW MONTHLY ROSTER PATTERN</a></li> -->
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
                                                            <h2>ADD MONTHLY ROSTER PATTERN</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <!-- <form class="form-horizontal" id="frm_weekly_roster"
                                                                name="frm_weekly_roster"
                                                                action="<?php echo base_url(); ?>Master/Weekly_Roster/insert_data"
                                                                method="POST" onsubmit="createShiftDataArr()"> -->

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

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">Roster
                                                                        Code</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" readonly=""
                                                                            value="<?php echo $RosterCode; ?>"
                                                                            class="form-control" id="txtRoster_Code"
                                                                            name="txtRoster_Code" placeholder="">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">Month
                                                                        Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            value="<?php echo $MonthType; ?>"
                                                                            readonly="" id="txtMType" name="txtMType"
                                                                            placeholder="Ex: Office">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">Category</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control" required
                                                                            id="cmb_cat" name="cmb_cat"
                                                                            onchange="selctcity()">
                                                                            <option value="" default
                                                                                style="background-color: #e9e9e9;">
                                                                                <?php echo $Data; ?>
                                                                            </option>
                                                                            <option value="Individual Employee">
                                                                                Individual Employee
                                                                            </option>
                                                                            <option value="OnlyGroup">Only Group
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div id="dynamic-fields"></div>


                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">
                                                                        Old ID/Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            value="<?php echo $RosterName; ?>"
                                                                            readonly="" placeholder="Ex: Office">
                                                                    </div>
                                                                </div>

                                                            </div><br>

                                                            <?php

                                                            $x = -1;

                                                            foreach ($emp_roster as $newData) {

                                                                $x = $x + 1;

                                                                ?>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-3 control-label"
                                                                            style="font-weight: bold">
                                                                            <?php echo $newData->Date ?> - (
                                                                            <?php echo $newData->DayName ?>)
                                                                        </label>

                                                                    </div>
                                                                    <input type="text" name=""
                                                                        value=" <?php echo $newData->Date ?>"
                                                                        id="<?php echo "txtDay" . $x ?>" class="hidden">
                                                                    <div class="col-sm-2">
                                                                        <select class="form-control" required=""
                                                                            id="<?php echo "SHType" . $x ?>"
                                                                            name="txtSH_MO">

                                                                            <option style="background-color: #eeeeee;"
                                                                                value="<?php echo $newData->ShiftCode; ?>">
                                                                                <?php echo $newData->ShiftName; ?></option>

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
                                                                            id="<?php echo "txtDayType" . $x ?>"
                                                                            value="<?php echo $newData->FromTime; ?> - <?php echo $newData->ToTime; ?>"
                                                                            name="txt_shift_name" placeholder="" readonly>

                                                                        <input id="<?php echo "DType" . $x ?>" name="txtMon"
                                                                            value="<?php echo $newData->DayName ?>"
                                                                            class="hide">
                                                                    </div>



                                                                    <div class="col-sm-2">
                                                                        <select id="<?php echo "txtSType" . $x ?>"
                                                                            name="txtM_SType" class="form-control">
                                                                            <option
                                                                                value="<?php echo $newData->ShiftType; ?>">
                                                                                <?php echo $newData->ShiftType; ?></option>
                                                                            <option>DU</option>
                                                                            <option>EX</option>
                                                                            <option>OFF</option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                                <?php
                                                            }
                                                            ?>

                                                            <!--Hidden Text-->
                                                            <input type="text" name="hdntext" id="hdntext"
                                                                class="hidden">


                                                            <button type="submit" id="submit" name="submit"
                                                                style="margin-left: 22px;"
                                                                class="btn-primary btn fa fa-check">SUBMIT</button>

                                                            <!--submit button-->
                                                            <!-- <?php $this->load->view('template/btn_submit.php'); ?> -->
                                                            <!--end submit-->


                                                            <!-- </form> -->
                                                            <hr>


                                                            <div id="divmessage" class="">

                                                                <div id="spnmessage"> </div>
                                                            </div>

                                                            <!-- Add a loading spinner in HTML -->
                                                            <div id="loadingSpinner" style="display: none;">
                                                                Loading...
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

                                <!-- <div class="tab-pane" id="tab2">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h2>WEEKLY ROSTER PATTERN DETAILS</h2>
                                                            <div class="panel-ctrls">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body panel-no-padding">
                                                            <table id="example"
                                                                class="table table-striped table-bordered"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>

                                                                        <th>ROSTER CODE</th>
                                                                        <th>ROSTER NAME</th>

                                                                        <th>EDIT</th>
                                                                        <th>DELETE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set as $data) {

                                                                        echo "<tr class='odd gradeX'>";

                                                                        echo "<td width='100'>" . $data->RosterCode . "</td>";
                                                                        echo "<td width='100'>" . $data->RosterName . "</td>";

                                                                        echo "<td width='15'>";
                                                                        echo "<button class='get_data btn btn-green'  data-toggle='modal' data-target='#myModal' title='EDIT' data-id='$data->RosterCode' href='" . base_url() . "index.php/Master/Department/get_details" . $data->RosterCode . "'><i class='fa fa-edit'></i></button>";
                                                                        echo "</td>";

                                                                        echo "<td width='15'>";

                                                                        echo "<button  class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->RosterCode)'><i class='fa fa-times-circle'></i></a>";

                                                                        echo "</td>";

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

                                </div> -->


                                <!-- End Grid View -->
                                <!--***************************-->

                            </div>


                            <!-- Modal -->
                            <!--                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h2 class="modal-title">SHIFTS</h2>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="<?php echo base_url(); ?>Master/Shifts/edit" method="post">
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">SHIFT CODE</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->ID; ?>" type="text" class="form-control" readonly="readonly" name="ShiftCode" id="ShiftCode" class="m-wrap span3" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">NAME</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->ShiftName; ?>" type="text" name="ShiftName" id="ShiftName"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">FROM TIME</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->FromTime; ?>" type="time" name="FromTime" id="FromTime"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">TO TIME</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->ToTime; ?>" type="time" name="ToTime" id="ToTime"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">SHIFT GAP</label>
                                                        <div class="col-sm-8">
                                                            <input value="<?php echo $data->ShiftGap; ?>" type="text" name="ShiftGap" id="ShiftGap"  class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>


                                            </div>

                                            <br>
                                            <input class="btn green" type="submit" value="submit" id="submit">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                            </form>
                                        </div>

                                    </div> /.modal-content
                                </div> /.modal-dialog -->



                        </div> <!-- .container-fluid -->
                    </div>

                    <!--Footer-->
                    <?php $this->load->view('template/footer.php'); ?>
                    <!--End Footer-->

                </div>
            </div>
        </div>

        <script>

            document.getElementById('submit').addEventListener('click', function () {
                // Extract data from fields
                var rosterCode = document.getElementById('txtRoster_Code').value;
                var monthType = document.getElementById('txtMType').value;
                var rosterName = document.getElementById('txt_nic').value;
                var rosterData = document.getElementById('cmb_cat').value;

                // Validation
                if (!rosterCode) {
                    alert("Please enter a valid Roster Code.");
                    return;
                } else if (!rosterName) {
                    alert("Please enter a valid Roster Name.");
                    return;
                } else if (!monthType) {
                    alert("Please select a valid Month Type.");
                    return;
                }

                var dates = [];

                // Extract data from looped fields
                <?php foreach ($datesOfThisMonth as $index => $date) { ?>
                    var shType = document.getElementById('SHType<?= $index ?>').value;
                    var dayType = document.getElementById('txtDayType<?= $index ?>').value;
                    var dType = document.getElementById('DType<?= $index ?>').value;
                    var todayType = document.getElementById('txtDay<?= $index ?>').value;
                    var SType = document.getElementById('txtSType<?= $index ?>').value;

                    // Push data into array
                    dates.push({
                        shType: shType,
                        dayType: dayType,
                        SType: SType,
                        dType: dType,
                        todayType: todayType,
                    });
                <?php } ?>

                // Prepare data to send
                var data = {
                    rosterCode: rosterCode,
                    monthType: monthType,
                    rosterName: rosterName,
                    rosterData: rosterData,
                    dates: dates
                };

                console.log(data);

                // Show the loading spinner
                document.getElementById('loadingSpinner').style.display = 'block';

                // Send data via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo base_url(); ?>Master/Weekly_Roster/update_data', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        // Hide the loading spinner once the request completes
                        document.getElementById('loadingSpinner').style.display = 'none';

                        if (xhr.status === 200) {
                            // Handle response from server
                            console.log(xhr.responseText);
                            alert(xhr.responseText);
                            window.location = "<?php echo base_url(); ?>Master/Weekly_Roster/index";
                        } else {
                            alert("An error occurred: " + xhr.statusText);
                        }
                    }
                };

                xhr.send(JSON.stringify(data));
            });
        </script>
        </script>


        <!-- Load site level scripts -->

        <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <script src="<?php echo base_url(); ?>system_js/Master/Weekly_Roster.js"></script>

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

                    if (selectedValue === 'Individual Employee') {
                        dynamicFields.html(`
                        <div class="form-group col-sm-4">
                            <label for="" class="col-sm-4 control-label">New Emp Number</label>
                            <div class="col-sm-8">
                                <select type="text" required="required" autocomplete="off" class="form-control txt_nic itemName" name="txt_nic" id="txt_nic" placeholder="">
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
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
                    }
                    // else {
                    //     dynamicFields.html(`
                    //         <div class="form-group col-sm-4">
                    //             <label for="" class="col-sm-4 control-label">Select</label>
                    //             <div class="col-sm-8" id="cat_div">
                    //                 <select class="form-control" required id="cmb_cat2" name="cmb_cat2">
                    //                 </select>
                    //             </div>
                    //         </div>
                    //     `);

                    //     $.post('<?php echo base_url(); ?>index.php/Pay/Allowance/dropdown/', { cmb_cat: selectedValue }, function (data) {
                    //         $('#cmb_cat2').html(data);
                    //     });
                    // }

                    if (selectedValue === 'OnlyGroup') {
                        dynamicFields.html(`
                        <div class="form-group col-sm-4">
                                                                        <label for="focusedinput"
                                                                            class="col-sm-4 control-label">New ID/Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" 
                                                                                id="txt_nic"
                                                                                name="txt_nic" value="<?php echo $RosterName; ?>"
                                                                                placeholder="Ex: Office">
                                                                        </div>
                                                                    </div>
                    `);
                    }
                });

                $("#cmb_cat").trigger("change");
            });
        </script>

        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_weekly_roster").validate();
                $("#spnmessage").hide("shake", { times: 4 }, 1500);
            });
        </script>

</body>


</html>