<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->

<!--<script src="<?php echo base_url(); ?>system_js/Cheque/new.js"></script>
<script src="<?php echo base_url(); ?>system_js/Cheque/toword.js"></script>-->
<html lang="en">



<head>
    <title><?php echo "Charts | Attendance Analysis"; ?></title>
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
                            <li class="active"><a href="<?php echo base_url(); ?>Reports/Analysis/Attendance_Analysis_Chart/">ATTENDANCE ANALYSIS CHART</a></li>

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
                                                            <h2>ATTENDANCE ANALYSIS CHART</h2>
                                                        </div>


                                                        <!DOCTYPE HTML>
                                                        <html>

                                                        <head>
                                                            <script type="text/javascript"
                                                                src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
                                                            </script>

                                                        <script type="text/javascript">
                                                            $(function () {
                                                                var attendanceSummary = <?php echo $attendance_summary; ?>;

                                                                Highcharts.chart('container', {
                                                                    chart: {
                                                                        type: 'column',
                                                                        height: 400
                                                                    },
                                                                    title: {
                                                                        text: "Today's Attendance Summary"
                                                                    },
                                                                    xAxis: {
                                                                        categories: ['Attendance'],
                                                                        title: {
                                                                            text: 'Status'
                                                                        }
                                                                    },
                                                                    yAxis: {
                                                                        min: 0,
                                                                        title: {
                                                                            text: 'Number of Employees'
                                                                        }
                                                                    },
                                                                    tooltip: {
                                                                        formatter: function () {
                                                                            return '<b>' + this.series.name + ':</b> ' + this.y + ' employees';
                                                                        }
                                                                    },
                                                                    plotOptions: {
                                                                        column: {
                                                                            dataLabels: {
                                                                                enabled: true
                                                                            },
                                                                            grouping: true,
                                                                            pointPadding: 0.2,
                                                                            groupPadding: 0.1
                                                                        }
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: 'Present',
                                                                            data: [attendanceSummary.find(item => item.name === 'Present').y],
                                                                            color: '#28a745'
                                                                        },
                                                                        {
                                                                            name: 'Absent',
                                                                            data: [attendanceSummary.find(item => item.name === 'Absent').y],
                                                                            color: '#007bff'
                                                                        }
                                                                    ]
                                                                });
                                                            });
                                                        </script>

                                                        <script type="text/javascript">
                                                            $(function () {
                                                                var weeklyAttendance = <?php echo $weekly_attendance; ?>;

                                                                // Extract data for chart
                                                                var dates = weeklyAttendance.map(item => item.date);
                                                                var presentData = weeklyAttendance.map(item => item.present);
                                                                var absentData = weeklyAttendance.map(item => item.absent);

                                                                Highcharts.chart('container', {
                                                                    chart: {
                                                                        type: 'column',
                                                                        height: 400
                                                                    },
                                                                    title: {
                                                                        text: "Weekly Attendance Summary"
                                                                    },
                                                                    xAxis: {
                                                                        categories: dates,
                                                                        title: {
                                                                            text: 'Date'
                                                                        }
                                                                    },
                                                                    yAxis: {
                                                                        min: 0,
                                                                        title: {
                                                                            text: 'Number of Employees'
                                                                        }
                                                                    },
                                                                    tooltip: {
                                                                        shared: true,
                                                                        formatter: function () {
                                                                            let tooltip = `<b>${this.x}</b><br/>`;
                                                                            this.points.forEach(point => {
                                                                                tooltip += `${point.series.name}: <b>${point.y}</b><br/>`;
                                                                            });
                                                                            return tooltip;
                                                                        }
                                                                    },
                                                                    plotOptions: {
                                                                        column: {
                                                                            dataLabels: {
                                                                                enabled: true
                                                                            },
                                                                            grouping: true,
                                                                            pointPadding: 0.2,
                                                                            groupPadding: 0.1
                                                                        }
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: 'Present',
                                                                            data: presentData,
                                                                            color: '#28a745'
                                                                        },
                                                                        {
                                                                            name: 'Absent',
                                                                            data: absentData,
                                                                            color: '#007bff'
                                                                        }
                                                                    ]
                                                                });
                                                            });
                                                        </script>
                                                        
                                                        </head>

                                                        <body>

                                                            <form
                                                                action="<?php echo base_url(); ?>Reports/Analysis/Attendance_Analysis_Chart/filter_attendace_data"
                                                                class="form-horizontal" id="frm_in_out_rpt"
                                                                name="frm_in_out_rpt" method="POST">

                                                                <div class="form-group col-md-12"
                                                                    style="margin-top: 50px;margin-bottom: 50px;">

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
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-sm-3">
                                                                    <label for="focusedinput" class="col-sm-4 control-label">Filter On</label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="fiter_time_range" name="fiter_time_range" required>
                                                                                <option value="">--Select--</option>
                                                                                <option value="1">DAILY</option>
                                                                                <option value="2">WEEKLY</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <input type="submit" id="search" name="search"
                                                                            formtarget="_new"
                                                                            class="btn-green btn fa fa-check"
                                                                            value="&nbsp;&nbsp;FILTER&nbsp; CHART">
                                                                    </div>

                                                                </div>

                                                            </form>
                                                            <hr>

                                                            <div id="container"
                                                                style="max-width: 1200px; min-height: 300px; margin: 0 auto;">
                                                            </div>

                                                        </body>

                                                        </html>

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

    <!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->
    <!-- Load site level scripts -->
    <?php get_instance()->load->view('template/js.php'); ?>
    <!-- Initialize scripts for this page-->



    <script src="<?php echo base_url(); ?>assets/plugins/highcharts/exporting.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts-3d.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts.js" type="text/javascript"></script>


</body>


</html>