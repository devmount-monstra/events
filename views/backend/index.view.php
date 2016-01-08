<?php 
    // get current time
    $now = time();
    // get all existing categories from db
    $categories = new Table('categories');
    $categories = $categories->select(null, 'all');
    $categories_title = array();
    foreach ($categories as $c) {
        $categories_title[$c['id']] = $c['title'];
    }
    // get all existing events from db
    $events = new Table('events');
    $upcomingevents = $events->select('[timestamp>=' . $now . ']');
    $pastevents = $events->select('[timestamp<' . $now . ']');
?>
<!-- custom plugin styles -->
<style>
    .events-plugin .row {
        margin-bottom: 10px;
    }
</style>

<!-- custom plugin script -->
<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    // color field
    $('#color').on("input change paste keyup", function(){
        setColor();
    });
    // $('.list-group-item').focusin(function(){
    //     $(this).addClass('active');
    // });
    // $('.list-group-item').focusout(function(){
    //     $(this).removeClass('active');
    //     $('#add-edit-title').html('<?php echo __('Add event', 'events'); ?>');
    // });
    $('.btn.edit').click(function(e){
        var id = $(this).val();
        $.ajax({
            type: 'post',
            data: 'edit_event_id=' + id,
            url: '<?php echo Site::url(); ?>/admin/index.php?id=events',
            // on success: modify formula to edit
            success: function(data){
                var event = JSON.parse(data);
                var date = new Date(event.timestamp * 1000).toISOString().slice(0, -1);
                // change title
                $('#add-edit-title').html('<?php echo __('Edit event', 'events'); ?> ' + event.title);
                // insert existing values
                $('input[name="events_title"]').val(event.title);
                $('input[name="events_timestamp"]').val(date);
                $('select[name="events_category"]').val(event.category);
                $('input[name="events_date"]').val(event.date);
                $('input[name="events_time"]').val(event.time);
                $('input[name="events_location"]').val(event.location);
                $('input[name="events_short"]').val(event.short);
                $('textarea[name="events_description"]').val(event.description);
                $('input[name="events_image"]').val(event.image);
                $('input[name="events_audio"]').val(event.audio);
                $('input[name="events_color"]').val(event.color);
                // set color
                setColor();
                // change input name to id edit
                $('#add-edit-submit')
                    .attr('name', 'edit_event')
                    .val(id)
                    .attr('title', '<?php echo __('Update', 'events'); ?>');
                $('#add-edit-submit>span').toggleClass('glyphicon-plus').toggleClass('glyphicon-pencil');
                $(window).scrollTo($('#add-edit-title'), 200);
            }
        });
        e.preventDefault();
    });
    
    // url: "<?php echo Option::get('siteurl'); ?>index.php?id=pages&action=add_page",

    
});

function setColor() {
    var color = $('#color').val();
    if (color.length == 3 || color.length == 6) {
        $('#color').css('background-image', 'linear-gradient(to right, #fff, #fff 70%, #' + color + ' 70%)');
    } else {
        $('#color').css('background-image', 'none');
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
<div class='events-plugin'>
    
    <div class="vertical-align margin-bottom-1">
        <div class="text-left row-phone">
            <h2><?php echo __('Events', 'events'); ?></h2>
        </div>
        <div class="text-right row-phone">
            <a href="index.php?id=pages&amp;action=add_page" title="New Event" class="btn btn-phone btn-primary">New Event</a>&nbsp;&nbsp;&nbsp;
            <a href="index.php?id=pages&amp;action=add_page" title="New Category" class="btn btn-phone btn-primary">New Category</a>&nbsp;&nbsp;&nbsp;
            <a href="index.php?id=pages&amp;action=edit_page&amp;name=error404" title="Documentation" class="btn btn-phone btn-default">Documentation</a>
        </div>
    </div>
    
    <div class="tabbable mobile-nav-tabs"></div>
        
        <!-- Tab navigation -->
        <ul class="nav nav-tabs">
            <li><a href="#events">Events</a></li>
            <li><a href="#categories">Categories</a></li>
        </ul>
        
        <!-- Tab content -->
        <div class="tab-content">
            <div class="tab-pane active" id="events">
                <div class="row">
                    <div class="col-md-6">
                        <h2><?php echo __('Upcoming events', 'events'); ?></h2>
                        <div class="list-group">
                            <?php if (sizeof($upcomingevents) > 0) {
                                foreach ($upcomingevents as $event) { ?>
                                    <a href="#" class="list-group-item">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit" value="<?php echo $event['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
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
                                        <p class="list-group-item-text"><?php echo $event['timestamp']; ?></p>
                                        <br style="clear: both;" />
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
                                    <a href="#" class="list-group-item">
                                        <div class="pull-right">
                                            <button class="btn btn-sm btn-default edit" value="<?php echo $event['id'] ?>" title="<?php echo __('Edit', 'events'); ?>">
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
                                        <p class="list-group-item-text"><?php echo $event['timestamp']; ?></p>
                                        <br style="clear: both;" />
                                    </a>
                                <?php }
                            } else {
                                echo __('No past events', 'events');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 id="add-edit-title"><?php echo __('Add event', 'events'); ?></h2>
                        <?php echo
                            Form::open() .
                            Form::hidden('csrf', Security::token());
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('events_title', __('Title', 'events')) .
                                    Form::input('events_title', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('events_timestamp', __('Timestamp', 'events')) .
                                    Form::input('events_timestamp', '', array('class' => 'form-control', 'type' => 'datetime-local'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('events_date', __('Date', 'events')) .
                                    Form::input('events_date', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('events_time', __('Time', 'events')) .
                                    Form::input('events_time', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <?php echo
                                    Form::label('events_category', __('Category', 'events')) .
                                    Form::select('events_category', $categories_title, Null, array('class' => 'form-control'));
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo
                                    Form::label('events_color', __('Color', 'events')) .
                                    Form::input('events_color', '', array('class' => 'form-control', 'id' => 'color', 'placeholder' => '#'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('events_location', __('Location', 'events')) .
                                    Form::input('events_location', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('events_short', __('Short description', 'events')) .
                                    Form::input('events_short', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo
                                    Form::label('events_description', __('Description', 'events')) .
                                    Form::textarea('events_description', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('events_image', __('Image path', 'events')) .
                                    Form::input('events_image', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('events_audio', __('Audio path', 'events')) .
                                    Form::input('events_audio', Null, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="add_event" id="add-edit-submit" class="btn btn-lg btn-primary pull-right" value="1" title="<?php echo __('Add', 'events'); ?>">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="categories">
                <!--  -->
                <h2><?php echo __('Categories', 'events'); ?></h2>
                <ul>
                <?php
                    foreach ($categories as $category) {
                ?>
                        <li><?php echo $category['title'] ?></li>
                <?php } ?>
                </ul>
            </div>
        </div>
        
    </div>

</div>