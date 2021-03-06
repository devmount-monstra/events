/* global $ */
if (typeof $.monstra == 'undefined') $.monstra = {};

$.monstra.events = {

    /* initialize document ready functions */
	init: function(){
        // activate current tab on page reload
        $.monstra.events.handleTabLinks();

        // handle color field changes
        $('#event-color').on('input change paste keyup', function(){
            $.monstra.events.setColor('event', false);
        });
        $('#category-color').on('input change paste keyup', function(){
            $.monstra.events.setColor('category', false);
        });

        // modal: new category
        $('.new-category').click(function() {
            $.monstra.events.showCategoryDialog();
        });
        // modal: edit category
        $('.edit-category').click(function() {
            $.monstra.events.showCategoryDialog($(this).val());
        });

        // modal: new location
        $('.new-location').click(function() {
            $.monstra.events.showLocationDialog();
        });
        // modal: edit location
        $('.edit-location').click(function() {
            $.monstra.events.showLocationDialog($(this).val());
        });

        // modal: new event
        $('.new-event').click(function() {
            $.monstra.events.showEventDialog(null, false);
        });
        // modal: edit event
        $('.edit-event').click(function() {
            $.monstra.events.showEventDialog($(this).val(), false);
        });
        // modal: clone event
        $('.clone-event').click(function() {
            $.monstra.events.showEventDialog($(this).attr('data'), true);
        });

        // modal: readme greybox script
        $('.readme-plugin').click(function() {
            $.ajax({
                type:'post',
                data:'readme_plugin='+$(this).attr('readme-plugin'),
                success: function(data){
                    $('#modal-documentation .modal-body').html(data);
                }
            });
        });

        // enable tooltips
        $('[data-toggle="tooltip"]').tooltip()
        
        // enable image picker
        $('.image-picker').imagepicker();
        
        // timestamp helper
        $('#copy-timestamp').click(function() {
            $('input[name="event_timestamp_end_date"]').val($('input[name="event_timestamp_date"]').val());
            $('input[name="event_timestamp_end_time"]').val($('input[name="event_timestamp_time"]').val());
        });
    },

    /* set color of input field
     * @param: type     string  'event', 'category'
     * @param: clear    bool    clear color field or not
     */
    setColor: function(type, clear) {
        var color = $('#' + type + '-color').val();
        if (color.length == 3 || color.length == 6 || clear) {
            $('#' + type + '-color').css('background-image', 'linear-gradient(to right, #fff, #fff 80%, #' + color + ' 80%)');
        } else {
            $('#' + type + '-color').css('background', 'none');
        }
    },

    /* modal: handle new / edit category
     * @param: id int  category to handle; no given id will clear the formula
     */
    showCategoryDialog: function(id) {
        var dialog = $('#modal-category');
        if (id !== null && id !== undefined && id >= 0) {
            $.ajax({
                type: 'post',
                data: 'edit_category_id=' + id,
                dataType: 'json',
                // on success: modify formula to edit
                success: function(category){
                    dialog.find('.modal-title').text($('#output_editcategory').val() + ' »' + category.title + '«');
                    dialog.find('input[name="category_title"]').val(category.title);
                    dialog.find('input[name="category_color"]').val(category.color);
                    dialog.find('select[name="category_hidden_in_archive"]').val(category.hidden_in_archive);
                    dialog.find('#add-edit-submit-category').val(category.id).attr('name', 'edit_category').text($('#output_update').val());
                    $.monstra.events.setColor('category', false);
                }
            });
        } else {
            // clear formula
            dialog.find('.modal-title').text($('#output_addcategory').val());
            dialog.find('input.clear').each(function(){ $(this).val(''); });
            dialog.find('select[name="category_hidden_in_archive"]').val(0);
            dialog.find('#add-edit-submit-category').val(1).attr('name', 'add_category').text($('#output_add').val());
            $.monstra.events.setColor('category', true);
        }
        dialog.modal('show');
    },

    /* modal: handle new / edit location
     * @param: id int  location to handle; no given id will clear the formula
     */
    showLocationDialog: function(id) {
        var dialog = $('#modal-location');
        if (id !== null && id !== undefined && id >= 0) {
            $.ajax({
                type: 'post',
                data: 'edit_location_id=' + id,
                dataType: 'json',
                // on success: modify formula to edit
                success: function(location){
                    dialog.find('.modal-title').text($('#output_editlocation').val() + ' »' + location.title + '«');
                    dialog.find('input[name="location_title"]').val(location.title);
                    dialog.find('input[name="location_website"]').val(location.website);
                    dialog.find('input[name="location_address"]').val(location.address);
                    dialog.find('input[name="location_longitude"]').val(location.lon);
                    dialog.find('input[name="location_latitude"]').val(location.lat);
                    dialog.find('#add-edit-submit-location').val(location.id).attr('name', 'edit_location').text($('#output_update').val());
                }
            });
        } else {
            // clear formula
            dialog.find('.modal-title').text($('#output_addlocation').val());
            dialog.find('input.clear').each(function(){ $(this).val(''); });
            dialog.find('#add-edit-submit-location').val(1).attr('name', 'add_location').text($('#output_add').val());
        }
        dialog.modal('show');
    },

    /* modal: handle new / edit event
     * @param: id int  event to handle; no given id will clear the formula
     */
    showEventDialog: function(id, clone) {
        var dialog = $('#modal-event');
        if (id !== null && id !== undefined && id >= 0) {
            $.ajax({
                type: 'post',
                data: 'edit_event_id=' + id,
                dataType: 'json',
                // on success: modify formula to edit
                success: function(event){
                    console.log(event);
                    clone ? dialog.find('.modal-title').text($('#output_addevent').val()) : dialog.find('.modal-title').text($('#output_editevent').val() + (event.title ? ' »' + event.title + '«' : ''));
                    dialog.find('input[name="event_title"]').val(event.title);
                    dialog.find('select[name="event_status"]').val(event.status);
                    dialog.find('input[name="event_timestamp_date"]').val(event.timestamp ? event.timestamp.slice(0,10) : '');
                    dialog.find('input[name="event_timestamp_time"]').val(event.timestamp ? event.timestamp.slice(11,16) : '');
                    dialog.find('input[name="event_timestamp_end_date"]').val(event.timestamp_end ? event.timestamp_end.slice(0,10) : '');
                    dialog.find('input[name="event_timestamp_end_time"]').val(event.timestamp_end ? event.timestamp_end.slice(11,16) : '');
                    dialog.find('select[name="event_category"]').val(event.category);
                    dialog.find('input[name="event_date"]').val(event.date);
                    dialog.find('input[name="event_openat"]').val(event.openat);
                    dialog.find('input[name="event_time"]').val(event.time);
                    dialog.find('select[name="event_location"]').val(event.location);
                    dialog.find('input[name="event_address"]').val(event.address);
                    dialog.find('input[name="event_short"]').val(event.short);
                    dialog.find('textarea[name="event_description"]').val(event.description);
                    dialog.find('textarea[name="event_archive"]').val(event.archive);
                    dialog.find('input[name="event_hashtag"]').val(event.hashtag);
                    dialog.find('input[name="event_facebook"]').val(event.facebook);
                    dialog.find('input[name="event_gallery"]').val(event.gallery);
                    dialog.find('select[name="event_image"]').val(event.image);
                    dialog.find('select[name="event_imagesection"]').val(event.imagesection ? event.imagesection : 'm');
                        $('.image-picker').data('picker').sync_picker_with_select();
                    dialog.find('input[name="event_audio"]').val(event.audio);
                    dialog.find('input[name="event_color"]').val(event.color);
                    dialog.find('input[name="event_number_staff"]').val(event.number_staff);
                    dialog.find('input[name="event_number_visitors"]').val(event.number_visitors);
                    clone ? dialog.find('#add-edit-submit-event').val(1).attr('name', 'add_event').text($('#output_add').val()) : dialog.find('#add-edit-submit-event').val(event.id).attr('name', 'edit_event').text($('#output_update').val());
                    $.monstra.events.setColor('event', false);
                }
            });
        } else {
            // clear formula
            dialog.find('.modal-title').text($('#output_addevent').val());
            dialog.find('input.clear').each(function(){ $(this).val(''); });
            dialog.find('select.clear').each(function(){ $(this).val(''); });
            dialog.find('textarea.clear').each(function(){ $(this).val(''); });
            dialog.find('select[name="event_imagesection"]').val('m');
                $('.image-picker').data('picker').sync_picker_with_select();
            dialog.find('#add-edit-submit-event').val(1).attr('name', 'add_event').text($('#output_add').val());
            $.monstra.events.setColor('event', true);
        }
        dialog.modal('show');
    },

    /* activate tab and sub tab by GET param */
    handleTabLinks: function() {
        var hash = window.location.href.split("#")[1];
        if (hash !== undefined) {
            var hpieces = hash.split("/");
            for (var i=0;i<hpieces.length;i++) {
                var domelid = hpieces[i];
                var domitem = $('a[href=#' + domelid + '][data-toggle=tab]');
                if (domitem.length > 0) {
                    domitem.tab('show');
                }
            }
        }
    }

};

// call init
$(document).ready(function(){
	$.monstra.events.init();
});