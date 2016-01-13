<?php //var_dump($directories); ?>

<!-- custom plugin script -->
<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    // color field
    $('#event-color').on('input change paste keyup', function(){
        setColor(this);
    });
    $('#category-color').on('input change paste keyup', function(){
        setColor(this);
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
        switch(type) {
            case 'events': shorttype = 'event'; break;
            case 'categories': shorttype = 'category'; break;
            default: break;
        }
        $('#add-edit-title-' + shorttype).html('<?php echo __('Add', 'events'); ?> ' + shorttype);
        $('#add-edit-submit-' + shorttype)
            .attr('name', 'add_' + shorttype)
            .val(1)
            .attr('title', '<?php echo __('Add', 'events'); ?>')
            .text('<?php echo __('Add', 'events'); ?>');
    })
});

// handle form / ajax
// @param type: ['event', 'category']
// @param id:   of record to edit
function handleForm(type, id) {
    $.ajax({
        type: 'post',
        data: 'edit_' + type + '_id=' + id,
        url: '<?php echo Site::url(); ?>/admin/index.php?id=events',
        // on success: modify formula to edit
        success: function(data){
            switch (type) {
                case 'event':
                    var event = JSON.parse(data);
                    var date = event.timestamp ? new Date(event.timestamp * 1000).toISOString().slice(0, -1) : '';
                    // change title
                    $('#add-edit-title-event').html('<?php echo __('Edit event', 'events'); ?> ' + event.title);
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
                    $('input[name="event_audio"]').val(event.audio);
                    $('input[name="event_color"]').val(event.color);
                    break;
                case 'category':
                    var category = JSON.parse(data);
                    // change title
                    $('#add-edit-title-category').html('<?php echo __('Edit category', 'events'); ?> ' + category.title);
                    // insert existing values
                    $('input[name="category_title"]').val(category.title);
                    $('input[name="category_color"]').val(category.color);
                    break;
                default:
                    break;
            }
            // set color
            setColor('#' + type + '-color');
            // change input name to id edit
            $('#add-edit-submit-' + type)
                .attr('name', 'edit_' + type)
                .val(id)
                .attr('title', '<?php echo __('Update', 'events'); ?>')
                .text('<?php echo __('Update', 'events'); ?>');
            $(window).scrollTo($('#add-edit-title-' + type), 200);
        }
    });
}

// set color of input field
function setColor(type) {
    var color = $(type).val();
    if (color.length == 3 || color.length == 6) {
        $(type).css('background-image', 'linear-gradient(to right, #fff, #fff 70%, #' + color + ' 70%)');
    } else {
        $(type).css('background-image', 'none');
    }
}
</script>

<!-- notifications -->
<?php
    Notification::get('success') AND Alert::success(Notification::get('success'));
    Notification::get('warning') AND Alert::warning(Notification::get('warning'));
    Notification::get('error')   AND Alert::error(Notification::get('error'));
?>

<!-- content -->
<div class='events-admin'>
    
    <div class="vertical-align margin-bottom-1">
        <div class="text-left row-phone">
            <h2><?php echo __('Events', 'events'); ?></h2>
        </div>
        <div class="text-right row-phone">
            <a href="#events" data-toggle="tab" title="New Event" class="btn btn-phone btn-primary">New Event</a>
            <a href="#categories" data-toggle="tab" title="New Category" class="btn btn-phone btn-primary">New Category</a>
            <?php echo Html::anchor(__('Documentation', 'events'), '#', array('class' => 'btn btn-phone btn-default readme_plugin', 'data-toggle' => 'modal', 'data-target' => '#readme', 'readme_plugin' => 'events')); ?>
        </div>
    </div>
    
    <div class="tabbable mobile-nav-tabs"></div>
        
        <!-- Tab navigation -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#events" data-toggle="tab">Events</a></li>
            <li><a href="#categories" data-toggle="tab">Categories</a></li>
            <li><a href="#configuration" data-toggle="tab">Configuration</a></li>
        </ul>
        
        <!-- Tab content -->
        <div class="tab-content">
            
            <!-- Tab: events -->
            <div class="tab-pane active" id="events">
                <div class="row">
                    <div class="col-md-6">   
                        <h2><?php echo __('Upcoming events', 'events'); ?></h2>
                        <div class="list-group">
                            <?php if (sizeof($upcomingevents) > 0) {
                                foreach ($upcomingevents as $event) { ?>
                                    <a href="#" class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit-event" value="<?php echo $event['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_event', $event['id']);
                                            ?>
                                                <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete event &quot;:title&quot;', 'events', array(':title' => $event['title'])); ?>')" title="<?php echo __('Delete', 'events'); ?>">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            <?php echo Form::close(); ?>
                                        </div>
                                        <div class="pull-left">
                                            <img src="<?php echo $event['image']; ?>" />
                                        </div>
                                        <h4 class="list-group-item-heading"><?php echo $event['title']; ?></h4>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']]; ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </a>
                                <?php }
                            } else {
                                echo __('No upcoming events', 'events');
                            }
                            ?>
                        </div>
                        <h2><?php echo __('Past events', 'events'); ?></h2>
                        <div class="list-group">
                            <?php if (sizeof($pastevents) > 0) {
                                foreach ($pastevents as $event) { ?>
                                    <a href="#" class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit-event" value="<?php echo $event['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_event', $event['id']);
                                            ?>
                                                <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete event &quot;:title&quot;', 'events', array(':title' => $event['title'])); ?>')" title="<?php echo __('Delete', 'events'); ?>">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            <?php echo Form::close(); ?>
                                        </div>
                                        <h4 class="list-group-item-heading"><?php echo $event['title']; ?></h4>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']]; ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </a>
                                <?php }
                            } else {
                                echo __('No past events', 'events');
                            }
                            ?>
                        </div>
                        <h2><?php echo __('Draft events', 'events'); ?></h2>
                        <div class="list-group">
                            <?php if (sizeof($draftevents) > 0) {
                                foreach ($draftevents as $event) { ?>
                                    <a href="#" class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit-event" value="<?php echo $event['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_event', $event['id']);
                                            ?>
                                                <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete event &quot;:title&quot;', 'events', array(':title' => $event['title'])); ?>')" title="<?php echo __('Delete', 'events'); ?>">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            <?php echo Form::close(); ?>
                                        </div>
                                        <h4 class="list-group-item-heading"><?php echo $event['title']; ?></h4>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']]; ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </a>
                                <?php }
                            } else {
                                echo __('No draft events', 'events');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 id="add-edit-title-event"><?php echo __('Add event', 'events'); ?></h2>
                        <?php echo
                            Form::open(Null, array('id' => 'add-edit-events')) .
                            Form::hidden('csrf', Security::token());
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('event_title', __('Title', 'events')) .
                                    Form::input('event_title', Null, array('class' => 'form-control clear', 'id' => 'focus-events', 'required' => 'required'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('event_timestamp', __('Timestamp', 'events')) .
                                    Form::input('event_timestamp', '', array('class' => 'form-control clear', 'type' => 'datetime-local'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('event_date', __('Date', 'events')) .
                                    Form::input('event_date', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('event_time', __('Time', 'events')) .
                                    Form::input('event_time', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <?php echo
                                    Form::label('event_category', __('Category', 'events')) .
                                    Form::select('event_category', $categories_title, Null, array('class' => 'form-control clear', 'required' => 'required'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('event_color', __('Color', 'events')) .
                                    Form::input('event_color', '', array('class' => 'form-control clear', 'id' => 'event-color', 'placeholder' => '#'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('event_location', __('Location', 'events')) .
                                    Form::input('event_location', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('event_short', __('Short description', 'events')) .
                                    Form::input('event_short', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('event_description', __('Description', 'events')) .
                                    Form::textarea('event_description', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?php
                                    echo Form::label('event_image', __('Image file', 'events'));
                                    // Form::input('event_image', Null, array('class' => 'form-control clear'));
                                    if (sizeof($files)>1) {
                                        echo Form::select('event_image', $files, Null, array('class' => 'form-control'));
                                    } else {
                                        echo Form::select('event_image', array(), Null, array('class' => 'form-control', 'disabled' => 'disabled', 'title' => __('No file available in configured image directory', 'events')));
                                    }
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('event_audio', __('Audio file', 'events')) .
                                    Form::input('event_audio', Null, array('class' => 'form-control clear'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="add_event" id="add-edit-submit-event" class="btn btn-lg btn-primary pull-right" value="1" title="<?php echo __('Add', 'events'); ?>">
                                    <?php echo __('Add', 'events'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Tab: categories -->
            <div class="tab-pane" id="categories">
                <div class="row">
                    <div class="col-md-6">
                        <h2><?php echo __('Categories', 'events'); ?></h2>
                        <div class="list-group">
                            <?php if (sizeof($categories) > 0) {
                                foreach ($categories as $category) { ?>
                                    <a href="#" class="list-group-item" style="border-left: 5px solid #<?php echo $category['color']; ?>">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit-category" value="<?php echo $category['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_category', $category['id']);
                                            ?>
                                                <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete category &quot;:title&quot;', 'events', array(':title' => $category['title'])); ?>')" title="<?php echo __('Delete', 'events'); ?>">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            <?php echo Form::close(); ?>
                                        </div>
                                        <h4 class="list-group-item-heading"><?php echo $category['title']; ?></h4>
                                        <p class="list-group-item-text"><?php echo $categories_count[$category['id']] . ' ' . __('events assigned', 'events'); ?></p>
                                        <!-- TODO: number of events for each category -->
                                    </a>
                                <?php }
                            } else {
                                echo __('No category available.', 'events');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 id="add-edit-title-category"><?php echo __('Add category', 'events'); ?></h2>
                        <?php echo
                            Form::open(Null, array('id' => 'add-edit-categories')) .
                            Form::hidden('csrf', Security::token());
                        ?>
                        <div class="row">
                            <div class="col-sm-9">
                                <?php echo
                                    Form::label('category_title', __('Title', 'events')) .
                                    Form::input('category_title', Null, array('class' => 'form-control clear', 'id' => 'focus-categories', 'required' => 'required'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('category_color', __('Color', 'events')) .
                                    Form::input('category_color', '', array('class' => 'form-control clear', 'id' => 'category-color', 'placeholder' => '#'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="add_category" id="add-edit-submit-category" class="btn btn-lg btn-primary pull-right" value="1" title="<?php echo __('Add', 'events'); ?>">
                                    <?php echo __('Add', 'events'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Tab: configuration -->
            <div class="tab-pane" id="configuration">
                <?php echo
                    Form::open() .
                    Form::hidden('csrf', Security::token());
                ?>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <!-- config image directory -->
                        <?php echo
                            Form::label('events_image_directory', __('Image directory for events', 'events')) .
                            Form::select('events_image_directory', $directories, Option::get('events_image_directory'), array('class' => 'form-control'));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- TODO: config audio directory -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" name="events_options" class="btn btn-lg btn-primary pull-right" value="1" title="<?php echo __('Save', 'events'); ?>">
                            <?php echo __('Save', 'events'); ?>
                        </button>
                    </div>
                </div>
                <?php echo Form::close(); ?>
            </div>
        </div>
        
    </div>

</div>

<!-- modal: readme greybox script -->
<script>
    $(document).ready(function () {
        $('.readme_plugin').click(function() {
            $.ajax({
                type:'post',
                data:'readme_plugin='+$(this).attr('readme_plugin'),
                url: '<?php echo Site::url(); ?>/admin/index.php?id=plugins',
                success: function(data){
                    $('#readme .modal-body').html(data);
                }
            });
        });
    });
</script>

<!-- modal: markup -->
<div class="modal fade" id="readme" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="close" data-dismiss="modal">&times;</div>
        <h4 class="modal-title" id="myModalLabel">README.md</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>