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
                    url: window.location.pathname + "api/delete.php",
                    type: 'POST',
                    data: { id:id},
                    success: function(data) {
                        console.log(data)
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
            url: window.location.pathname + "api/read.php?id=" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // populate modal with data
                populateModal(response);
            }
        });
    });
});

// function to populate modal with data
function populateModal(data) {
    // set values of form fields based on data
    $('#firstname').val(data.firstname);
    $('#lastname').val(data.lastname);
    $('#dob').val(data.dob);
    $('#gender').val(data.gender);
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
//generate Datatable
function drawDatatable(){
    $('.datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": window.location.pathname + "api/read.php"
    });
}

function loadProfile(){
    $('#main').load(`pages/profile.html`, function(){
        drawDatatable();
        $.getJSON("inc/all.json", function(data) {
            data.sort(function(a, b) {
                var labelA = a.name.common.toUpperCase();
                var labelB = b.name.common.toUpperCase();
                if (labelA < labelB) {
                    return -1;
                }
                if (labelA > labelB) {
                    return 1;
                }
                return 0;
            });
            $.each(data, function(index, item) {
                var selected = item.name.common === "Philippines" ? "selected" : "";
                $("#nationality").append("<option value='" + item.name.common + "' " + selected + ">" + item.name.common + "</option>");
            });
        });

        $.validator.addMethod('regex', function(value, element, regexp) {
            return this.optional(element) || regexp.test(value);
        }, 'Please enter a valid value.');

        $('#myForm').validate({
            rules: {
                contact: {
                    number: true,
                    regex: /^09\d{9}$/
                }
            },
            messages: {
                contact: {
                    number: 'Please enter a valid mobile number',
                    regex: 'Start with 09*********'
                },
                bodyTemp: {
                    required: true,
                    number: true,
                    step: 0.01
                },
            },
            submitHandler: function(form) {
                var url = ($('#submitBtn').html()!=='Update') ? window.location.pathname + "api/store.php" : window.location.pathname + "api/update.php";
                var msg = ($('#submitBtn').html()!=='Update') ? "Record added successfully":"Record updated successfully";
                // This code is executed when the form is valid and is submitted
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $(form).serialize(),
                    success: function(data) {
                        // Refresh the DataTable object
                        $('.datatable').DataTable().ajax.reload();

                        // close the modal
                        $("#addProfileModal").modal("hide");

                        // show success message
                        toast(msg);

                    }
                });
            }
        });

        // Check if the form is valid when the Submit button is clicked
        $('#submitBtn').click(function() {
            if ($('#myForm').valid()) {
                // Submit the form if it is valid
                $('#myForm').submit();
            }
        });
    });
}