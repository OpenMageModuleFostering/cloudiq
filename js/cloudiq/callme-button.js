function cloudiqCallmeInit() {
    var callme_button = $('cloudiq-callme-button');
    var callme_popup = $('cloudiq-callme-popup');
    var callme_popup_close_button = $('cloudiq-callme-popup-close');
    var callme_popup_form = $('cloudiq-callme-popup-form');
    var callme_popup_response = $('cloudiq-callme-popup-response');

    // Position the popup in the centre of the screen
    callme_popup.setStyle({
        top: "50%",
        left: "50%",
        marginTop: "-" + Math.ceil(callme_popup.getHeight() / 2) + "px",
        marginLeft: "-" + Math.ceil(callme_popup.getWidth() / 2) + "px"
    });

    // Toggle the callMe popup when buttons are clicked
    [callme_button, callme_popup_close_button].each(function (element) {
        element.observe("click", function () {
            var display_value = (callme_popup.getStyle("display") == "none") ? "inline-block" : "none";

            // Reset popup elements before displaying it
            if (display_value != "none") {
                callme_popup_form.setStyle({display: "block"});
                callme_popup_response.update("");
                callme_popup_response.removeClassName("cloudiq-callme-status-success");
                callme_popup_response.removeClassName("cloudiq-callme-status-failure");
            }

            callme_popup.setStyle({display: display_value});
        });
    });

    // Submit callMe callback request through Ajax
    callme_popup_form.observe("submit", function (e) {
        callme_popup_form.request({
            onComplete: function (transport) {
                var result;
                if (transport.request.success()) {
                    result = transport.responseJSON;
                } else {
                    result = {
                        status: false,
                        message: transport.statusText
                    };
                }

                // Update the response box
                var result_class = (result.status) ? "success" : "failure";
                callme_popup_response.addClassName("cloudiq-callme-status-" + result_class);
                callme_popup_response.update(result.message);

                // Hide the form
                callme_popup_form.setStyle({display: "none"});
            }
        });
        e.stop();
    });
}
