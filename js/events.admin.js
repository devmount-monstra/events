/* global $ */

$(document).ready(function () {
    
    // color field
    $('#event-color').on('input change paste keyup', function(){
        setColor('event', false);
    });
    $('#category-color').on('input change paste keyup', function(){
        setColor('category', false);
    });
    // handle event form
    $('.btn.edit-event').click(function(e){
        handleForm('event', $(this).val());
        e.preventDefault();
    });
    // handle category form
    $('.btn.edit-category').click(function(e){
        handleForm('category', $(this).val());
        e.preventDefault();
    });

    // handle remote activate tab, clear 'edit' form to 'new' form
    $('.btn[data-toggle=tab]').click(function(){
        var type = $(this).attr("href").substring(1);
        $('.nav-tabs li.active').toggleClass('active');
        $('.nav-tabs li a[href="#' + type + '"]').parent().toggleClass('active');
        $('#focus-' + type).focus();
        $('#add-edit-' + type + ' .clear').each(function(){
            $(this).val('');
        });
        $('#event_category').val($('#event_category>option:first-child').val());
        var shorttype;
        switch(type) {
            case 'events': shorttype = 'event'; break;
            case 'categories': shorttype = 'category'; break;
            default: break;
        }
        $('#add-edit-title-' + shorttype).html($('#output_add').val() + ' ' + shorttype);
        setColor(shorttype, true);
        $('#add-edit-submit-' + shorttype)
            .attr('name', 'add_' + shorttype)
            .val(1)
            .attr('title', $('#output_add').val())
            .text($('#output_add').val());
        $('input[name="event_imagesection"]').attr("checked", false);
        $('.image-section-label>div.checked').removeClass('checked');
        $('input[name="event_imagesection"][value="m"]').attr('checked', 'checked');
        $('input[name="event_imagesection"][value="m"]').parent().attr('aria-checked', true);
        $('input[name="event_imagesection"][value="m"]').parent().addClass('checked');

    })
    
    // modal: readme greybox script
    $('.readme_plugin').click(function() {
        $.ajax({
            type:'post',
            data:'readme_plugin='+$(this).attr('readme_plugin'),
            success: function(data){
                $('#readme .modal-body').html(data);
            }
        });
    });

});


// handle form / ajax
// @param type: ['event', 'category']
// @param id:   of record to edit
function handleForm(type, id) {
    $.ajax({
        type: 'post',
        data: 'edit_' + type + '_id=' + id,
        // on success: modify formula to edit
        success: function(data){
            switch (type) {
                case 'event':
                    var event = JSON.parse(data);
                    var date = event.timestamp ? new Date(event.timestamp * 1000).toISOString().slice(0, -1) : '';
                    // change title
                    $('#add-edit-title-event').html($('#output_editevent').val() + ' ' + event.title);
                    // insert existing values
                    $('input[name="event_title"]').val(event.title);
                    $('input[name="event_timestamp"]').val(date);
                    $('select[name="event_category"]').val(event.category);
                    $('input[name="event_date"]').val(event.date);
                    $('input[name="event_time"]').val(event.time);
                    $('input[name="event_location"]').val(event.location);
                    $('input[name="event_short"]').val(event.short);
                    $('textarea[name="event_description"]').val(event.description);
                    $('select[name="event_image"]').val(event.image);
                    if (event.imagesection === '') event.imagesection = 'm';
                    $('input[name="event_imagesection"]').attr("checked", false);
                        $('.image-section-label>div.checked').removeClass('checked');
                        $('input[name="event_imagesection"][value="' + event.imagesection + '"]').attr('checked', 'checked');
                        $('input[name="event_imagesection"][value="' + event.imagesection + '"]').parent().attr('aria-checked', true);
                        $('input[name="event_imagesection"][value="' + event.imagesection + '"]').parent().addClass('checked');
                    $('input[name="event_audio"]').val(event.audio);
                    $('input[name="event_color"]').val(event.color);
                    break;
                case 'category':
                    var category = JSON.parse(data);
                    // change title
                    $('#add-edit-title-category').html($('#output_editcategory').val() + ' ' + category.title);
                    // insert existing values
                    $('input[name="category_title"]').val(category.title);
                    $('input[name="category_color"]').val(category.color);
                    break;
                default:
                    break;
            }
            // set color
            setColor(type, false);
            // change input name to id edit
            $('#add-edit-submit-' + type)
                .attr('name', 'edit_' + type)
                .val(id)
                .attr('title', $('#output_update').val())
                .text($('#output_update').val());
            $(window).scrollTo($('#add-edit-title-' + type), 200);
        }
    });
}


// set color of input field
function setColor(type, clear) {
    var color = $('#' + type + '-color').val();
    if (color.length == 3 || color.length == 6 || clear) {
        $('#' + type + '-color').css('background-image', 'linear-gradient(to right, #fff, #fff 80%, #' + color + ' 80%)');
    } else {
        $('#' + type + '-color').css('background', 'none');
    }
}