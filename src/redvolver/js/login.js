"use strict";

// Class Definition
var KTLogin = function() {
    var _login;

    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_signin_form'),
			{
				fields: {
					rvuser: {
						validators: {
							notEmpty: {
								message: 'Username is required'
							}
						}
					},
					rvpassword: {
						validators: {
							notEmpty: {
								message: 'Password is required'
							}
						}
					}
				},
				plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          submitButton: new FormValidation.plugins.SubmitButton(),
          //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#kt_login_signin_submit').on('click', function (e) {
            e.preventDefault();
            console.log('a');
            validation.validate().then(function(status) {
		        if (status == 'Valid') {
              //       swal.fire({
		          //       text: "All is cool! Now you submit this form",
		          //       icon: "success",
		          //       buttonsStyling: false,
		          //       confirmButtonText: "Ok, got it!",
              //           customClass: {
    					// 	confirmButton: "btn font-weight-bold btn-light-primary"
    					// }
		          //   }).then(function() {
						  //           KTUtil.scrollTop();
					    //  });
              //btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

              $( "#kt_login_signin_form" ).submit();

				    } else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

        $('#kt_login_signin_form').on('submit', function(e) {
            e.preventDefault(); // prevent native submit
            $.ajax({
                type : "POST",
                url: ajaxurl,
                data : {
                  action: "rv_login",
                  form: $('#kt_login_signin_form').serialize()
                },
                beforeSend: function() {
                  $('#kt_login_signin_submit').addClass('spinner spinner-white spinner-right');
                },
                success: function(response) {
                  console.log(response);

                  if ( response.success ) {
                    location.href = response.data.redirect;
                  }else{
                    swal.fire({
          		                text: "Sorry, looks like there are some errors detected, please try again.",
          		                icon: "error",
          		                buttonsStyling: false,
          		                confirmButtonText: "Ok, got it!",
                                  customClass: {
              						confirmButton: "btn font-weight-bold btn-light-primary"
              					}
          		            }).then(function() {
                            $('#kt_login_signin_submit').removeClass('spinner spinner-white spinner-right');
          						KTUtil.scrollTop();
                      });

                  }

                  // // similate 2s delay
                  // setTimeout(function() {
                  //     btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                  //     form.clearForm();
                  //     form.validate().resetForm();
                  //
                  //     // display signup form
                  //     displaySignInForm();
                  //     var signInForm = login.find('.kt-login__signin form');
                  //     signInForm.clearForm();
                  //     signInForm.validate().resetForm();
                  //
                  //     showErrorMsg(signInForm, 'success', 'Thank you. To complete your registration please check your email.');
                  // }, 2000);
                }
            });

        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
          if ( $('#kt_login').length > 0 ) {
            _login = $('#kt_login');

            _handleSignInForm();
          }
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});
