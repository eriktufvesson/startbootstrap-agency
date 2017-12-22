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
                    $('#garderobenForm').trigger("reset");

                    //hide submit button and show close button
                    // $('#garderobenForm button[type=submit]').hide();
                    // $('#garderobenForm button#close').show(); 
                },
                error: function(err) {
                    console.log(err);

                    // Fail message
                    $('#garderobenForm #success').html("<div class='alert alert-danger'>");
                    $('#garderobenForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!"));
                    $('#garderobenForm #success > .alert-danger').append('</div>');
                    // clear all fields
                    // $('#bookingForm').trigger("reset");
                },
            });
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("#nyarForm input,#nyarForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("#nyarForm input#name").val();
            var email = $("#nyarForm input#email").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            $.ajax({
                url: "../mail/nyar.php",
                type: "POST",
                data: {
                    name: name,
                    email: email
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#nyarForm #success').html("<div class='alert alert-success'>");
                    $('#nyarForm #success > .alert-success')
                        .append("<strong>Tack för ditt intresse! Utmaningen landar i din inbox inom kort.</strong>");
                    $('#nyarForm #success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#nyarForm').trigger("reset");

                    //hide submit button and show close button
                    // $('#nyarForm button[type=submit]').hide();
                    // $('#nyarForm button#close').show(); 
                },
                error: function(err) {
                    console.log(err);

                    // Fail message
                    $('#nyarForm #success').html("<div class='alert alert-danger'>");
                    $('#nyarForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!"));
                    $('#nyarForm #success > .alert-danger').append('</div>');
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
            var email = $("#bookingForm input#email").val();
            var message = $("#bookingForm textarea#message").val();
            $.ajax({
                url: "../mail/contact_me.php",
                type: "POST",
                data: {
                    email: email,
                    message: message
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#bookingForm #success').html("<div class='alert alert-success'>");
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
                    $('#bookingForm #success > .alert-danger').append($("<strong>").text("Sorry, it seems that my mail server is not responding. Please try again later!"));
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
            var email = $("#contactForm input#email").val();
            var message = $("#contactForm textarea#message").val();
            $.ajax({
                url: "././mail/contact_me.php",
                type: "POST",
                data: {
                    email: email,
                    message: message
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#contactForm #success').html("<div class='alert alert-success'>");
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
                    $('#contactForm #success > .alert-danger').append($("<strong>").text("Sorry, it seems that my mail server is not responding. Please try again later!"));
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
