<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">


<head>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <!-- <style>
        
        .calendar-row {
            display: flex;
        }

        .day, .day-header {
            flex: 1 1 14.2857%;
            max-width: 14.2857%;
            background-color: white;
            border: 1px solid #ddd;
            padding: 5px;
            box-sizing: border-box;
        }

        .day-header {
            background-color: #d1c4e9;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .day {
            height: 160px;
            position: relative;
        }

        .event {
            background-color: #03a9f4;
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .today {
            background-color: #f44336;
            color: white;
            font-weight: bold;
        }
    </style> -->
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
                                                                            value="<?= $RosterCode ?>"
                                                                            class="form-control" id="txtRoster_Code"
                                                                            name="txtRoster_Code" placeholder="">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-2 control-label">Month</label>
                                                                    <div class="col-sm-8">
                                                                        <select id="txtMType" name="txt_MType"
                                                                            readonly="" class="form-control">
                                                                            <option>
                                                                                <?= $MonthType ?>
                                                                            </option>
                                                                        </select>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-2 control-label">Category</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            value="<?= $Data ?>" readonly=""
                                                                            id="txtRoster_Data" name="txtRoster_Data"
                                                                            placeholder="Ex: Office">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-4">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">
                                                                        ID/Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            value="<?= $RosterName ?>" readonly=""
                                                                            id="txtRoster_Name" name="txtRoster_Name"
                                                                            placeholder="Ex: Office">
                                                                    </div>
                                                                </div>

                                                            </div><br>

                                                            <?php

                                                            $x = -1;

                                                            foreach ($datesOfThisMonth as $date) {

                                                                $x = $x + 1;

                                                                ?>

                                                                <div class="form-group col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="#" class="col-sm-3 control-label"
                                                                            style="font-weight: bold">
                                                                            <?= $date['date'] ?> - (
                                                                            <?= $date['day'] ?>)
                                                                        </label>
                                                                        <input type="text" name=""
                                                                            value="<?= $date['date'] ?>"
                                                                            id="<?php echo "txtDay" . $x ?>" class="hidden">
                                                                        <div class="col-sm-2">
                                                                            <select class="form-control" required=""
                                                                                id="<?php echo "SHType" . $x ?>"
                                                                                name="txtSH_MO">

                                                                                <option value="" default>-- Select --
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
                                                                                id="<?php echo "txtDayType" . $x ?>"
                                                                                name="txt_shift_name" placeholder=""
                                                                                readonly>
                                                                            <input id="<?php echo "DType" . $x ?>"
                                                                                name="txtMon" value="<?= $date['day'] ?>"
                                                                                class="hide">
                                                                        </div>



                                                                        <div class="col-sm-2">
                                                                            <select id="<?php echo "txtSType" . $x ?>"
                                                                                name="txtM_SType" class="form-control">
                                                                                <option></option>
                                                                                <option>DU</option>
                                                                                <option>EX</option>
                                                                                <option>OFF</option>
                                                                            </select>
                                                                        </div>
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

                                <div class="tab-pane" id="tab2">

                                    <div id="wrap">
                                        <div class="container3">
                                            <!-- Calendar Body -->
                                            <?php
                                            // Example data for dates and days
                                            $daysInWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                            $dayIndexMap = array_flip($daysInWeek);

                                            // Initialize the day counter
                                            $x = 0;

                                            echo '<div class="calendar-row">';

                                            // Add empty divs for days before the start date
                                            for ($i = 0; $i < $x; $i++) {
                                                echo '<div class="day"></div>';
                                            }

                                            // Loop through the dates
                                            foreach ($datesOfThisMonth as $date) {
                                                if ($x % 7 === 0 && $x > 0) {
                                                    echo '</div><div class="calendar-row">'; // Start a new row after every 7 days
                                                }
                                                ?>
                                                <div class="day">
                                                    <span style="font-weight: bold;"><?php echo $date['date']; ?> -
                                                        (<?php echo $date['day']; ?>)</span>
                                                    <input type="text" name="" value="<?php echo $date['date']; ?>"
                                                        id="<?php echo "txtDay" . $x; ?>" class="hidden">
                                                    <div class="col-12">
                                                        <select class="form-control" required=""
                                                            id="<?php echo "SHType" . $x; ?>" name="txtSH_MO">
                                                            <option value="" default>-- Select --</option>
                                                            <?php foreach ($data_set_shift as $t_data) { ?>
                                                                <option value="<?php echo $t_data->ShiftCode; ?>">
                                                                    <?php echo $t_data->ShiftName; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control"
                                                            id="<?php echo "txtDayType" . $x; ?>" name="txt_shift_name"
                                                            placeholder="" readonly>
                                                        <input id="<?php echo "DType" . $x; ?>" name="txtMon"
                                                            value="<?php echo $date['day']; ?>" class="hide">
                                                    </div>
                                                    <div class="col-12">
                                                        <select id="<?php echo "txtSType" . $x; ?>" name="txtM_SType"
                                                            class="form-control">
                                                            <option></option>
                                                            <option>DU</option>
                                                            <option>EX</option>
                                                            <option>OFF</option>
                                                        </select>
                                                    </div>
                                                    <!-- You can add events or other content here -->
                                                </div>
                                                <?php
                                                $x++;
                                            }

                                            // Fill the remaining days in the last row with empty divs if necessary
                                            while ($x % 7 !== 0) {
                                                echo '<div class="day"></div>';
                                                $x++;
                                            }

                                            echo '</div>'; // Close the last row
                                            ?>
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

            <script>
                document.getElementById('submit').addEventListener('click', function () {
                    // Extract data from fields
                    var rosterCode = document.getElementById('txtRoster_Code').value;
                    var monthType = document.getElementById('txtMType').value;
                    var rosterName = document.getElementById('txtRoster_Name').value;
                    var rosterData = document.getElementById('txtRoster_Data').value;

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
                    console.log(dates);

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

                    // Show the loading spinner
                    document.getElementById('loadingSpinner').style.display = 'block';

                    // Send data via AJAX
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?php echo base_url(); ?>Master/Weekly_Roster/insert_data', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            // Hide the loading spinner
                            document.getElementById('loadingSpinner').style.display = 'none';

                            if (xhr.status === 200) {
                                // Display success alert with SweetAlert
                                console.log(xhr.responseText);
                                alert(xhr.responseText);
                                window.location = "<?php echo base_url(); ?>Master/Weekly_Roster/index";
                                // swal({
                                //     title: "Success!",
                                //     text: "Data has been successfully inserted.",
                                //     icon: "success",
                                //     timer: 5000, // Display for 5 seconds
                                //     button: false // Hide the "OK" button
                                // }).then(() => {
                                //     // Redirect after success
                                //     window.location = "<?php echo base_url(); ?>Master/Weekly_Roster/index";
                                // });
                            } else {
                                alert("An error occurred: " + xhr.statusText);
                            }
                        }
                    };

                    xhr.send(JSON.stringify(data));
                });

            </script>


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

</body>


</html>