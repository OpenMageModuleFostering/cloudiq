/**
 * Disable the "Display on selected pages" selection box when "Display on all pages"
 * gets selected and vice versa when the selection box gets clicked.
 */
document.observe("dom:loaded", function() {
    var all_pages = $("callme[published][all]");
    var selected_pages = $("callme[published][pages]");

    all_pages.observe("change", function () {
        selected_pages.disabled = all_pages.checked ? true : false;
    });
    selected_pages.parentElement.observe("click", function() {
        selected_pages.disabled = false;
        all_pages.checked = false;
    });
});

/**
 * Display a preview of the callMe button
 */
document.observe("dom:loaded", function () {
    var preview_url = $('callme[button][preview_url]').value;
    var config_form = $('edit_form');
    var button_form_section = $('button');
    var popup_form_section = $('popup');

    var button_preview = new Element("div", {id: "cloudiq_callme_button_preview"});
    button_preview.setStyle({display: "none"});
    $(document.body).insert(button_preview);

    function updateCloudiqCallmePreview() {
        new Ajax.Updater(button_preview, preview_url, {
            method: "POST",
            parameters: config_form.serialize(true),
            evalScripts: true
        });
    }

    // Update the preview when button or popup configuration is changed
    button_form_section.select('input, select')
        .concat(popup_form_section.select('input, select'))
        .each(function (element) {
            element.observe("change", updateCloudiqCallmePreview);
        });

    // Only show the preview when the callMe config form tab is open
    varienGlobalEvents.attachEventHandler("showTab", function (event) {
        if (event.tab.name == "callme_section") {
            button_preview.setStyle({display: "block"});
            updateCloudiqCallmePreview();
        } else {
            button_preview.setStyle({display: "none"});
        }
    });
});
