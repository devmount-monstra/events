<?php //var_dump($directories); ?>

<!-- custom plugin script -->
<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>

<!-- notifications -->
<?php
    Notification::get('success') AND Alert::success(Notification::get('success'));
    Notification::get('warning') AND Alert::warning(Notification::get('warning'));
    Notification::get('error')   AND Alert::error(Notification::get('error'));
?>

<!-- i18n PHP output for JS -->
<?php echo
    Form::hidden('output_add', __('Add', 'events')) .
    Form::hidden('output_editevent', __('Edit event', 'events')) .
    Form::hidden('output_addevent', __('Add event', 'events')) .
    Form::hidden('output_editcategory', __('Edit category', 'events')) .
    Form::hidden('output_addcategory', __('Add category', 'events')) .
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
                Html::anchor(__('New Event', 'events'), '#events', array('class' => 'btn btn-phone btn-primary new-event', 'data-toggle' => 'tab', 'title' => __('New Event', 'events'))) . Html::nbsp() .
                Html::anchor(__('New Category', 'events'), '#categories', array('class' => 'btn btn-phone btn-primary new-category', 'data-toggle' => 'tab', 'title' => __('New Category', 'events'))) . Html::nbsp() .
                Html::anchor(__('Documentation', 'events'), '#', array('class' => 'btn btn-phone btn-default readme-plugin', 'data-toggle' => 'modal', 'data-target' => '#modal-documentation', 'readme-plugin' => 'events'));
            ?>
        </div>
    </div>

    <div class="tabbable mobile-nav-tabs"></div>

        <!-- Tab navigation -->
        <ul class="nav nav-tabs">
            <li class="active"><?php echo Html::anchor(__('Events', 'events'), '#events', array('data-toggle' => 'tab')); ?></li>
            <li><?php echo Html::anchor(__('Categories', 'events'), '#categories', array('data-toggle' => 'tab')); ?></li>
            <li><?php echo Html::anchor(__('Configuration', 'events'), '#configuration', array('data-toggle' => 'tab')); ?></li>
            <li><?php echo Html::anchor(__('Trash', 'events'), '#trash', array('data-toggle' => 'tab')); ?></li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content">

            <!-- Tab: events -->
            <div class="tab-pane no-table active" id="events">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo Html::heading(__('Upcoming events', 'events'), 2); ?>
                        <div class="list-group">
                            <?php if (sizeof($upcomingevents) > 0) {
                                foreach ($upcomingevents as $event) { ?>
                                    <div class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
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
                                        <?php if ($event['image']) echo Html::anchor('', $event['image'], array('rel' => $event['image'], 'class' => 'chocolat pull-left event-image section-' . $event['imagesection'], 'data-toggle' => 'lightbox', 'style' => 'background-image: url(' . $event['image'] . ')'));?>
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']] . ($event['date'] ? ' — ' . $event['date'] . ' ' . $event['time'] : ''); ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </div>
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
                                    <div class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
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
                                        <?php if ($event['image']) echo Html::anchor('', $event['image'], array('rel' => $event['image'], 'class' => 'chocolat pull-left event-image section-' . $event['imagesection'], 'data-toggle' => 'lightbox', 'style' => 'background-image: url(' . $event['image'] . ')'));?>
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']] . ($event['date'] ? ' — ' . $event['date'] . ' ' . $event['time'] : ''); ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </div>
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
                                    <div class="list-group-item" style="border-left: 5px solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>">
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
                                        <?php if ($event['image']) echo Html::anchor('', $event['image'], array('rel' => $event['image'], 'class' => 'chocolat pull-left event-image section-' . $event['imagesection'], 'data-toggle' => 'lightbox', 'style' => 'background-image: url(' . $event['image'] . ')'));?>
                                        <?php echo Html::heading($event['title'], 4, array('class' => 'list-group-item-heading')); ?>
                                        <p class="list-group-item-text"><?php echo $categories_title[$event['category']] . ($event['date'] ? ' — ' . $event['date'] . ' ' . $event['time'] : ''); ?></p>
                                        <p class="list-group-item-text"><?php echo $event['short']; ?></p>
                                    </div>
                                <?php }
                            } else {
                                echo __('No draft events', 'events');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>

            <!-- Tab: categories -->
            <div class="tab-pane" id="categories">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Title</th>
                                <th>Assigned</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (sizeof($categories) > 0) {
                                foreach ($categories as $category) { ?>
                                    <tr>
                                        <td>
                                            <div class="color-text-box" style="border-left: 1.4em solid #<?php echo $category['color']; ?>; padding-left: 10px;">
                                                #<?php echo $category['color']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo Html::heading($category['title'], 4); ?>
                                        </td>
                                        <td>
                                            <!-- number of events for each category -->
                                            <?php echo $categories_count[$category['id']] . ' ' . __('events', 'events'); ?>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                <button class="btn btn-primary edit-category" value="<?php echo $category['id'] ?>" title="<?php echo __('Edit', 'events'); ?>"><?php echo __('Edit', 'events'); ?></button>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('delete_category', $category['id']);
                                                ?>
                                                    <button class="btn btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete category &quot;:title&quot;', 'events', array(':title' => $category['title'])); ?>')" title="<?php echo __('Delete', 'events'); ?>"><?php echo __('Delete', 'events'); ?></button>
                                                <?php echo Form::close(); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="4">
                                        <?php echo __('No category available.', 'events'); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: configuration -->
            <div class="tab-pane no-table" id="configuration">
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

            <!-- Tab: trash -->
            <div class="tab-pane" id="trash">
                <div class="row no-gutter">
                    <div class="col-md-6 col-left">
                        <!-- deleted events -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3"><?php echo Html::heading(__('Deleted events', 'events'), 2); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (sizeof($deletedevents) > 0) {
                                    foreach ($deletedevents as $event) { ?>
                                        <tr>
                                            <td>
                                                <div class="color-text-box" style="border-left: 1.4em solid #<?php echo $event['color'] ? $event['color'] : $categories_color[$event['category']] ; ?>; padding-left: 10px;">
                                                    <?php echo Html::heading($event['title'], 4); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $categories_title[$event['category']] . ($event['date'] ? ' — ' . $event['date'] . ' ' . $event['time'] : ''); ?>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('restore_trash_event', $event['id']);
                                                    ?>
                                                        <button class="btn btn-sm btn-primary" value="<?php echo $event['id'] ?>" title="<?php echo __('Restore', 'events'); ?>">
                                                            <span class="glyphicon glyphicon-repeat"></span>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('delete_trash_event', $event['id']);
                                                    ?>
                                                        <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete event &quot;:title&quot; permanently (can not be undone)', 'events', array(':title' => $event['title'])); ?>')" title="<?php echo __('Delete permanently', 'events'); ?>">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="3">
                                            <?php echo __('No deleted events', 'events'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 col-right">
                        <!-- deleted categories -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3"><?php echo Html::heading(__('Deleted categories', 'events'), 2); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <th>Assigned</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (sizeof($deletedcategories) > 0) {
                                    foreach ($deletedcategories as $category) { ?>
                                        <tr>
                                            <td>
                                                <div class="color-text-box" style="border-left: 1.4em solid #<?php echo $category['color']; ?>; padding-left: 10px;">
                                                    <?php echo Html::heading($category['title'], 4); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $categories_count[$category['id']] . ' ' . __('events', 'events'); ?>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('restore_trash_category', $category['id']);
                                                    ?>
                                                        <button class="btn btn-sm btn-primary" value="<?php echo $category['id'] ?>" title="<?php echo __('Restore', 'events'); ?>">
                                                            <span class="glyphicon glyphicon-repeat"></span>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('delete_trash_category', $category['id']);
                                                    ?>
                                                        <button class="btn btn-sm btn-danger" value="1" onclick="return confirmDelete('<?php echo __('Delete category &quot;:title&quot; permanently (can not be undone)', 'events', array(':title' => $category['title'])); ?>')" title="<?php echo __('Delete permanently', 'events'); ?>">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="3">
                                            <?php echo __('No deleted categories', 'events'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- modal: README markup -->
<div id="modal-documentation" class="modal fade" tabindex="-1">
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

<!-- modal: category -->
<div id="modal-category" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss="modal">&times;</div>
                <h4 class="modal-title"><?php echo __('New category', 'filesmanager'); ?></h4>
            </div>
            <?php echo
                Form::open(Null, array('role' => 'form')) .
                Form::hidden('csrf', Security::token());
            ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('category_title', __('Title', 'events')) .
                            Form::input('category_title', Null, array('class' => 'form-control clear', 'id' => 'focus-categories', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('category-color', __('Color', 'events')); ?>
                        <div class="input-group">
                            <span class="input-group-addon" id="category-color-addon">#</span>
                            <?php echo Form::input('category_color', '', array('class' => 'form-control clear', 'id' => 'category-color', 'aria-describedby' => 'category-color-addon')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', 'events'); ?></button>
                <button type="submit" name="add_category" id="add-edit-submit-category" class="btn btn-primary" value="1" title="<?php echo __('Add', 'events'); ?>">
                    <?php echo __('Add', 'events'); ?>
                </button>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>

<!-- modal: event -->
<div id="modal-event" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss="modal">&times;</div>
                <h4 class="modal-title"><?php echo __('New event', 'filesmanager'); ?></h4>
            </div>
            <?php echo
                Form::open(Null, array('role' => 'form')) .
                Form::hidden('csrf', Security::token());
            ?>
            <div class="modal-body">
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
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_category', __('Category', 'events')) .
                            Form::select('event_category', $categories_title, Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('event-color', __('Color', 'events')); ?>
                        <div class="input-group">
                            <span class="input-group-addon" id="event-color-addon">#</span>
                            <?php echo Form::input('event_color', '', array('class' => 'form-control clear', 'id' => 'event-color', 'aria-describedby' => 'event-color-addon')); ?>
                        </div>
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
                                echo Form::select('event_image', $files, Null, array('class' => 'form-control clear'));
                            } else {
                                echo Form::select('event_image', array(), Null, array('class' => 'form-control clear', 'disabled' => 'disabled', 'title' => __('No file available in configured image directory', 'events')));
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
                        <label class="image-section-label" title="Clip to top"><?php echo Form::radio('event_imagesection', 't'); ?><span class="image-section section-portrait section-top"></span></label>
                        <label class="image-section-label" title="Clip to middle"><?php echo Form::radio('event_imagesection', 'm', True); ?><span class="image-section section-portrait section-middle"></span></label>
                        <label class="image-section-label" title="Clip to bottom"><?php echo Form::radio('event_imagesection', 'b'); ?><span class="image-section section-portrait section-bottom"></span></label>
                        <label class="image-section-label" title="Clip to left"><?php echo Form::radio('event_imagesection', 'l'); ?><span class="image-section section-landscape section-left"></span></label>
                        <label class="image-section-label" title="Clip to center"><?php echo Form::radio('event_imagesection', 'c'); ?><span class="image-section section-landscape section-center"></span></label>
                        <label class="image-section-label" title="Clip to right"><?php echo Form::radio('event_imagesection', 'r'); ?><span class="image-section section-landscape section-right"></span></label>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel', 'events'); ?></button>
                <button type="submit" name="add_event" id="add-edit-submit-event" class="btn btn-primary" value="1" title="<?php echo __('Add', 'events'); ?>">
                    <?php echo __('Add', 'events'); ?>
                </button>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>
