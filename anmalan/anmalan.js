$(document).ready(function() {
  console.log('anmälan page loaded');

  var event_route = location.hash.replace('#', '').replace('/', '');
  console.log('event_route', event_route); 
  var scope = {};

  $.get('/api/event/' + event_route, function(data) {
    console.log('event', data);
    scope.event = data;

    reloadPlacesLeft(scope.event.id);
  });

  function reloadPlacesLeft(event_id) {
    $.get('/api/event/places_left/' + event_id, function(data) {
      console.log('places left', data.places_left);
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
      // get values from FORM
      var firstname = $("#anmalanForm input#firstname").val();
      var lastname = $("#anmalanForm input#lastname").val();
      var email = $("#anmalanForm input#email").val();
      var places = $("#anmalanForm input#places").val();
      console.log('places left', scope.places_left, places, event);
      if (parseInt(places) > scope.places_left) {
        $('#anmalanForm #success').html("<div class='alert alert-danger'>");
        $('#anmalanForm #success > .alert-danger').append($("<strong>").text("Du kan inte registrera fler platser än det finns ledigt!"));
        $('#anmalanForm #success > .alert-danger').append('</div>');
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
          success: function() {
              // Success message
              $('#anmalanForm #success').html("<div class='alert alert-success'>");
              $('#anmalanForm #success > .alert-success')
                  .append("<strong>Tack för ditt intresse! Infomation om anmälan kommer skickas till din e-postadress.</strong>");
              $('#anmalanForm #success > .alert-success')
                  .append('</div>');

              //clear all fields
              $('#anmalanForm').trigger("reset");

              //hide submit button and show close button
              $('#anmalanForm button[type=submit]').hide();
              $('#anmalanForm button#close').show(); 

              reloadPlacesLeft(scope.event.id);
          },
          error: function(err) {
              console.log(err);

              // Fail message
              $('#anmalanForm #success').html("<div class='alert alert-danger'>");
              $('#anmalanForm #success > .alert-danger').append($("<strong>").text("Sorry " + firstname + ", it seems that my mail server is not responding. Please try again later!"));
              $('#anmalanForm #success > .alert-danger').append('</div>');
          },
      });
    },
    filter: function() {
        return $(this).is(":visible");
    },
  });
});