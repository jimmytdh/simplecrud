$(document).ready(function() {
    //rename modal title
    $(document).on('click','.deleteRecord',function(){
        //get current ID
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure you want to delete this record?',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            reverseButtons: true
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // show success message
                $.ajax({
                    url: 'controller/deleteRecord.php?id=' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function() {
                        //show msg alert
                        var msg = 'Record successfully deleted!'
                        toast(msg);

                        // Refresh the DataTable object
                        $('.datatable').DataTable().ajax.reload();
                    },
                    error: function(){
                        console.log('error')
                    }
                });

            }
        })
    });

    //rename modal title
    $(document).on('click','.btn-addRecord',function(){

        $('#myForm').trigger("reset"); // reset the form
        $('#id').val(""); // reset the form
        $('.modal-title').html('Add Record'); //rename modal title
        $('#submitBtn').html('Submit'); //rename btn
        $('')
    });
    // Set up form validation rules and messages
    $(document).on('click','.updateRecord',function(){
        //rename modal title
        $('.modal-title').html('Update Record');
        //rename btn
        $('#submitBtn').html('Update');

        // get ID of selected row
        var id = $(this).data('id');
        // send AJAX request to get data for the selected row
        $.ajax({
            url: 'controller/getRecord.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // populate modal with data
                populateModal(response);
            }
        });
    });

    // function to populate modal with data
    function populateModal(data) {
        // set values of form fields based on data
        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#dob').val(data.dob);
        $('#contact').val(data.contact);
        $('#bodyTemp').val(data.bodyTemp);
        $('input[name=covidDiagnosed][value=' + data.covidDiagnosed + ']').prop('checked', true);
        $('input[name=covidEncounter][value=' + data.covidEncounter + ']').prop('checked', true);
        $('input[name=vaccinated][value=' + data.vaccinated + ']').prop('checked', true);
        $('#nationality').val(data.nationality);

        // set ID as data attribute for form submission
        $('#id').val(data.id);

        // show modal
        $('#addProfileModal').modal('show');
    }
});