$(document).ready(function() {
  
  $("#competitionForm input,#competitionForm textarea").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, e, errors) {
        // additional error messages or events
    },
    submitSuccess: function($form, e) {
      e.preventDefault(); // prevent default submit behaviour
      $('#competitionForm button[type=submit]').prop('disabled', true);
      // get values from FORM
      var name = $("#competitionForm input#name").val();
      var email = $("#competitionForm input#email").val();
      var answer = $("#competitionForm textarea#answer").val();
      $.ajax({
          url: "../api/competition",
          type: "POST",
          data: {
            name: name,
            email: email,
            answer: answer
          },
          cache: false,
          success: function(res) {
            if (res.status) {
              // Success message
              $('#competitionForm #success').html("<div class='alert alert-success'>");
              $('#competitionForm #success > .alert-success')
                  .append("<strong>Tack för ditt svar. Lycka till!</strong>");
              $('#competitionForm #success > .alert-success')
                  .append('</div>');

              //clear all fields
              $('#competitionForm').trigger("reset");
            }
            else {
              // Error message
              $('#competitionForm #success').html("<div class='alert alert-danger'>");
              $('#competitionForm #success > .alert-danger').append($("<strong>").text(res.message));
              $('#competitionForm #success > .alert-danger').append('</div>');
            }
          },
          error: function(err) {
              console.log(err);

              // Fail message
              $('#competitionForm #success').html("<div class='alert alert-danger'>");
              $('#competitionForm #success > .alert-danger').append($("<strong>").text("Sorry, it seems that my mail server is not responding. Please try again later!"));
              $('#competitionForm #success > .alert-danger').append('</div>');
          },
          complete: function() {
            $('#competitionForm button[type=submit]').prop('disabled', false);
          }
      });
    },
    filter: function() {
        return $(this).is(":visible");
    },
  });
});