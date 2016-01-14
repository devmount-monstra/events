<?php //var_dump($directories); ?>

<!-- custom plugin script -->
<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>

<!-- notifications -->
<?php
    Notification::get('success') AND Alert::success(Notification::get('success'));
    Notification::get('warning') AND Alert::warning(Notification::get('warning'));
    Notification::get('error')   AND Alert::error(Notification::get('error'));
?>

<!-- PHP output for JS -->
<?php echo
    Form::hidden('output_add', __('Add', 'events')) .
    Form::hidden('output_editevent', __('Edit event', 'events')) .
    Form::hidden('output_editcategory', __('Edit category', 'events')) .
    Form::hidden('output_update', __('Update', 'events'));
?>


<!-- content -->
<div class='events-admin'>
    
    <div class="vertical-align margin-bottom-1">
        <div class="text-left row-phone">
            <?php echo Html::heading(__('Events', 'events'), 2); ?>
        </div>
        <div class="text-right row-phone">
            <?php echo
                Html::anchor(__('New Event', 'events'), '#events', array('class' => 'btn btn-phone btn-primary', 'data-toggle' => 'tab', 'title' => __('New Event', 'events'))) . Html::nbsp() .
                Html::anchor(__('New Category', 'events'), '#categories', array('class' => 'btn btn-phone btn-primary', 'data-toggle' => 'tab', 'title' => __('New Category', 'events'))) . Html::nbsp() .
                Html::anchor(__('Documentation', 'events'), '#', array('class' => 'btn btn-phone btn-default readme_plugin', 'data-toggle' => 'modal', 'data-target' => '#readme', 'readme_plugin' => 'events'));
            ?>
        </div>
    </div>
    
    <div class="tabbable mobile-nav-tabs"></div>
        
        <!-- Tab navigation -->
        <ul class="nav nav-tabs">
            <li class="active"><?php echo Html::anchor(__('Events', 'events'), '#events', array('data-toggle' => 'tab')); ?></li>
            <li><?php echo Html::anchor(__('Categories', 'events'), '#categories', array('data-toggle' => 'tab')); ?></li>
            <li><?php echo Html::anchor(__('Configuration', 'events'), '#configuration', array('data-toggle' => 'tab')); ?></li>
        </ul>
        
        <!-- Tab content -->
        <div class="tab-content">
            
            <!-- Tab: events -->
            <div class="tab-pane active" id="events">
                <div class="row">
                    <div class="col-md-6">   
                        <?php echo Html::heading(__('Upcoming events', 'events'), 2); ?>
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
                                        <div class="pull-left event-image">
                                            <?php echo Html::image($event['image']); ?>
                                        </div>
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']]; ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </a>
                                <?php }
                            } else {
                                echo __('No upcoming events', 'events');
                            }
                            ?>
                        </div>
                        <?php echo Html::heading(__('Past events', 'events'), 2); ?>
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
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']]; ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </a>
                                <?php }
                            } else {
                                echo __('No past events', 'events');
                            }
                            ?>
                        </div>
                        <?php echo Html::heading(__('Draft events', 'events'), 2); ?>
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
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
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
                        <?php echo
                            Html::heading(__('Add event', 'events'), 2, array('id' => 'add-edit-title-event')) .
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
                            <div class="col-sm-6">
                                <?php echo
                                    Form::label('event_imagesection', __('Clip image', 'events')) . Html::br();
                                ?>
                                <label class="event_imagesection" title="Clip to top"><?php echo Form::radio('event_imagesection', 't'); ?><span class="image-section section-portrait section-top"></span></label>
                                <label class="event_imagesection" title="Clip to bottom"><?php echo Form::radio('event_imagesection', 'b'); ?><span class="image-section section-portrait section-bottom"></span></label>
                                <label class="event_imagesection" title="Clip to center"><?php echo Form::radio('event_imagesection', 'c', True); ?><span class="image-section section-portrait section-center"></span></label>
                                <label class="event_imagesection" title="Clip to left"><?php echo Form::radio('event_imagesection', 'l'); ?><span class="image-section section-landscape section-left"></span></label>
                                <label class="event_imagesection" title="Clip to right"><?php echo Form::radio('event_imagesection', 'r'); ?><span class="image-section section-landscape section-right"></span></label>
                            </div>
                            <div class="col-sm-6">
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
                        <?php echo Html::heading(__('Categories', 'events'), 2); ?>
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
                                        <?php echo Html::heading($category['title'], 4, array('class' => 'list-group-item-heading')); ?>
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
                        <?php echo
                            Html::heading(__('Add category', 'events'), 2, array('id' => 'add-edit-title-category')) .
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
                    Form::hidden('csrf', Security::token()) .
                    HTML::br();
                ?>
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