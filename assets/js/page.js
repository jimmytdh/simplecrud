$(function(){
    $('.nav-link').click(function(event){
        event.preventDefault();
        let page = $(this).attr('href').substring(1);
        $('#main').load(`pages/${page}.html`, function(){
            $('.datatable').DataTable();

            if(page == 'profile'){
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
                        // This code is executed when the form is valid and is submitted
                        $.ajax({
                            url: "inc/profile.php",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function() {
                                // close the modal
                                $("#addProfileModal").modal("hide");
                                // reset the form
                                $(form).trigger("reset");
                                // show success message
                                var alertDiv = '<div class="alert alert-success" role="alert">Record added successfully.</div>';
                                $(".notificationArea").prepend(alertDiv);
                                setTimeout(function() {
                                    $(".alert-success").remove();
                                }, 3000);
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
            }

        });
    });
});