$(document).ready(function() {
  console.log('anmälan page loaded');

  var event_route = location.hash.replace('#', '').replace('/', '');
  console.log('event_route', event_route); 

  $.get('/api/event/' + event_route, function(event) {
    console.log('event', event);

    $.get('/api/event/places_left/' + event.id, function(data) {
      console.log('places left', data.places_left);
    });
  });
});