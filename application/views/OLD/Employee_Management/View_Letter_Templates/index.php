<!DOCTYPE html>

<html lang="en">

<title>
    <?php echo "Letter tempaltes"; ?>
</title>

<head>
    <!-- Styles -->
    <?php get_instance()->load->view('template/css.php'); ?>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .cke_notification_warning {
            display: none !important;
        }
    </style>
</head>

<body class="infobar-offcanvas">

    <!--header-->

    <?php get_instance()->load->view('template/header.php'); ?>

    <!--end header-->

    <div id="wrapper">
        <div id="layout-static">

            <!--dashboard side-->

            <?php get_instance()->load->view('template/dashboard_side.php'); ?>

            <!--dashboard side end-->

            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">

                            <li class=""><a href="<?php echo base_url(); ?>Dashboard/">HOME</a></li>
                            <li class="active"><a
                                    href="<?php echo base_url(); ?>Employee_Management/View_Letter_Templates">LETTER
                                    TEMPLATES</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">CREATE LETTER TEMPLATE</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW LETTER TEMPLATES </a></li>


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
                                                            <h2>ADD TEMPLATE</h2>
                                                        </div>
                                                        <div
                                                            style="max-width: 100%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                                            <h3 style="text-align: center; color: #444;">Create a New
                                                                Letter Template</h3>

                                                            <!-- Display success or error messages -->
                                                            <?php if ($this->session->flashdata('success')): ?>
                                                                <p
                                                                    style="color: green; text-align: center; margin-bottom: 20px;">
                                                                    <?php echo $this->session->flashdata('success'); ?>
                                                                </p>
                                                            <?php elseif ($this->session->flashdata('error')): ?>
                                                                <p
                                                                    style="color: red; text-align: center; margin-bottom: 20px;">
                                                                    <?php echo $this->session->flashdata('error'); ?>
                                                                </p>
                                                            <?php endif; ?>

                                                            <?php echo form_open('Employee_Management/View_Letter_Templates/save'); ?>

                                                            <label
                                                                style="font-weight: bold; display: block; margin-bottom: 5px;">Template
                                                                Name</label>
                                                            <input type="text" name="template_name" required
                                                                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">

                                                            <label
                                                                style="font-weight: bold; display: block; margin-bottom: 5px;">Letter
                                                                Subject</label>
                                                            <input type="text" name="letter_subject" required
                                                                style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">

                                                            <label
                                                                style="font-weight: bold; display: block; margin-bottom: 5px;">Letter
                                                                Body</label>
                                                            <textarea name="letter_body" id="letter_body"
                                                                style="width: 100%; height: 500px; resize: none; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;"></textarea>

                                                            <script
                                                                src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
                                                            <script>
                                                                document.addEventListener("DOMContentLoaded", function () {
                                                                    CKEDITOR.replace('letter_body', {
                                                                        height: 500,
                                                                        resize_enabled: false
                                                                    });
                                                                });
                                                            </script>
                                                            <div style="margin-top: 20px;">
                                                                <p><strong>You can use the following
                                                                        placeholders:</strong></p>
                                                                <ul style="list-style: none; padding: 0; columns: 2;">
                                                                    <?php
                                                                    $placeholders = [
                                                                        '[EMP_NO]',
                                                                        '[TITLE]',
                                                                        '[EMP_NAME_INT]',
                                                                        '[EMP_FULL_NAME]',
                                                                        '[USER_NAME]',
                                                                        '[GENDER]',
                                                                        '[BRANCH]',
                                                                        '[DEPARTMENT]',
                                                                        '[GROUP]',
                                                                        '[APPOINT_DATE]',
                                                                        '[ADDRESS]',
                                                                        '[PHONE]',
                                                                        '[EMAIL]',
                                                                        '[NIC]',
                                                                        '[BIRTHDAY]',
                                                                        '[RELIGION]',
                                                                        '[NIC_NUM]',
                                                                        '[OTHER_ID]',
                                                                        '[BASIC_SALARY]',
                                                                        '[TERMINATION_DATE]'
                                                                    ];
                                                                    foreach ($placeholders as $ph) {
                                                                        echo "<li style='margin-bottom: 5px; font-size: 14px; color: #555; width: 50%; display: inline-block;'>$ph</li>";
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>

                                                            <input type="submit" id="" name=""
                                                                class="btn-green btn fa fa-check"
                                                                value="&nbsp;&nbsp;CREATE&nbsp; TEMPLATE">

                                                            <?php echo form_close(); ?>
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
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h2>ALL TEMPLATES</h2>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-body">
                                                        <table id="example" class="table table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Template Name</th>
                                                                    <th>Subject</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($templates as $template): ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $template->id ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $template->template_name ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $template->letter_subject ?>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" title="View" id="view"
                                                                                onclick="window.location.href='<?php echo site_url('Employee_Management/View_Letter_Templates/edit_template/' . $template->id); ?>'"
                                                                                class="btn btn-success">
                                                                                <i class="fa fa-pencil-square-o"></i> View &
                                                                                Update
                                                                            </button>
                                                                            <button type="button" title="Generate Letter"
                                                                                id="generate"
                                                                                onclick="window.location.href='<?= site_url('Employee_Management/View_Letter_Templates/generate_letter/' . $template->id) ?>'"
                                                                                class="btn btn-primary">
                                                                                <i class="fa fa-file-text"></i> Generate
                                                                                Letter
                                                                            </button>
                                                                            <button type="button" title="Delete"
                                                                                onclick="if(confirm('Are you sure you want to delete this template?')) window.location.href='<?= site_url('Employee_Management/View_Letter_Templates/delete_template/' . $template->id) ?>'"
                                                                                class="btn btn-danger">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
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
                    <?php get_instance()->load->view('template/footer.php'); ?>
                    <!--End Footer-->
                </div>
            </div>
        </div>
    </div>


    <!-- Load site level scripts -->

    <?php get_instance()->load->view('template/js.php'); ?> <!-- Initialize scripts for this page-->

    <!-- Initialize CKEditor -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            CKEDITOR.replace('letter_body');
        });
    </script>

    <!-- End loading page level scripts-->

    <!--Ajax-->
    <script src="<?php echo base_url(); ?>system_js/Master/Designation.js"></script>

    <!--JQuary Validation-->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frm_designation").validate();
            $("#spnmessage").hide("shake", { times: 6 }, 3000);
        });
    </script>

</body>


</html>