jQuery(document).ready(function ($) {
  $("#lookway_booking_booking_submit").on("click", function (e) {
    e.preventDefault();

    $.ajax({
      url: lookway_booking_bookingform_var.ajaxurl,
      type: "post",
      data: {
        action: "booking_form",
        nonce: lookway_booking_bookingform_var.nonce,
        title: lookway_booking_bookingform_var.title,
        name: $("#lookway_booking_name").val(),
        email: $("#lookway_booking_email").val(),
        phone: $("#lookway_booking_phone").val(),
        price: $("#lookway_booking_price").val(),
        location: $("#lookway_booking_location").val(),
        agent: $("#lookway_booking_agent").val(),
      },
      success: function (data) {
        $("#lookway_booking_result").html(data);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  });
});
