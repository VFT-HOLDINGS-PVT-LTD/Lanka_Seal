<!DOCTYPE html>
<html lang="en">

<head>
    <title>Letter Templates</title>
    <!-- Styles -->
    <?php $this->load->view('template/css.php'); ?>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include CKEditor script -->
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            ClassicEditor
                .create(document.querySelector('#letter_body'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script> -->

    <style>
        .cke_notification_warning {
            display: none !important;
        }
    </style>
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
                            <li class="active">
                                <?= $template->template_name ?>
                            </li>
                        </ol>

                        <div class="page-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#view_template_tab">VIEW LETTER
                                        TEMPLATE</a></li>
                                <li><a data-toggle="tab" href="#edit_template_tab">EDIT LETTER TEMPLATE</a></li>
                            </ul>
                        </div>

                        <div class="container-fluid">
                            <div class="tab-content">

                                <!-- Tab 1: View Letter Template -->
                                <div id="view_template_tab" class="tab-pane fade in active">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h2>VIEW TEMPLATE <button type="button" title="Generate Letter"
                                                    id="generate"
                                                    onclick="window.location.href='<?= site_url('Employee_Management/View_Letter_Templates/generate_letter/' . $template->id) ?>'"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-file-text"></i> Generate Letter
                                                </button></h2>
                                        </div>
                                        <div class="panel-body">

                                            <?php if (isset($template)): ?>
                                                <h2 style="text-align: center; color: #444;">Template:
                                                    <?= $template->template_name ?>
                                                </h2>
                                                <p><strong>Subject:</strong>
                                                    <?= $template->letter_subject ?>
                                                </p>
                                                <div
                                                    style="border: 1px solid #ccc; padding: 10px; border-radius: 4px; background-color: #f9f9f9;">
                                                    <?= $template->letter_body ?>
                                                </div>
                                            <?php else: ?>
                                                <p style="text-align: center; color: #555;">Select a template to view from
                                                    the "View Letter Templates" tab.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 2: View Letter Template -->
                                <div id="edit_template_tab" class="tab-pane fade">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h2>EDIT TEMPLATE</h2>
                                        </div>
                                        <div
                                            style="max-width: 100%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                            <h3 style="text-align: center; color: #444;">Edit Letter Template</h3>

                                            <!-- Display success or error messages -->
                                            <?php if ($this->session->flashdata('success')): ?>
                                                <p style="color: green; text-align: center; margin-bottom: 20px;">
                                                    <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                                                </p>
                                            <?php elseif ($this->session->flashdata('error')): ?>
                                                <p style="color: red; text-align: center; margin-bottom: 20px;">
                                                    <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                                                </p>
                                            <?php endif; ?>

                                            <?php if (isset($template) && $template->id == $id): ?>
                                                <?php echo form_open("Employee_Management/View_Letter_Templates/update/{$template->id}"); ?>

                                                <label
                                                    style="font-weight: bold; display: block; margin-bottom: 5px;">Template
                                                    Name</label>
                                                <input type="text" name="template_name"
                                                    value="<?php echo $template->template_name; ?>" required
                                                    style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">

                                                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Letter
                                                    Subject</label>
                                                <input type="text" name="letter_subject"
                                                    value="<?php echo $template->letter_subject; ?>" required
                                                    style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">

                                                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Letter
                                                    Body</label>

                                                <!-- Textarea (with PHP variable for the content) -->
                                                <textarea name="letter_body" id="edit_letter_body"
                                                    style="width: 100%; height: 600px; resize: none; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                                                    <?php echo $template->letter_body; ?>
                                                </textarea>

                                                <!-- Include CKEditor 4 script -->
                                                <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

                                                <!-- Initialize CKEditor 4 -->
                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function () {
                                                        CKEDITOR.replace('edit_letter_body', {
                                                            height: 600,          // Set fixed height
                                                            resize_enabled: false // Disable resizing
                                                        });
                                                    });
                                                </script>

                                                <div style="margin-top: 20px;">
                                                    <p><strong>You can use the following placeholders:</strong></p>
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

                                                <input type="submit" class="btn-green btn fa fa-check"
                                                    value="&nbsp;&nbsp;UPDATE&nbsp;TEMPLATE">


                                                <?php echo form_close(); ?>
                                            <?php else: ?>
                                                <p style="text-align: center; color: #555;">Select a template to edit from
                                                    the "View Letter Templates" tab.</p>
                                            <?php endif; ?>
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

</body>

</html>