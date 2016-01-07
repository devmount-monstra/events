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
<script type="text/javascript">
$(document).ready(function(){
    // color field
    $('#color').on('keyup', function() {
        var color = $(this).val();
        if (color.length == 3 || color.length == 6) {
            $(this).css('background-image', 'linear-gradient(to right, #fff, #fff 70%, #' + color + ' 70%)');
        } else {
            $(this).css('background-image', 'none');
        }
    });
    // $('.list-group-item').focusin(function(){
    //     $(this).addClass('active');
    // });
    // $('.list-group-item').focusout(function(){
    //     $(this).removeClass('active');
    //     $('#add-edit').html('<?php echo __('Add event', 'events'); ?>');
    // });
    $('.btn.edit').click(function(){
        var id = $(this).val();
        $('#add-edit').html('<?php echo __('Edit event', 'events'); ?>');
        $.ajax({
            type: 'post',
            data: 'edit_event_id=' + id,
            url: '<?php echo Site::url(); ?>/admin/index.php?id=events',
            success: function(data){
                $('#add-edit').html(data);
            }
        });
    });
    
    // url: "<?php echo Option::get('siteurl'); ?>index.php?id=pages&action=add_page",

    
});
</script>

<!-- notifications -->
<?php
    Notification::get('success') AND Alert::success(Notification::get('success'));
    Notification::get('warning') AND Alert::warning(Notification::get('warning'));
    Notification::get('error')   AND Alert::error(Notification::get('error'));
?>

<!-- content -->
<div class='events-plugin'>
    <h1><?php echo __('Events', 'events'); ?></h1>
    <p>An event managment plugin for Monstra. See <a href="https://github.com/devmount-monstra/events" target="_blank">documentation</a> for further information.</p>
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
            <h2 id="add-edit"><?php echo __('Add event', 'events'); ?></h2>
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
                    <button type="submit" name="add_event" class="btn btn-lg btn-primary pull-right" value="1" title="<?php echo __('Add', 'events'); ?>">
                        <span class="glyphicon glyphicon-save"></span>
                    </button>
                </div>
            </div>
            <?php echo Form::close(); ?>
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