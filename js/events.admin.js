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

        // modal: new event
        $('.new-event').click(function() {
            $.monstra.events.showEventDialog();
        });
        // modal: edit event
        $('.edit-event').click(function() {
            $.monstra.events.showEventDialog($(this).val());
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
                    dialog.find('.modal-title').text($('#output_editcategory').val() + ' « ' + category.title + ' »');
                    dialog.find('input[name="category_title"]').val(category.title);
                    dialog.find('input[name="category_color"]').val(category.color);
                    dialog.find('#add-edit-submit-category').val(category.id).attr('name', 'edit_category').text($('#output_update').val());
                    $.monstra.events.setColor('category', false);
                }
            });
        } else {
            // clear formula
            dialog.find('.modal-title').text($('#output_addcategory').val());
            dialog.find('input.clear').each(function(){ $(this).val(''); });
            dialog.find('#add-edit-submit-category').val(1).attr('name', 'add_category').text($('#output_add').val());
            $.monstra.events.setColor('category', true);
        }
        dialog.modal('show');
    },

    /* modal: handle new / edit event
     * @param: id int  event to handle; no given id will clear the formula
     */
    showEventDialog: function(id) {
        var dialog = $('#modal-event');
        if (id !== null && id !== undefined && id >= 0) {
            $.ajax({
                type: 'post',
                data: 'edit_event_id=' + id,
                dataType: 'json',
                // on success: modify formula to edit
                success: function(event){
                    dialog.find('.modal-title').text($('#output_editevent').val() + ' « ' + event.title + ' »');
                    dialog.find('input[name="event_title"]').val(event.title);
                    dialog.find('input[name="event_timestamp"]').val(event.timestamp ? new Date(event.timestamp * 1000).toISOString().slice(0, -1) : '');
                    dialog.find('select[name="event_category"]').val(event.category);
                    dialog.find('input[name="event_date"]').val(event.date);
                    dialog.find('input[name="event_time"]').val(event.time);
                    dialog.find('input[name="event_location"]').val(event.location);
                    dialog.find('input[name="event_short"]').val(event.short);
                    dialog.find('textarea[name="event_description"]').val(event.description);
                    dialog.find('select[name="event_image"]').val(event.image);
                    if (event.imagesection === '') event.imagesection = 'm';
                    dialog.find('input[name="event_imagesection"]').attr("checked", false);
                        dialog.find('.image-section-label>div.checked').removeClass('checked');
                        dialog.find('input[name="event_imagesection"][value="' + event.imagesection + '"]').attr('checked', 'checked');
                        dialog.find('input[name="event_imagesection"][value="' + event.imagesection + '"]').parent().attr('aria-checked', true);
                        dialog.find('input[name="event_imagesection"][value="' + event.imagesection + '"]').parent().addClass('checked');
                    dialog.find('input[name="event_audio"]').val(event.audio);
                    dialog.find('input[name="event_color"]').val(event.color);
                
                    dialog.find('#add-edit-submit-event').val(event.id).attr('name', 'edit_event').text($('#output_update').val());
                    $.monstra.events.setColor('event', false);
                }
            });
        } else {
            // clear formula
            dialog.find('.modal-title').text($('#output_addevent').val());
            dialog.find('input.clear').each(function(){ $(this).val(''); });
            dialog.find('select.clear').each(function(){ $(this).val(''); });
            dialog.find('textarea.clear').each(function(){ $(this).val(''); });
            dialog.find('input[name="event_imagesection"]').attr("checked", false);
            dialog.find('.image-section-label>div.checked').removeClass('checked');
            dialog.find('input[name="event_imagesection"][value="m"]').attr('checked', 'checked');
            dialog.find('input[name="event_imagesection"][value="m"]').parent().attr('aria-checked', true);
            dialog.find('input[name="event_imagesection"][value="m"]').parent().addClass('checked');
            dialog.find('#add-edit-submit-event').val(1).attr('name', 'add_event').text($('#output_add').val());
            $.monstra.events.setColor('event', true);
        }
        dialog.modal('show');
    },
    
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