$(document).ready(function() {

  var event_route = location.hash.replace('#', '').replace('/', '');
  var scope = {};

  $.get('/api/event/workshop/' + event_route, function(data) {
    scope.event = data;
    document.title = 'Workshop Lund ' + scope.event.formattedDate + ' | Dress by heart';
    $('.event-date').text(scope.event.formattedDate);
    $('.event-price').text(scope.event.price);
    $('.event-title').text(scope.event.title);
    if (scope.event.enableBooking) {
      $('.booking-enabled').show();
      $('.booking-disabled').hide();
    }
    else {
      $('.booking-enabled').hide();
      $('.booking-disabled').show();
      if (scope.event.next) {
        $('.next-event').html('<a href="/workshop#' + scope.event.next.name + '">Nästa workshop hålls i Lund den ' + scope.event.next.formattedDate + '</a>');
      }
    }
    $('.anmalan .intro-text').show();
    reloadPlacesLeft(scope.event.id);
  });

  function reloadPlacesLeft(event_id) {
    $.get('/api/event_places_left/' + event_id, function(data) {
      scope.places_left = data.places_left;
      $('.places-left').html(data.places_left);
    });
  }


  $("#anmalanForm input,#anmalanForm textarea").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, e, errors) {
        // additional error messages or events
    },
    submitSuccess: function($form, e) {
      e.preventDefault(); // prevent default submit behaviour
      $('#anmalanForm button[type=submit]').prop('disabled', true);
      // get values from FORM
      var firstname = $("#anmalanForm input#firstname").val();
      var lastname = $("#anmalanForm input#lastname").val();
      var email = $("#anmalanForm input#email").val();
      var places = $("#anmalanForm input#places").val();
      if (scope.places_left > 0 && parseInt(places) > scope.places_left) {
        $('#anmalanForm #success').html("<div class='alert alert-danger'>");
        $('#anmalanForm #success > .alert-danger').append($("<strong>").text("Du kan inte registrera fler platser än det finns ledigt!"));
        $('#anmalanForm #success > .alert-danger').append('</div>');
        $('#anmalanForm button[type=submit]').prop('disabled', false);
        return;
      }
      $.ajax({
          url: "../api/event/register",
          type: "POST",
          data: {
            event_id: scope.event.id,
            name: firstname + ' ' + lastname,
            email: email,
            nbr_places: places
          },
          cache: false,
          success: function(res) {
            if (res.status) {
              // Success message
              $('#anmalanForm #success').html("<div class='alert alert-success'>");
              $('#anmalanForm #success > .alert-success')
                  .append("<strong>Tack för ditt intresse! Infomation om anmälan kommer skickas till din e-postadress.</strong>");
              $('#anmalanForm #success > .alert-success')
                  .append('</div>');

              //clear all fields
              $('#anmalanForm').trigger("reset");
              
              reloadPlacesLeft(scope.event.id);
            }
            else {
              // Error message
              $('#anmalanForm #success').html("<div class='alert alert-danger'>");
              $('#anmalanForm #success > .alert-danger').append($("<strong>").text(res.message));
              $('#anmalanForm #success > .alert-danger').append('</div>');
            }
          },
          error: function(err) {
              console.log(err);

              // Fail message
              $('#anmalanForm #success').html("<div class='alert alert-danger'>");
              $('#anmalanForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstname + ", it seems that my mail server is not responding. Please try again later!"));
              $('#anmalanForm #success > .alert-danger').append('</div>');
          },
          complete: function() {
            $('#anmalanForm button[type=submit]').prop('disabled', false);
          }
      });
    },
    filter: function() {
        return $(this).is(":visible");
    },
  });
});