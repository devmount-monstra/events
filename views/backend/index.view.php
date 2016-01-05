<?php 
    // get all existing categories from db
    $categories = new Table('categories');
    $categories = $categories->select(null, 'all');
    $categories_title = array();
    foreach ($categories as $c) {
        $categories_title[$c['id']] = $c['title'];
    }
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
    $('#color').on('keyup', function() {
        var color = $(this).val();
        if (color.length == 3 || color.length == 6) {
            $(this).css('background-image', 'linear-gradient(to right, #fff, #fff 80%, #' + color + ' 80%)');
        }
    });
});
</script>

<!-- content -->
<div class='events-plugin'>
    <h1><?php echo __('Events', 'events'); ?></h1>
    <p>An event managment plugin for Monstra. See <a href="https://github.com/devmount-monstra/events" target="_blank">documentation</a> for further information.</p>
    <div class="row">
        <div class="col-md-6">
            <h2><?php echo __('Upcoming events', 'events'); ?></h2>
            <h2><?php echo __('Past events', 'events'); ?></h2>
        </div>
        <div class="col-md-6">
            <h2><?php echo __('Add event', 'events'); ?></h2>
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
                <div class="col-sm-4">
                    <?php echo
                        Form::label('events_timestamp', __('Timestamp', 'events')) .
                        Form::input('events_timestamp', '', array('class' => 'form-control', 'type' => 'datetime-local'));
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php echo
                        Form::label('events_date', __('Date', 'events')) .
                        Form::input('events_date', Null, array('class' => 'form-control'));
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php echo
                        Form::label('events_time', __('Time', 'events')) .
                        Form::input('events_time', Null, array('class' => 'form-control'));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <?php echo
                        Form::label('events_category', __('Category', 'events')) .
                        Form::select('events_category', $categories_title, Null, array('class' => 'form-control'));
                    ?>
                </div>
                <div class="col-sm-4">
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
                        Form::label('events_description', __('Description', 'events')) .
                        Form::input('events_description', Null, array('class' => 'form-control'));
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
            <?php echo
                Form::submit('events_submitted', __('Add', 'events'), array('class' => 'btn btn-phone btn-primary')) .
                Form::close();
            ?>
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