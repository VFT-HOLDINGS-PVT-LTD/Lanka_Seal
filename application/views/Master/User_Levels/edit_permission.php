<!-- Include Bootstrap CSS in <head> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>ADD USER LEVELS</h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="frm_user_level" name="frm_user_level" action="<?php echo base_url(); ?>Master/User_Levels/insert_data" method="POST">
                        <div class="form-group col-sm-12">
                            <div class="col-sm-8">
                                <img class="imagecss" src="<?php echo base_url(); ?>assets/images/userlevel.png">
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="focusedinput" class="col-sm-4 control-label">User Level Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required id="txt_user_level" name="txt_user_level" placeholder="Ex: Admin">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8 offset-sm-2">
                                <button type="submit" id="submit" class="btn btn-primary">
                                    <i class="fa fa-check"></i> Submit
                                </button>
                                <button type="button" id="Cancel" name="Cancel" class="btn btn-danger">
                                    <i class="fa fa-times-circle"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <div id="divmessage" class="mt-3" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <div id="spnmessage"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Level Permissions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Permissions Content -->
                <form class="form-horizontal" id="frm_user_level_view" name="frm_user_level_view" method="POST" style="margin-top: 20px;">
                    <div class="form-group row align-items-center">
                        <label for="user_level_select" class="col-sm-4 col-form-label text-right">Select User Level</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="user_level_select" name="user_level_select" required>
                                <option value="">-- Select User Level --</option>
                                <?php foreach ($data_set as $data): ?>
                                    <option value="<?php echo $data->user_level_id; ?>">
                                        <?php echo $data->user_level_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="font-weight-bold">Permissions Checklist</label>
                        <div class="row">
                            <?php
                            $permissions = [
                                "Dashboard", "master_data", "designation", "department",
                                "holiday_types", "holidays", "shifts", "weekly_roster",
                                "user_level", "leave_types", "banks", "bank_accounts",
                                "payees", "loan_types", "allowance_types", "deduction_types",
                                "branches", "employee_groups", "Employee_mgt", "add_employee",
                                "add_employee_branch", "view_employee", "Attendance", "shift_allocation",
                                "attendance_collection", "attendance_row_data", "manual_attendance", "manual_att_request",
                                "Is_manual_Sup", "Is_manual_Admin", "attendance_process", "Leave_Transaction",
                                "view_lv_balance", "leave_allocation", "leave_approve", "leave_app_sup",
                                "leave_entry", "leave_request", "leave_adj", "Payroll",
                                "allowance", "deduction", "loan_entry", "salary_increment",
                                "salary_advance", "request_advance", "approve_advance", "payroll_process", "payroll_initialize",
                                "Cheque", "write_cheque", "view_cheque", "Messages",
                                "send_message", "view_message", "company", "company_profile",
                                "Reports", "Master_Reports", "employee_report", "designation_report",
                                "department_report", "holidays_report", "employee_birthdays", "Attendance_Report",
                                "in_out_report", "overtime_report", "absence_report", "leave_report",
                                "late_report", "monthly_summery_report", "leave_summery_report", "ot_summery_report",
                                "Payroll_reports", "allowance_report", "deduction_report", "salary_advance_report",
                                "paysheet_report", "payslip_report", "Analysis_Report", "month_absence_rpt",
                                "attendance_rpt", "leave_rpt", "Tools", "System_backup",
                                "dsh_report", "dsh_ch_1", "dsh_ch_2", "dsh_ch_3",
                                "dsh_rpt_in_out", "dsh_rpt_paysheet", "dsh_rpt_leave", "dsh_rpt_emp_master",
                                "dsh_rpt_sal_advance", "dsh_rpt__absence", "header_lv_nt"
                            ];

                            $columns = 4;
                            $chunked = array_chunk($permissions, ceil(count($permissions) / $columns));
                            foreach ($chunked as $column) {
                                echo '<div class="col-md-3">';
                                foreach ($column as $perm) {
                                    echo '<label><input type="checkbox" name="permissions[]" value="' . $perm . '" disabled> ' . ucfirst(str_replace('_', ' ', $perm)) . '</label><br>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- AJAX Form Submission -->
<script>
    $(document).ready(function () {
        $('#frm_user_level').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (response) {
                    $('#divmessage').removeClass().addClass('alert alert-success').show();
                    $('#spnmessage').text('User Level added successfully.');

                    // Show permissions modal
                    $('#permissionsModal').modal('show');
                },
                error: function () {
                    $('#divmessage').removeClass().addClass('alert alert-danger').show();
                    $('#spnmessage').text('Error adding user level.');
                }
            });
        });
    });
</script>
