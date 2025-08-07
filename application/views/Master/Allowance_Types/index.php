<!DOCTYPE html>


<!--Description of dashboard page

@authorAshanRathsara-->


<html lang="en">



    <head>
        <!-- Styles -->
<title><?php echo $title ?></title>
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
                            <li class=""><a href="<?php echo base_url(); ?>Master/Allowance_Types/">ALLOWANCES</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">ALLOWANCES</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW ALLOWANCES</a></li>

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
                                                            <h2>ADD ALLOWANCES</h2>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal" id="frm_allowance_types"
                                                                name="frm_allowance_types"
                                                                action="<?php echo base_url(); ?>Master/Allowance_Types/insert_data"
                                                                method="POST">

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
                                                                    <div class="col-sm-8">
                                                                        <img class="imagecss"
                                                                            src="<?php echo base_url(); ?>assets/images/allowance_types.png">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-sm-6">
                                                                    <label for="focusedinput"
                                                                        class="col-sm-4 control-label">Allowance Type
                                                                        Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="txt_allowance" name="txt_allowance"
                                                                            placeholder="Ex: Fual Allowance">
                                                                    </div>

                                                                </div>


                                                                <div class="form-group col-sm-6 icheck-flat">
                                                                    <label class="col-sm-2 control-label"></label>
                                                                    <div class="col-sm-8 icheck-flat">
                                                                        <label class="checkbox green icheck col-sm-5">
                                                                            <input type="checkbox" id="isFixed"
                                                                                name="isFixed" value="1"> IS FIXED
                                                                        </label>
                                                                        <!--                                                                            <label class="checkbox-inline icheck col-sm-5">
                                                                                                                                                            <input type="checkbox" id="isActive" name="isActive" value="1"> IS ACTIVE
                                                                                                                                                        </label>-->
                                                                    </div>
                                                                </div>



                                                                <div class="row">
                                                                    <div class="col-sm-8 col-sm-offset-2">
                                                                        <button type="submit" id="submit"
                                                                            class="btn-primary btn fa fa-check">&nbsp;&nbsp;Submit</button>
                                                                        <button type="reset" id="Cancel" name="Cancel"
                                                                            class="btn btn-danger-alt fa fa-times-circle">&nbsp;&nbsp;Cancel</button>
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
                                                            <h2>ALLOWANCE DETAILS</h2>
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
                                                                        <th>ALLOWANCE NAME</th>
                                                                        <th>IS FIXED</th>
                                                                        <th>IS ACTIVE</th>
                                                                        <th>EDIT</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($data_set as $data) : ?>
                                                                    <tr class="odd gradeX">
                                                                        <td width="100"><?php echo $data->Alw_ID; ?>
                                                                        </td>
                                                                        <td width="100">
                                                                            <?php echo $data->Allowance_name; ?></td>
                                                                        <td width="100">
                                                                            <?php echo $data->isFixed == 1 ? 'Fixed' : 'Not Fixed'; ?>
                                                                        <td width="100">
                                                                            <?php echo $data->IsActive == 1 ? 'Active' : 'Inactive'; ?>
                                                                        </td>
                                                                        <td width="15">
                                                                            <button class="get_data btn btn-green"
                                                                                data-toggle="modal"
                                                                                data-target="#myModal" title="EDIT"
                                                                                data-id="<?php echo $data->Alw_ID; ?>"
                                                                                href=""><i
                                                                                    class="fa fa-edit"></i></button>
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

                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                                <h2 class="modal-title">ALLOWANCE DETAILS</h2>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal"
                                                    action="<?php echo base_url(); ?>Master/Allowance_Types/edit"
                                                    method="post">
                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput"
                                                            class="col-sm-4 control-label">ID</label>
                                                        <div class="col-sm-8">
                                                            <input value="" type="text" class="form-control"
                                                                readonly="readonly" name="id" id="id"
                                                                class="m-wrap span3">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput"
                                                            class="col-sm-4 control-label">Allowance Name</label>
                                                        <div class="col-sm-8">
                                                            <input value="" type="text" name="allowance_name"
                                                                id="A_Name" class="form-control m-wrap span6"><br>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Is
                                                            Fixed</label>
                                                        <div class="col-sm-8">
                                                            <label class="checkbox green col-sm-5">
                                                                <input type="checkbox" name="is_Fixed" id="is_Fixed">
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <label for="focusedinput" class="col-sm-4 control-label">Is
                                                            Active</label>
                                                        <div class="col-sm-8">
                                                            <label class="checkbox green col-sm-5">
                                                                <input type="checkbox" name="isActive" id="isActive">
                                                            </label>
                                                        </div>
                                                    </div>

                                            </div>

                                            <br>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" id="submit" class="btn btn-primary">Save
                                                    changes</button>
                                            </div>
                                            </form>
                                        </div>

                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->



                            </div>

                        </div> <!-- .container-fluid -->

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
            <!-- <script src="<?php echo base_url(); ?>system_js/Administration/Allowance_types.js"></script> -->

            <!-- get allowance data -->
            <script>
                $(".get_data").click(function () {
                    var id = $(this).attr("data-id");
                    $.ajax({
                        type: "POST",
                        url: baseurl + "index.php/Master/Allowance_Types/get_details",
                        data: {
                            'id': id
                        },
                        dataType: "JSON",
                        success: function (response) {
                            $('#A_Name').val(response[0].Allowance_name);
                            $('#id').val(response[0].Alw_ID);


                            if (response[0].isFixed == '1') {
                                $('#is_Fixed').prop('checked', true);
                            } else {
                                $('#is_Fixed').prop('checked', false);
                            }


                            if (response[0].IsActive == '1') {
                                $('#isActive').prop('checked', true);
                            } else {
                                $('#isActive').prop('checked', false);
                            }

                            console.log(response);
                        }
                    });
                });
            </script>

            <!--JQuary Validation-->
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#frm_designation").validate();
                    $("#spnmessage").hide("shake", {
                        times: 6
                    }, 3000);
                });

                $("#Cancel").click(function () {
                    $("#frm_allowance_types")[0].reset();
                    $("#isFixed").prop("checked", false);
                });
            </script>


</body>


</html>