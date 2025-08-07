<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">

<title><?php echo $title ?></title>

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

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a href="<?php echo base_url(); ?>Leave_Transaction/Leave_Allocation_Edit/">LEAVE ALLOCATION
                                    EDIT</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <!-- <li class="active"><a data-toggle="tab" href="#tab1">LEAVE ALLOCATION EDIT</a></li> -->
                                <li class="active"><a data-toggle="tab" href="#tab1">VIEW LEAVE ALLOCATION EDIT</a></li>


                            </ul>
                        </div>
                        <div class="container-fluid">


                            <div class="tab-content">

                                <!--***************************-->
                                <!-- Grid View -->
                                <div class="tab-pane active" id="tab1">

                                    <!--success Message-->
                                    <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                    <div id="spnmessage" class="alert alert-dismissable alert-success">
                                        <strong>Success !</strong>
                                        <?php echo $_SESSION['success_message'] ?>
                                    </div>
                                    <?php } ?>

                                    <!--Error Message-->
                                    <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') { ?>
                                    <div id="spnmessage" class="alert alert-dismissable alert-danger">
                                        <strong>Error !</strong>
                                        <?php echo $_SESSION['error_message'] ?>
                                    </div>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h2>LEAVE DETAILS</h2>
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
                                                                        <th>EmpNo</th>
                                                                        <th>Emp Name</th>
                                                                        <th>Leave Name</th>
                                                                        <th>Year</th>
                                                                        <th>Entitle</th>
                                                                        <th>Used</th>
                                                                        <th>Balance</th>
                                                                        <th>EDIT</th>
                                                                        <th>DELETE</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($data_set as $data): ?>
                                                                    <tr class="odd gradeX">
                                                                        <td width="100"><?php echo $data->ID; ?></td>
                                                                        <td width="100"><?php echo $data->EmpNo; ?></td>
                                                                        <td width="100">
                                                                            <?php echo $data->Emp_Full_Name; ?></td>
                                                                        <td width="100"><?php echo $data->leave_name; ?>
                                                                        </td>
                                                                        <td width="100"><?php echo $data->Year; ?></td>
                                                                        <td width="100"><?php echo $data->Entitle; ?>
                                                                        </td>
                                                                        <td width="100"><?php echo $data->Used; ?></td>
                                                                        <td width="100"><?php echo $data->Balance; ?>
                                                                        </td>
                                                                        <td width="15">
                                                                            <?php $url = base_url() . "Leave_Transaction/Leave_Allocation_Edit/updateAttView?id=$data->ID"; ?>
                                                                            <a class="edit_data btn btn-green"
                                                                                href="<?php echo $url; ?>" title="EDIT">
                                                                                <i class="fa fa-edit"></i> </a>
                                                                        </td>

                                                                        <td width="15">
                                                                            <?php $url = base_url() . "Leave_Transaction/Leave_Allocation_Edit/Delete?id=$data->ID"; ?>
                                                                            <a class="edit_data btn btn-danger"
                                                                                href="<?php echo $url; ?>" title="EDIT">
                                                                                <i class="fa fa-times-circle"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; ?>

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

                                <!-- End Grid View-->


                            </div>

                        </div> <!-- .container-fluid -->
                    </div>
                    <!--Footer-->
                    <?php $this->load->view('template/footer.php'); ?>
                    <!--End Footer-->
                </div>
            </div>
        </div>
    </div>


    <!-- Load site level scripts -->

    <?php $this->load->view('template/js.php'); ?>
    <!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->

    <!--Ajax-->
    <script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>

    <!--JQuary Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_designation").validate();
            $("#spnmessage").hide("shake", {
                times: 6
            }, 3000);
        });
    </script>

</body>


</html>