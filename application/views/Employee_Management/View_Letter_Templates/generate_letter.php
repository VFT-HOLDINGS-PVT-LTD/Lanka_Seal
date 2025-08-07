<!DOCTYPE html>
<html lang="en">

<head>
    <title>Letter Templates</title>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
</head>

<body class="infobar-offcanvas">

    <!-- Header -->
    <?php $this->load->view('template/header.php'); ?>

    <div id="wrapper">
        <div id="layout-static">

            <!-- Sidebar -->
            <?php $this->load->view('template/dashboard_side.php'); ?>

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li><a href="<?php echo base_url(); ?>Employee_Management/View_Letter_Templates">LETTER
                                    TEMPLATES</a></li>
                            <li class="active">GENERATE LETTER</li>
                        </ol>

                        <div class="container-fluid">
                            <div class="tab-content">


                                <!-- Tab 1: View Letter Template -->
                                <div id="view_template_tab" class="tab-pane fade in active">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h2>GENERATE LETTER | <span><?=$template->template_name ?></span></h2>
                                        </div>
                                        <div class="panel-body">

                                            <form class="form-horizontal" id="frm_employee_view"
                                                name="frm_employee_view"
                                                action="<?= base_url('Employee_Management/View_Letter_Templates/fill_letter/' . $id) ?>"
                                                method="POST">
                                                <!-- Success Message -->
                                                <?php if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') : ?>
                                                <div id="spnmessage"
                                                    class="alert alert-success alert-dismissible fade in" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <strong>Success!</strong>
                                                    <?= $_SESSION['success_message'] ?>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <label for="txt_emp" class="col-sm-4 control-label">Employee
                                                            No</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="txt_emp"
                                                                id="txt_emp" placeholder="Ex: 0001">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="txt_emp_name"
                                                            class="col-sm-4 control-label">Employee Name</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="txt_emp_name"
                                                                id="txt_emp_name" placeholder="Ex: Ashan">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row text-center">
                                                    <input type="submit" id="search" name="search"
                                                        class="btn-green btn fa fa-check"
                                                        value="&nbsp;&nbsp;GENERATE&nbsp; LETTER">
                                                    <input type="button" id="cancel" name="cancel"
                                                        class="btn-danger-alt btn fa fa-check"
                                                        value="&nbsp;&nbsp;CLEAR">
                                                </div>
                                            </form>

                                            <hr>

                                            <div id="divmessage" class="text-center">
                                                <div id="spnmessage"></div>
                                            </div>

                                            <div class="letter-body">
                                                <?php if (isset($template)) : ?>
                                                <div
                                                    style="border: 1px solid #ccc; padding: 20px; border-radius: 4px; background-color: #F9F9F9; margin-bottom: 20px;">
                                                    <p><strong>Subject:</strong>
                                                        <?= $template->letter_subject ?>
                                                    </p>
                                                    <?= $template->letter_body ?>
                                                </div>
                                                <?php else : ?>
                                                <p style="text-align: center; color: #555;">Select a template to view
                                                    from the "View Letter Templates" tab.</p>
                                                <?php endif; ?>

                                            </div>

                                            <button onclick="printLetterBody()" class="btn btn-success">
                                                <i class="fa fa-print"></i> Print
                                            </button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Load site level scripts -->

    <?php $this->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

    <!-- End loading page level scripts-->

    <!--Ajax-->
    <!--<script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>-->


    <!--JQuary Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_designation").validate();
            $("#spnmessage").hide(5000);
        });
    </script>

    <!--Clear Text Boxes-->
    <script type="text/javascript">

        $("#cancel").click(function () {

            $("#txt_emp").val("");
            $("#txt_emp_name").val("");
            $("#cmb_desig").val("");
            $("#cmb_dep").val("");
            $("#cmb_comp").val("");
            $("#txt_nic").val("");
            $("#cmb_gender").val("");
            $("#cmb_status").val("");


        });
    </script>

    <script>

        $(function () {
            $('#from_date').datepicker(
                {
                    "setDate": new Date(),
                    "autoclose": true,
                    "todayHighlight": true,
                    format: 'yyyy/mm/dd'
                });

            $('#to_date').datepicker(
                {
                    "setDate": new Date(),
                    "autoclose": true,
                    "todayHighlight": true,
                    format: 'yyyy/mm/dd'
                });

        });
        $("#success_message_my").hide("bounce", 5000, 'fast');


        $("#search").click(function () {
            $('#search_body').html('<center><p><img style="width: 50;height: 50;" src="<?php echo base_url(); ?>assets/images/icon-loading.gif" /></p><center>');
            $('#search_body').load("<?php echo base_url(); ?>Employee_Management/View_Employees/search_employee", { 'txt_emp': $('#txt_emp').val(), 'txt_emp_name': $('#txt_emp_name').val(), 'cmb_desig': $('#cmb_desig').val(), 'cmb_dep': $('#cmb_dep').val(), 'txt_nic': $('#txt_nic').val(), 'cmb_status': $('#cmb_status').val(), 'cmb_branch': $('#cmb_branch').val(), 'cmb_gender': $('#cmb_gender').val() });
        });


    </script>
    <!--Auto complete-->
    <script type="text/javascript">
        $(function () {
            $("#txt_emp_name").autocomplete({
                source: "<?php echo base_url(); ?>Employee_Management/View_Employees/get_auto_emp_name" // path to the get_birds method
            });
        });

        $(function () {
            $("#txt_emp").autocomplete({
                source: "<?php echo base_url(); ?>Employee_Management/View_Employees/get_auto_emp_no" // path to the get_birds method
            });
        });
    </script>

    <!-- Print Letter Body -->
    <script>
        function printLetterBody() {
            var printContents = document.querySelector('.letter-body').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Reload to restore the original state
        }
    </script>

</body>

</html>