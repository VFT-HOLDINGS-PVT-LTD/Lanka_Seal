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
                            <li class="active">
                                <a
                                    href="<?php echo base_url(); ?>Leave_Transaction/Leave_Allocation_Edit/updateAttView?id=<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : (isset($data_set[0]->ID) ? htmlspecialchars($data_set[0]->ID) : ''); ?>">
                                    LEAVE ALLOCATION EDIT / ID:
                                    <?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : (isset($data_set[0]->ID) ? htmlspecialchars($data_set[0]->ID) : ''); ?>
                                </a>
                            </li>
                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab1">
                                        UPDATE LEAVE ALLOCATION EDIT / ID:
                                        <?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : (isset($data_set[0]->ID) ? htmlspecialchars($data_set[0]->ID) : ''); ?>
                                    </a>
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
                                                            <h2>UPDATE LEAVE ALLOCATION EDIT</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <center>
                                                                <div class="modal-body">
                                                                    <form class="form-horizontal"
                                                                        action="<?php echo base_url(); ?>Leave_Transaction/Leave_Allocation_Edit/edit"
                                                                        method="post">

                                                                        <!--success Message-->
                                                                        <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') { ?>
                                                                        <div id="spnmessage"
                                                                            class="alert alert-dismissable alert-success">
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
                                                                            <span class="col-sm-3">ID</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->ID; ?>"
                                                                                    type="text" class="form-control"
                                                                                    readonly="readonly" name="id"
                                                                                    id="id" class="m-wrap span3">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Emp No</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->EmpNo; ?>"
                                                                                    type="text" name="EmpNo" id="EmpNo"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly="readonly"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Emp Name</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Emp_Full_Name; ?>"
                                                                                    type="text" name="EmpName"
                                                                                    id="EmpName"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly="readonly"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Leave Name</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->leave_name; ?>"
                                                                                    type="text" name="EmpNo" id="EmpNo"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly="readonly"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Year</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Year; ?>"
                                                                                    type="text" name="Year" id="Year"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Entitle</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Entitle; ?>"
                                                                                    type="text" name="Entitle"
                                                                                    id="Entitle"
                                                                                    class="form-control m-wrap span6"><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Used</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Used; ?>"
                                                                                    type="text" name="Used" id="Used"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly><br>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-sm-12">
                                                                            <span class="col-sm-3">Balance</span>
                                                                            <div class="col-sm-6">
                                                                                <input
                                                                                    value="<?php echo $data_set[0]->Balance; ?>"
                                                                                    type="text" name="Balance"
                                                                                    id="Balance"
                                                                                    class="form-control m-wrap span6"
                                                                                    readonly><br>
                                                                            </div>
                                                                        </div>


                                                                        <div class="form-group col-sm-12">
                                                                            <div class="col-sm-offset-3 col-sm-6">
                                                                                <button type="submit"
                                                                                    class="btn btn-primary"
                                                                                    name="submit"
                                                                                    id="submit">Update</button>
                                                                            </div>
                                                                        </div>

                                                                    </form>
                                                                </div>

                                                            </center>

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
                $("[id^='Day'").each(function () {

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