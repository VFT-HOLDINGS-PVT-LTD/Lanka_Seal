<!DOCTYPE html>

<html lang="en">

<title>
    <?php echo "User Documents"; ?>
</title>

<head>
    <!-- Styles -->
    <?php get_instance()->load->view('template/css.php'); ?>
    <!-- --JQuary -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            <li class="active"><a href="<?php echo base_url(); ?>Employee_Management/View_Docs">USER
                                    DOCUMENTS</a></li>

                        </ol>


                        <div class="page-tabs">
                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab1">UPLOAD DOCUMENTS</a></li>
                                <li><a data-toggle="tab" href="#tab2">VIEW DOCUMENTS</a></li>


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
                                                            <h2>UPLOAD USER DOCUMENTS</h2>
                                                        </div>
                                                        <div class="panel-body">

                                                            <?php if ($this->session->flashdata('success')): ?>
                                                            <div class="alert alert-success">
                                                                <?= $this->session->flashdata('success'); ?>
                                                            </div>
                                                            <?php endif; ?>

                                                            <?php if ($this->session->flashdata('error')): ?>
                                                            <div class="alert alert-danger">
                                                                <?= $this->session->flashdata('error'); ?>
                                                            </div>
                                                            <?php endif; ?>

                                                            <?php if ($this->session->flashdata('info')): ?>
                                                            <div class="alert alert-info">
                                                                <?= $this->session->flashdata('info'); ?>
                                                            </div>
                                                            <?php endif; ?>


                                                            <form
                                                                action="<?= base_url('Employee_Management/View_Docs/upload_docs') ?>"
                                                                method="post" enctype="multipart/form-data"
                                                                id="uploadForm">

                                                                <div class="row">
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="txt_emp"
                                                                            class="col-sm-4 control-label">Employee
                                                                            No</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="txt_emp" id="txt_emp"
                                                                                placeholder="Ex: 0001">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="txt_emp_name"
                                                                            class="col-sm-4 control-label">Employee
                                                                            Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                name="txt_emp_name" id="txt_emp_name"
                                                                                placeholder="Ex: Ashan">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <hr>

                                                                <div id="fileFieldsContainer">
                                                                    <div
                                                                        class="file-field p-4 border rounded bg-light shadow-sm mb-3">
                                                                        <div class="row align-items-center g-3">

                                                                            <!-- Category Input -->
                                                                            <div class="col-md-4">
                                                                                <label
                                                                                    class="form-label fw-semibold text-secondary">Category</label>
                                                                                <input type="text"
                                                                                    class="form-control border-primary"
                                                                                    name="file_name[]"
                                                                                    placeholder="e.g., NIC, Certificate"
                                                                                    required>
                                                                            </div>

                                                                            <!-- File Upload with Icon and Filename -->
                                                                            <div class="col-md-8">
                                                                                <label
                                                                                    class="form-label fw-semibold text-secondary">Upload
                                                                                    File</label>
                                                                                <div class="position-relative">
                                                                                    <label
                                                                                        class="d-flex align-items-center justify-content-center gap-3 w-100 p-4 bg-white border border-2 border-primary rounded shadow-sm"
                                                                                        style="cursor: pointer; transition: all 0.3s;"
                                                                                        onmouseover="this.style.backgroundColor='#f0f8ff'"
                                                                                        onmouseout="this.style.backgroundColor='white'">
                                                                                        <div
                                                                                            style="font-size: 50px; color: #0d6efd;">
                                                                                            📁</div>
                                                                                        <span class="text-muted"
                                                                                            id="filenameDisplay">Click
                                                                                            to select a file</span>
                                                                                        <input type="file"
                                                                                            name="user_files[]"
                                                                                            class="form-control"
                                                                                            style="display: none;"
                                                                                            required
                                                                                            onchange="document.getElementById('filenameDisplay').innerText = this.files[0]?.name || 'Click to select a file'">
                                                                                    </label>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <button type="button" id="addMoreFiles"
                                                                    class="btn btn-success">+ Add More</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Submit</button>
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="location.reload();">Cancel</button>
                                                            </form>
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
                                                    <h2>VIEW DOCUMENTS</h2>
                                                </div>
                                                <div class="panel-body">
                                                    <form id="viewDocsForm">
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label for="txt_emp_view"
                                                                    class="col-sm-4 control-label">Employee No</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        id="txt_emp_view" name="txt_emp_view"
                                                                        placeholder="Ex: 0001">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="txt_emp_name_view"
                                                                    class="col-sm-4 control-label">Employee Name</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        id="txt_emp_name_view" name="txt_emp_name_view"
                                                                        placeholder="Ex: Ashan">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" id="submitViewDocs"
                                                            class="btn btn-primary">Submit</button>
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="location.reload();">Cancel</button>
                                                    </form>
                                                    <div id="viewDocsResult" style="margin-top: 20px;"></div>
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

    <!--Auto complete-->
    <script type="text/javascript">
        $(function () {
            $("#txt_emp_name_view").autocomplete({
                source: "<?php echo base_url(); ?>Employee_Management/View_Docs/get_auto_emp_name" // path to the get_birds method
            });
        });

        $(function () {
            $("#txt_emp_view").autocomplete({
                source: "<?php echo base_url(); ?>Employee_Management/View_Docs/get_auto_emp_no" // path to the get_birds method
            });
        });
    </script>

    <!-- script for adding more files -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let fileFieldCount = 1;

            document.getElementById("addMoreFiles").addEventListener("click", function () {
                fileFieldCount++;
                const container = document.getElementById("fileFieldsContainer");
                const newField = document.createElement("div");
                newField.classList.add("file-field", "p-4", "border", "rounded", "bg-light", "shadow-sm", "mb-3");

                newField.innerHTML = `
        <div class="row align-items-center g-3">
        <hr class="my-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold text-secondary">Category</label>
            <input type="text" class="form-control border-primary" name="file_name[]" placeholder="e.g., NIC, Certificate" required>
          </div>
          <div class="col-md-7">
            <label class="form-label fw-semibold text-secondary">Upload File</label>
            <div class="position-relative">
              <label class="d-flex align-items-center justify-content-center gap-3 w-100 p-4 bg-white border border-2 border-primary rounded shadow-sm"
                     style="cursor: pointer; transition: all 0.3s;"
                     onmouseover="this.style.backgroundColor='#f0f8ff'"
                     onmouseout="this.style.backgroundColor='white'">
                <div style="font-size: 50px;">📁</div>
                <span class="text-muted">Click to select a file</span>
                <input type="file" name="user_files[]" class="form-control" style="display: none;" required onchange="this.previousElementSibling.innerText = this.files[0]?.name || 'Click to select a file'">
              </label>
            </div>
          </div>
          <div class="col-md-1 text-end">
            <button type="button" class="btn btn-link text-danger fs-4 p-0" onclick="this.closest('.file-field').remove()">
              <i class="bi bi-x-circle-fill" style="font-size: 2rem; color: red;"></i>
            </button>
          </div>
        </div>
      `;

                container.appendChild(newField);
            });
        });
    </script>
    <!-- End loading page level scripts-->

    <!-- ajax for view documents -->
    <script>
        $(document).ready(function () {
            $('#viewDocsForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "<?php echo base_url(); ?>Employee_Management/View_Docs/fetch_docs",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#viewDocsResult').html(response); // Make sure this div exists
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            $('#submitViewDocs').on('click', function () {
                $('#viewDocsForm').submit();
            });
        });
    </script>


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