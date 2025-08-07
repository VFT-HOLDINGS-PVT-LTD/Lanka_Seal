<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<div class="panel panel-primary">
    <div class="panel panel-default">
        <div class="panel-body panel-no-padding" >
        <button type="button" class='get_data btn btn-primary' onclick="handleApproveAll()">Approve All Selected</button>
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                        <th>EMP NO</th>
                        <th>NAME</th>
                        <th>LEVE TYPE</th>
                        <th>APPLY DATE</th>
                        <th>LEAVE DATE</th>
                        <th>REASON</th>
                        <th>LEAVE COUNT</th>
                        <th>MONTH</th>
                        <th>YEAR</th>


                        <th>STATUS</th>
                        <!--<th>EDIT</th>-->
                        <th>APPROVE</th>
                        <th>REJECT</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_set as $data) {?>
                        <tr class='odd gradeX'>
                            <td width='15'>
                                <input type='checkbox' class='select-item' value="<?php echo $data->LV_ID; ?>">
                            </td>
                            <td width='270'><?php echo $data->LV_ID; ?></td>
                            <td width='100'><?php echo $data->EmpNo; ?></td>
                            <td width='100'><?php echo $data->Emp_Full_Name; ?></td>
                            <td width='100'><?php echo $data->leave_name; ?></td>
                            <td width='150'><?php echo $data->Apply_Date; ?></td>
                            <td width='150'><?php echo $data->Leave_Date; ?></td>
                            <td width='100'><?php echo $data->Reason; ?></td>
                            <td width='50'><?php echo $data->Leave_Count; ?></td>
                            <td width='50'><?php echo $data->month; ?></td>
                            <td width='70'><?php echo $data->Year; ?></td>
                            <td width='15'><span class='get_data label label-warning'>Pending<i class='fa fa-eye'></i></span></td>
                            <td width='15'><a class='get_data btn btn-primary' href="<?php echo base_url() . 'Leave_Transaction/Leave_Approve_Sup/approve/' . $data->LV_ID; ?>">APPROVE<i class=''></i></a></td>
                            <td width='15'><a class='get_data btn btn-danger' href="<?php echo base_url() . 'Leave_Transaction/Leave_Approve_Sup/reject/' . $data->LV_ID; ?>">REJECT<i class=''></i></a></td>
                        </tr>
                    <?php }?>
                </tbody>

            </table>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>
<script>
    document.getElementById('select-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.select-item');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    function handleApproveAll() {
        var selected = [];
        var checkboxes = document.querySelectorAll('.select-item:checked');
        for (var checkbox of checkboxes) {
            selected.push(checkbox.value);
        }

        if (selected.length > 0) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo base_url(); ?>Leave_Transaction/Leave_Approve_Sup/approveAll';

            for (var id of selected) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        } else {
            alert('No leave requests selected');
        }
    }
</script>
