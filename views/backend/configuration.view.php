<?php echo Html::heading(__('Configuration', 'events'), 2); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo Html::heading(__('Options', 'events'), 3); ?>
        <?php echo
            Form::open() .
            Form::hidden('csrf', Security::token()) .
            HTML::br();
        ?>
        <div class="row">
            <div class="col-md-6">
                <!-- config image directory -->
                <?php echo
                    Form::label('events_image_directory', __('Image directory for events', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Existing directory for event images, that will be displayed in the select list of the events add/edit form', 'events'))) .
                    Form::select('events_image_directory', $directories, Option::get('events_image_directory'), array('class' => 'form-control'));
                ?>
            </div>
            <div class="col-md-6">
                <!-- config archive description placeholder -->
                <?php echo
                    Form::label('events_placeholder_archive', __('Archive description field', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Custom placeholder text for "Archive description" textarea in the events add/edit form', 'events'))) .
                    Form::input('events_placeholder_archive', Option::get('events_placeholder_archive'), array('class' => 'form-control'));
                ?>
            </div>
        </div>
        <div class="row margin-top-1">
            <div class="col-sm-12">
                <button
                    type="submit"
                    name="events_options_update_and_exit"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Save and Exit', 'events'); ?>"
                >
                    <?php echo __('Save and Exit', 'events'); ?>
                </button>
                <button
                    type="submit"
                    name="events_options_update"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Save', 'events'); ?>"
                >
                    <?php echo __('Save', 'events'); ?>
                </button>
                <?php echo Html::anchor(__('Cancel', 'events'), 'index.php?id=events', array('title' => __('Cancel', 'events'), 'class' => 'btn btn-default')); ?>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
    <div class="col-md-6">
        <hr class="visible-sm visible-xs"/>
        <?php echo Html::heading(__('Actions', 'events'), 3); ?>
        <?php echo Html::heading(__('Resize Images', 'events'), 4); ?>
        <p><?php echo __('Use this tool to resize all images from the configured image directory. Resized images are saved into a subdirectory and automatically loaded if existing.', 'events'); ?></p>
        <?php echo
            Form::open() .
            Form::hidden('csrf', Security::token()) .
            HTML::br();
        ?>
        <div class="row">
            <div class="col-md-6">
                <?php echo
                    Form::label('events_action_resize_size', __('Length of the the short side', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Number of pixels of the short side to resize the image to while keeping aspect ratio', 'events'))) .
                    Form::input('events_action_resize_size', '', array('type' => 'number', 'class' => 'form-control'));
                ?>
            </div>
            <div class="col-md-6">
                <?php echo
                    Form::label('events_action_resize_overwrite', __('Handle existing images', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Decide to either overwrite or keep existing resized images', 'events'))) .
                    Form::select('events_action_resize_overwrite', array(0 => __("Don't overwrite", 'events'), 1 => __('Overwrite existing', 'events')), Null, array('class' => 'form-control'));
                ?>
            </div>
        </div>
        <div class="row margin-top-1">
            <div class="col-sm-12">
                <button
                    type="submit"
                    name="events_action_resize_images_and_exit"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Resize and Exit', 'events'); ?>"
                >
                    <?php echo __('Resize and Exit', 'events'); ?>
                </button>
                <button
                    type="submit"
                    name="events_action_resize_images"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Resize', 'events'); ?>"
                >
                    <?php echo __('Resize', 'events'); ?>
                </button>
                <?php echo Html::anchor(__('Cancel', 'events'), 'index.php?id=events', array('title' => __('Cancel', 'events'), 'class' => 'btn btn-default')); ?>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>
