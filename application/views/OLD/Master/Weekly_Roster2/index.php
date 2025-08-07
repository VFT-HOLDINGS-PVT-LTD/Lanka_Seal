<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


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

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">WEEKLY ROSTER PATTERN</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW WEEKLY ROSTER PATTERN</a></li>
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
                                                            <!-- <form class="form-horizontal" id="frm_weekly_roster"
                                                                name="frm_weekly_roster"
                                                                action="<?php echo base_url(); ?>Master/Weekly_Roster2/insert_data"
                                                                method="POST" onsubmit="createShiftDataArr()"> -->
                                                            <form id="weeklyRosterForm">

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
                                                                                value="<?php echo $serial; ?>"
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
                                                                                id="txtRoster_Name"
                                                                                name="txtRoster_Name"
                                                                                placeholder="Ex: Office">
                                                                        </div>
                                                                    </div>

                                                                </div><br>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">MONDAY</label>

                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType0" name="SHType0">
                                                                                <option value="" default>-- Select
                                                                                    --</option>
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
                                                                                id="txtDayType0" name="txtDayType0"
                                                                                placeholder="">
                                                                            <input id="DType0" name="DType0" value="MON"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType0" name="txtSType0"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton"
                                                                                class="btn btn-success">+</a>
                                                                        </div>

                                                                    </div>



                                                                </div>
                                                                <div id="fieldsContainer"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">TUESDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType1" name="SHType1">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType1" name="txtDayType1"
                                                                                placeholder="">
                                                                            <input id="DType1" name="DType1" value="TUE"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType1" name="txtSType1"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton2"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div id="fieldsContainer2"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">WEDNESDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType2" name="SHType2">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType2" name="txtDayType2"
                                                                                placeholder="">
                                                                            <input id="DType2" name="DType2" value="WED"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType2" name="txtSType2"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton3"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div id="fieldsContainer3"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">THURSDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType3" name="SHType3">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType3" name="txtDayType3"
                                                                                placeholder="">
                                                                            <input id="DType3" name="DType3" value="THU"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType3" name="txtSType3"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton4"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div id="fieldsContainer4"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">FRIDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType4" name="SHType4">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType4" name="txtDayType4"
                                                                                placeholder="">
                                                                            <input id="DType4" name="DType4" value="FRI"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType4" name="txtSType4"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton5"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div id="fieldsContainer5"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">SATURDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType5" name="SHType5">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType5" name="txtDayType5"
                                                                                placeholder="">
                                                                            <input id="DType5" name="DType5" value="SAT"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType5" name="txtSType5"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton6"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div id="fieldsContainer6"></div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-2 control-label"
                                                                            style="font-weight: bold">SUNDAY</label>
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="SHType7" name="SHType7">


                                                                                <option value="" default>-- Select
                                                                                    --
                                                                                </option>
                                                                                <?php foreach ($data_set_shift as $t_data) { ?>
                                                                                    <option
                                                                                        value="<?php echo $t_data->ShiftCode; ?>">
                                                                                        <?php echo $t_data->ShiftName; ?>
                                                                                    </option>

                                                                                <?php }
                                                                                ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text" class="form-control"
                                                                                id="txtDayType7" name="txtDayType7"
                                                                                placeholder="">
                                                                            <input id="DType7" name="DType7" value="SUN"
                                                                                class="hide">
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select id="txtSType7" name="txtSType7"
                                                                                class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <a id="addButton7"
                                                                                class="btn btn-success">+</a>
                                                                        </div>
                                                                    </div>

                                                                </div>
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


                                <!--***************************-->
                                <!-- Grid View -->

                                <div class="tab-pane" id="tab2">

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
                                                                        <!-- <th>DELETE</th> -->

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($data_set as $data) {
                                                                        // if ($data->Status == 1) {

                                                                            echo "<tr class='odd gradeX'>";


                                                                            echo "<td width='100'>" . $data->RosterCode . "</td>";
                                                                            echo "<td width='100'>" . $data->RosterName . "</td>";



                                                                            echo "<td width='15'>";
                                                                            echo "<button class='get_data btn btn-green'  data-toggle='modal' data-target='#myModal' title='EDIT' data-id='$data->RosterCode' href='" . base_url() . "index.php/Master/Department/get_details" . $data->RosterCode . "'><i class='fa fa-edit'></i></button>";
                                                                            echo "</td>";

                                                                            // echo "<td width='15'>";
                                                                    
                                                                            // echo "<button  class='action_comp btn btn-danger' data-toggle='modal' href='javascript:void()' title='DELETE' onclick='delete_id($data->RosterCode)'><i class='fa fa-times-circle'></i></a>";
                                                                            // echo "<a class='action_comp btn btn-danger' data-toggle='modal' title='DELETE'  href='" . base_url() . "index.php/Master/Weekly_Roster/ajax_delete/" . $data->RosterCode . "''><i class='fa fa-times-circle'></i></a>";
                                                                    

                                                                            // echo "</td>";
                                                                    
                                                                            echo "</tr>";
                                                                        // }
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

                            </div>

                            <div id="outputContainer"></div>


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




        <!-- Load site level scripts -->

        <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

        <!-- End loading page level scripts-->

        <!--Ajax-->
        <script src="<?php echo base_url(); ?>system_js/Master/Weekly_Roster.js"></script>

        <!--JQuary Validation-->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#frm_weekly_roster").validate();
                $("#spnmessage").hide("shake", { times: 4 }, 1500);
            });
        </script>

        <!-- <script>
            document.getElementById('addButton').addEventListener('click', function () {
                // Get the container where the new fields will be added
                const container = document.getElementById('fieldsContainer');

                // Get the current count of existing fields
                const currentCount = container.childElementCount / 3; // Adjust division based on field grouping

                // Create a new HTML block with updated IDs
                const newFields = `
                <div class="form-group col-md-12">
                    <div class="form-group">
                        <label for="#" class="col-sm-2 control-label" style="font-weight: bold;"> </label>
                        <div class="col-sm-2">
                            <select class="form-control" required="" id="SHType${currentCount}" name="txtSH_MO">
                                <option value="" default>-- Select --</option>
                                <?php foreach ($data_set_shift as $t_data) { ?>
                                        <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="txtDayType${currentCount}" name="txt_shift_name" placeholder="">
                            <input id="DType${currentCount}" name="txtMon" value="MON" class="hide">
                        </div>
                        <div class="col-sm-2">
                            <select id="txtSType${currentCount}" name="txtM_SType" class="form-control">
                                <option></option>
                                <option>DU</option>
                                <option>EX</option>
                                <option>OFF</option>
                            </select>
                        </div>
                    </div>
                </div>
                `;

                // Append the new fields to the container
                container.insertAdjacentHTML('beforeend', newFields);
            });

        </script> -->

        <script>
            // MONDAY
            document.getElementById("addButton").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 6;

                if (displayCount < 14) {
                // Generate new fields dynamically
                const newFields = `
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                            <div class="col-sm-2">
                                <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                    <option value="" default>-- Select --</option>
                                    <?php foreach ($data_set_shift as $t_data) { ?>
                                                <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                            </div>
                            <div class="col-sm-2">
                                <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                    <option></option>
                                    <option>DU</option>
                                    <option>EX</option>
                                    <option>OFF</option>
                                </select>
                            </div>
                        </div>
                    </div>`;

                // Append the new fields to the container
                container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }

                
            });

            //TUESDAY
            document.getElementById("addButton2").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer2");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 14;
                if (displayCount < 22) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });

            //WEDNESDAY
            document.getElementById("addButton3").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer3");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 22;
                if (displayCount < 30) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });

            //THURSDAY
            document.getElementById("addButton4").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer4");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 30;
                if (displayCount < 38) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });

            //TUESDAY
            document.getElementById("addButton5").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer5");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 38;
                if (displayCount < 46) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });

            //TUESDAY
            document.getElementById("addButton6").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer6");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 46;
                if (displayCount < 54) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });

            //SUNDAY
            document.getElementById("addButton7").addEventListener("click", function () {
                // Get the container for fields
                const container = document.getElementById("fieldsContainer7");

                // Get the current count of rows
                const currentCount = container.querySelectorAll(".form-group").length;

                // Add 6 to the currentCount
                const displayCount = currentCount + 54;
                if (displayCount < 62) {
                    // Generate new fields dynamically
                    const newFields = `
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="font-weight: bold">NEW DAY ${displayCount}</label>
                                <div class="col-sm-2">
                                    <select class="form-control sh-type" required id="SHType${displayCount}" name="SHType${displayCount}">
                                        <option value="" default>-- Select --</option>
                                        <?php foreach ($data_set_shift as $t_data) { ?>
                                                    <option value="<?php echo $t_data->ShiftCode; ?>"><?php echo $t_data->ShiftName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtDayType${displayCount}" name="txtDayType${displayCount}" placeholder="">
                                    <input id="DType${displayCount}" name="DType${displayCount}" value="DAY${displayCount}" class="hide">
                                </div>
                                <div class="col-sm-2">
                                    <select id="txtSType${displayCount}" name="txtSType${displayCount}" class="form-control">
                                        <option></option>
                                        <option>DU</option>
                                        <option>EX</option>
                                        <option>OFF</option>
                                    </select>
                                </div>
                            </div>
                        </div>`;

                    // Append the new fields to the container
                    container.insertAdjacentHTML("beforeend", newFields);
                }else{
                    alert("You can't add more than 4 rows");
                }
            });


            // Use event delegation to handle change events dynamically
            $(document).on("change", "[id^='SHType']", function () {
                const elementIndex = this.id.replace("SHType", "");

                $.ajax({
                    type: "POST",
                    url: baseurl + "index.php/Master/Weekly_Roster/getShiftData",
                    data: { shiftCode: $("#" + this.id).val() },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $("#txtDayType" + elementIndex).val(data[0].FromTime + "-" + data[0].ToTime);
                        }
                    },
                });
            });

        </script>

        <!-- insert the controller -->
        <script>
            $(document).ready(function() {
                $("#weeklyRosterForm").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Serialize form data
                    var formData = $(this).serialize();

                    // Make an AJAX request
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>Master/Weekly_Roster2/insert_data2",
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            console.log(response); // Log the response
                            if (response.success) {
                                alert("Inserted Data: " + JSON.stringify(response.data, null, 2));

                                // Display inserted data
                                console.log("Inserted Data:", response.data);
                                window.location = "<?php echo base_url(); ?>Master/Weekly_Roster2/index";

                            } else {
                                alert("Failed to insert data.");
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                            alert("An error occurred while submitting the data." + error);
                        }
                    });
                });
            });

//             $(document).ready(function() { 
//     $("#weeklyRosterForm").submit(function(event) {
//         event.preventDefault(); // Prevent the default form submission

//         // Serialize form data
//         var formData = $(this).serialize();

//         // Make an AJAX request
//         $.ajax({
//             type: "POST",
//             url: "<?php echo base_url(); ?>Master/Weekly_Roster2/insert_data2",
//             data: formData,
//             dataType: "json",
//             success: function(response) {
//                 if (response.success) {
//                     // Render the data in HTML
//                     var displayHtml = `
//                         <h4>Roster Code: ${response.data.roster_code}</h4>
//                         <h4>Roster Name: ${response.data.roster_name}</h4>
//                         <table border="1">
//                             <thead>
//                                 <tr>
//                                     <th>Day</th>
//                                     <th>Shift Code</th>
//                                     <th>Shift Time</th>
//                                     <th>Shift Type</th>
//                                 </tr>
//                             </thead>
//                             <tbody>`;
                    
//                     response.data.shifts.forEach(function(shift) {
//                         displayHtml += `
//                             <tr>
//                                 <td>${shift.day}</td>
//                                 <td>${shift.shift_code}</td>
//                                 <td>${shift.shift_time}</td>
//                                 <td>${shift.shift_type}</td>
//                             </tr>`;
//                     });

//                     displayHtml += `
//                             </tbody>
//                         </table>`;

//                     $("#outputContainer").html(displayHtml); // Assuming you have a container with this ID
//                 } else {
//                     alert("Failed to insert data.");
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.error("An error occurred: " + error);
//                 alert("An error occurred while submitting the data." + error);
//             }
//         });
//     });
// });


        </script>


</body>


</html>