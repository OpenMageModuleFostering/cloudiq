function cloudiqCallmeInit() {
    var callme_button = $('cloudiq-callme-button');
    var callme_popup = $('cloudiq-callme-popup');
    var callme_popup_close_button = $('cloudiq-callme-popup-close');
    var callme_popup_form = $('cloudiq-callme-popup-form');
    var callme_popup_form_button = $('cloudiq-callme-popup-form-button');
    var callme_popup_response = $('cloudiq-callme-popup-response');

    // Adjust the position of the button if it's rotated
    var class_matches = callme_button.getAttribute('class').match(/cloudiq-callme-position-(left|right)(top|middle|bottom)/);
    if (class_matches) {
        var position_adjustment = {};
        if (Prototype.Browser.IE) {
            var adjustment_value = Math.abs(callme_button.getHeight() - callme_button.getWidth());
            if (class_matches[1] == "right") {
                position_adjustment[class_matches[1]] = "-" + adjustment_value + "px";
            }
            if (class_matches[2] == "bottom") {
                position_adjustment[class_matches[2]] = adjustment_value + "px";
            }
        } else {
            var adjustment_value = Math.abs(Math.round(callme_button.getWidth() / 2 - callme_button.getHeight() / 2));
            position_adjustment[class_matches[1]] = "-" + adjustment_value + "px";
            if (class_matches[2] != "middle") {
                position_adjustment[class_matches[2]] = adjustment_value + "px";
            }
        }
        if (class_matches[2] == "middle") {
            position_adjustment["marginTop"] = "-" + Math.round(callme_button.getHeight() / 2) + "px";
        }
        callme_button.setStyle(position_adjustment);
    }

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
                callme_popup_response.update("");
                callme_popup_response.removeClassName("cloudiq-callme-status-success");
                callme_popup_response.removeClassName("cloudiq-callme-status-failure");
                callme_popup_form.select("label, input, button").each(function (e) {
                    e.show();
                });
            }

            callme_popup.setStyle({display: display_value});
        });
    });

    // Submit callMe callback request through Ajax
    callme_popup_form.observe("submit", function (e) {
        callme_popup_response.update("");
        callme_popup_response.removeClassName("cloudiq-callme-status-success");
        callme_popup_response.removeClassName("cloudiq-callme-status-failure");
        callme_popup_form_button.update("Loading...");

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

                // Update the popup
                var result_class = (result.status) ? "success" : "failure";
                callme_popup_response.addClassName("cloudiq-callme-status-" + result_class);
                callme_popup_response.update(result.message);
                callme_popup_form_button.update("Submit");
                if (result.status) {
                    callme_popup_form.select("label, input, button").each(function (e) {
                        e.hide();
                    });
                }
            }
        });
        e.stop();
    });
}
