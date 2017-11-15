$(document).ready(function() {
  
  $('.choose-giftcert').click(function(e) {
    e.preventDefault();
    if ($(this).hasClass('selected')) {
      $(this).removeClass('selected');   
      $('#presentkort').val('');
    }
    else {
      $('.choose-giftcert.selected').removeClass('selected');
      $(this).addClass('selected');   
      var giftCertId = $(this).data('giftcert-id');
      $('#presentkort').val(giftCertId);
    }
  });

  $('#presentkort').change(function(e) {
    var link = $("[data-giftcert-id='" + $('#presentkort').val() + "']");
    $('.choose-giftcert.selected').removeClass('selected');
    link.toggleClass('selected');   
  });

  $('.choose-delivery').click(function(e) {
    e.preventDefault();
    if ($(this).hasClass('selected')) {
      $(this).removeClass('selected');   
      $('#leverans').val('');
    }
    else {
      $('.choose-delivery.selected').removeClass('selected');
      $(this).addClass('selected');   
      var deliveryId = $(this).data('delivery-id');
      $('#leverans').val(deliveryId);
    }
  });

  $('#leverans').change(function(e) {
    var link = $("[data-delivery-id='" + $('#leverans').val() + "']");
    $('.choose-delivery.selected').removeClass('selected');
    link.toggleClass('selected');   
  });


  $("#presentkortForm input,#presentkortForm textarea,#presentkortForm select").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, e, errors) {
        // additional error messages or events
    },
    submitSuccess: function($form, e) {
      e.preventDefault(); // prevent default submit behaviour
      $('#presentkortForm button[type=submit]').prop('disabled', true);
      // get values from FORM
      var giftCertId = $('#presentkortForm select#presentkort').val();
      var deliveryId = $('#presentkortForm select#leverans').val();
      var name = $("#presentkortForm input#name").val();
      var address = $("#presentkortForm input#address").val();
      var postalCode = $("#presentkortForm input#postalCode").val();
      var city = $("#presentkortForm input#city").val();
      var email = $("#presentkortForm input#email").val();
      $.ajax({
          url: "../api/giftcert/buy",
          type: "POST",
          data: {
            giftcert_id: giftCertId,
            delivery_id: deliveryId,
            name: name,
            address: address,
            postal_code: postalCode,
            city: city,
            email: email
          },
          cache: false,
          success: function(res) {
            if (res.status) {
              // Success message
              $('#presentkortForm #success').html("<div class='alert alert-success'>");
              $('#presentkortForm #success > .alert-success')
                  .append("<strong>Tack för din beställning! En bekräftelse skickas till din e-postadress.</strong>");
              $('#presentkortForm #success > .alert-success')
                  .append('</div>');

              //clear all fields
              $('#presentkortForm').trigger("reset");
            }
            else {
              // Error message
              $('#presentkortForm #success').html("<div class='alert alert-danger'>");
              $('#presentkortForm #success > .alert-danger').append($("<strong>").text(res.message));
              $('#presentkortForm #success > .alert-danger').append('</div>');
            }
          },
          error: function(err) {
              console.log(err);

              // Fail message
              $('#presentkortForm #success').html("<div class='alert alert-danger'>");
              $('#presentkortForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstname + ", it seems that my mail server is not responding. Please try again later!"));
              $('#presentkortForm #success > .alert-danger').append('</div>');
          },
          complete: function() {
            $('#presentkortForm button[type=submit]').prop('disabled', false);
          }
      });
    },
    filter: function() {
        return $(this).is(":visible");
    },
  });
});