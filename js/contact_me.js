// Contact Form Scripts

$(function() {

    $("#garderobenForm input,#garderobenForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("#garderobenForm input#name").val();
            var email = $("#garderobenForm input#email").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            $.ajax({
                url: "../mail/garderoben.php",
                type: "POST",
                data: {
                    name: name,
                    email: email
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#garderobenForm #success').html("<div class='alert alert-success'>");
                    $('#garderobenForm #success > .alert-success')
                        .append("<strong>Tack för ditt intresse! Guiden kommer skickas till din e-postadress.</strong>");
                    $('#garderobenForm #success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#bookingForm').trigger("reset");

                    //hide submit button and show close button
                    $('#bookingForm button[type=submit]').hide();
                    $('#bookingForm button#close').show(); 
                },
                error: function(err) {
                    console.log(err);

                    // Fail message
                    $('#bookingForm #success').html("<div class='alert alert-danger'>");
                    $('#bookingForm #success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#bookingForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!"));
                    $('#bookingForm #success > .alert-danger').append('</div>');
                    // clear all fields
                    // $('#bookingForm').trigger("reset");
                },
            });
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("#bookingForm input,#bookingForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("#bookingForm input#name").val();
            var email = $("#bookingForm input#email").val();
            var phone = $("#bookingForm input#phone").val();
            var message = $("#bookingForm textarea#message").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            $.ajax({
                url: "././mail/contact_me.php",
                type: "POST",
                data: {
                    name: name,
                    phone: phone,
                    email: email,
                    message: message
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#bookingForm #success').html("<div class='alert alert-success'>");
                    $('#bookingForm #success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#bookingForm #success > .alert-success')
                        .append("<strong>Tack för ditt meddelande! Jag kontaktar dig så snart jag kan.</strong>");
                    $('#bookingForm #success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#bookingForm').trigger("reset");

                    //hide submit button and show close button
                    $('#bookingForm button[type=submit]').hide();
                    $('#bookingForm button#close').show(); 
                },
                error: function(err) {
                    console.log(err);

                    // Fail message
                    $('#bookingForm #success').html("<div class='alert alert-danger'>");
                    $('#bookingForm #success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#bookingForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!"));
                    $('#bookingForm #success > .alert-danger').append('</div>');
                    // clear all fields
                    // $('#bookingForm').trigger("reset");
                },
            });
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("#contactForm input#name").val();
            var email = $("#contactForm input#email").val();
            var phone = $("#contactForm input#phone").val();
            var message = $("#contactForm textarea#message").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            $.ajax({
                url: "././mail/contact_me.php",
                type: "POST",
                data: {
                    name: name,
                    phone: phone,
                    email: email,
                    message: message
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#contactForm #success').html("<div class='alert alert-success'>");
                    $('#contactForm #success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#contactForm #success > .alert-success')
                        .append("<strong>Tack för ditt meddelande! Jag kontaktar dig så snart jag kan.</strong>");
                    $('#contactForm #success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#contactForm').trigger("reset");
                },
                error: function(err) {
                    console.log(err);
                    // Fail message
                    $('#contactForm #success').html("<div class='alert alert-danger'>");
                    $('#contactForm #success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#contactForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!"));
                    $('#contactForm #success > .alert-danger').append('</div>');
                    //clear all fields
                    $('#contactForm').trigger("reset");
                },
            });
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
