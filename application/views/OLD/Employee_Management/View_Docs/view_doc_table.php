<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Documents</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Category</th>
                <th>Uploaded At</th>
                <th>Preview</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docs as $doc): ?>
            <tr class='odd gradeX'>
                <td width='100'>
                    <?= htmlspecialchars($doc->file_name) ?>
                </td>
                <td width='100'>
                    <?= date('d-m-Y h:i A', strtotime($doc->uploaded_at)) ?>
                </td>
                <td width='100'>
                    <a href="<?= base_url($doc->file_path) ?>" class="btn btn-success btn-sm" target="_blank"
                        title="PREVIEW">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
                <td width='15'>
                    <button class="btn btn-primary btn-sm edit-doc" data-id="<?= $doc->id ?>"
                        data-name="<?= htmlspecialchars($doc->file_name) ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </td>
                <td width='15'>
                    <button class='btn btn-danger btn-sm' title='DELETE'
                        onclick="if(confirm('Are you sure you want to delete this document?')) { window.location.href='<?= base_url('Employee_Management/View_Docs/Delete_Doc/' . $doc->id) ?>'; }">
                        <i class='fa fa-times-circle'></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="panel-footer"></div>




    <!--Modal for Editing Document -->
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            position: relative;
        }

        .modal-content input[type="text"],
        .modal-content input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            margin-bottom: 10px;
        }

        .modal-content .close {
            position: absolute;
            top: 8px;
            right: 12px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>

    <div id="myModal" class="modal hidden">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit Document</h3>
            <form action="<?= base_url('Employee_Management/View_Docs/Edit_Doc') ?>" method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="doc_id" id="modalDocId">
                <label for="modalDocName">Rename Category</label>
                <input type="text" id="modalDocName" name="file_name" required>

                <div class="col-md-8">
                    <label class="form-label fw-semibold text-secondary">Upload File</label>
                    <div class="position-relative">
                        <label
                            class="d-flex align-items-center justify-content-center gap-3 w-100 p-4 bg-white border border-2 border-primary rounded shadow-sm"
                            style="cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#f0f8ff'"
                            onmouseout="this.style.backgroundColor='white'">
                            <div style="font-size: 50px; color: #0d6efd;">📁</div>
                            <span class="text-muted file-name-display">Click to select a file</span>
                            <input type="file" name="user_files[]" class="form-control" style="display: none;"
                                onchange="this.parentElement.querySelector('.file-name-display').innerText = this.files.length > 0 ? this.files[0].name : 'Click to select a file'">
                        </label>
                    </div>
                </div>


                <div style="margin-top: 10px;">
                    <button type="submit" class="btn-sm btn-success">Save</button>
                    <button type="button" id="modalCancel" class="btn-sm btn-danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const modal = $('#myModal');

            $('.edit-doc').click(function () {
                const docId = $(this).data('id');
                const docName = $(this).data('name');
                $('#modalDocId').val(docId);
                $('#modalDocName').val(docName);
                modal.removeClass('hidden');
            });

            $('.close, #modalCancel').click(function () {
                modal.addClass('hidden');
            });

            $(window).click(function (e) {
                if ($(e.target).is('#myModal')) {
                    modal.addClass('hidden');
                }
            });
        });
    </script>

</body>

</html>