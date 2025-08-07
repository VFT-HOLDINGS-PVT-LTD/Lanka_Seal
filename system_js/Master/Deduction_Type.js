//newly addded deduction type delete
function delete_id(id) {
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Delete This!",
            cancelButtonText: "No, Cancel This!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {

                $.ajax({
                    url: baseurl + "Master/Deduction_Types/ajax_delete/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {

                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                });


                swal("Deleted!", "Selected data has been deleted.", "success");


                $(document).ready(function () {
                    setTimeout(function () {
                        window.location.replace(baseurl + "Master/Deduction_Types/");
                    }, 1000);
                });


            } else {
                swal("Cancelled", "Selected data Cancelled", "error");

            }

        });

}