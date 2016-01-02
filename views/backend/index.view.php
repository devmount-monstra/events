<h2><?php echo __('Events', 'events'); ?></h2>
<br />
<?php
    echo (
        Form::open() .
        Form::hidden('csrf', Security::token())
    );
?>

<div class="row">
    <div class="col-md-6">
        <?php
            echo (
                Form::label('events_duration', __('Slide duration [ms]', 'events')) .
                Form::input('events_duration', Option::get('events_duration'), array('class' => 'form-control'))
            );
        ?>
    </div>
    <div class="col-md-6">
        <?php
            echo (
                Form::label('events_easing', __('Slide easing', 'events')) .
                Form::select('events_easing', array('linear' => 'linear', 'swing' => 'swing'), Option::get('events_easing'), array('class' => 'form-control'))
            );
        ?>
    </div>
</div>
<br />

<?php
    echo (
        Form::submit('events_submitted', __('Save', 'events'), array('class' => 'btn btn-phone btn-primary')) .
        Form::close()
    );
?>