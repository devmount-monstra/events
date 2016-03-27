<!-- custom plugin script -->
<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>


<!-- i18n PHP output for JS -->
<?php echo
    Form::hidden('output_add', __('Add', 'events')) .
    Form::hidden('output_editevent', __('Edit event', 'events')) .
    Form::hidden('output_addevent', __('Add event', 'events')) .
    Form::hidden('output_editcategory', __('Edit category', 'events')) .
    Form::hidden('output_addcategory', __('Add category', 'events')) .
    Form::hidden('output_editlocation', __('Edit location', 'events')) .
    Form::hidden('output_addlocation', __('Add location', 'events')) .
    Form::hidden('output_update', __('Update', 'events'));
?>

<!-- content -->
<div class='events-admin'>

    <div class="vertical-align margin-bottom-1">
        <div class="text-left row-phone">
            <?php echo Html::heading(__('Events', 'events'), 2); ?>
        </div>
        <div class="text-right row-phone">
            <a href="#" class="btn btn-primary new-event" title="<?php echo __('New Event', 'events'); ?>">
                <span class="hidden-sm hidden-xs"><?php echo __('New Event', 'events'); ?></span>
                <span class="visible-sm visible-xs">+ <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
            </a>
            <a href="#" class="btn btn-primary new-category" title="<?php echo __('New Category', 'events'); ?>">
                <span class="hidden-sm hidden-xs"><?php echo __('New Category', 'events'); ?></span>
                <span class="visible-sm visible-xs">+ <span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
            </a>
            <a href="#" class="btn btn-primary new-location" title="<?php echo __('New Location', 'events'); ?>">
                <span class="hidden-sm hidden-xs"><?php echo __('New Location', 'events'); ?></span>
                <span class="visible-sm visible-xs">+ <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
            </a>
            <a href="#" class="btn btn-default readme-plugin" title="<?php echo __('Documentation', 'events'); ?>" data-toggle="modal" data-target="#modal-documentation" readme-plugin="events">
                <span class="hidden-sm hidden-xs"><?php echo __('Documentation', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></span>
            </a>
        </div>
    </div>

    <!-- Main tab navigation -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#events" data-toggle="tab">
                <span class="hidden-sm hidden-xs"><?php echo __('Events', 'events') . ' (' . sizeof($events_active) . ')'; ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
            </a>
        </li>
        <li>
            <a href="#categories" data-toggle="tab">
                <span class="hidden-sm hidden-xs"><?php echo __('Categories', 'events') . ' (' . sizeof($categories_active) . ')'; ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
            </a>
        </li>
        <li>
            <a href="#locations" data-toggle="tab">
                <span class="hidden-sm hidden-xs"><?php echo __('Locations', 'events') . ' (' . sizeof($locations_active) . ')'; ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
            </a>
        </li>
        <li>
            <a href="#configuration" data-toggle="tab">
                <span class="hidden-sm hidden-xs"><?php echo __('Configuration', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></span>
            </a>
        </li>
        <li>
            <a href="#trash" data-toggle="tab">
                <span class="hidden-sm hidden-xs"><?php echo __('Trash', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>
            </a>
        </li>
    </ul>

    <!-- Main tab content -->
    <div class="tab-content">

        <!-- Tab: events -->
        <div class="tab-pane active" id="events">

            <!-- Secondary pill navigation -->
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#upcoming-events" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Upcoming', 'events') . ' (' . sizeof($events_upcoming) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></span>
                    </a>
                </li>
                <li>
                    <a href="#past-events" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Past', 'events') . ' (' . sizeof($events_past) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></span>
                    </a>
                </li>
                <li>
                    <a href="#draft-events" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Draft', 'events') . ' (' . sizeof($events_draft) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span></span>
                    </a>
                </li>
            </ul>

            <!-- Secondary tab content -->
            <div class="tab-content">

                <!-- Tab: upcoming events -->
                <div class="tab-pane active" id="upcoming-events">
                    <?php echo
                        View::factory('events/views/backend/table.events')
                            ->assign('events', $events_upcoming)
                            ->assign('categories', $categories)
                            ->assign('imagepath', $imagepath)
                            ->display();
                    ?>
                </div>

                <!-- Tab: past events -->
                <div class="tab-pane" id="past-events">
                    <?php echo
                        View::factory('events/views/backend/table.events')
                            ->assign('events', $events_past)
                            ->assign('categories', $categories)
                            ->assign('imagepath', $imagepath)
                            ->display();
                    ?>
                </div>

                <!-- Tab: draft events -->
                <div class="tab-pane" id="draft-events">
                    <?php echo
                        View::factory('events/views/backend/table.events')
                            ->assign('events', $events_draft)
                            ->assign('categories', $categories)
                            ->assign('imagepath', $imagepath)
                            ->display();
                    ?>
                </div>
            </div>
        </div>

        <!-- Tab: categories -->
        <div class="tab-pane" id="categories">
            <?php echo
                View::factory('events/views/backend/table.categories')
                    ->assign('categories_list', $categories_active)
                    ->assign('categories', $categories)
                    ->display();
            ?>
        </div>

        <!-- Tab: locations -->
        <div class="tab-pane" id="locations">
            <?php echo
                View::factory('events/views/backend/table.locations')
                    ->assign('locations_list', $locations_active)
                    ->assign('locations', $locations)
                    ->display();
            ?>
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
                        Form::label('events_image_directory', __('Image directory for events', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Existing directory for event images, that will be displayed in the select list of the events add/edit form', 'events'))) .
                        Form::select('events_image_directory', $directories, Option::get('events_image_directory'), array('class' => 'form-control'));
                    ?>
                </div>
                <div class="col-md-6">
                    <!-- config archive description placeholder -->
                    <?php echo
                        Form::label('events_placeholder_archive', __('Placeholder for archive description field', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Custom placeholder text for "Archive description" textarea in the events add/edit form', 'events'))) .
                        Form::input('events_placeholder_archive', Option::get('events_placeholder_archive'), array('class' => 'form-control'));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button
                        type="submit"
                        name="events_options"
                        class="btn btn-primary"
                        value="1"
                        title="<?php echo __('Save', 'events'); ?>"
                    >
                        <?php echo __('Save', 'events'); ?>
                    </button>
                </div>
            </div>
            <?php echo Form::close(); ?>
        </div>

        <!-- Tab: trash -->
        <div class="tab-pane" id="trash">

            <!-- Secondary pill navigation -->
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#trash-events" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Events', 'events') . ' (' . sizeof($events_deleted) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                    </a>
                </li>
                <li>
                    <a href="#trash-categories" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Categories', 'events') . ' (' . sizeof($categories_deleted) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
                    </a>
                </li>
                <li>
                    <a href="#trash-locations" data-toggle="tab">
                        <span class="hidden-sm hidden-xs"><?php echo __('Locations', 'events') . ' (' . sizeof($locations_deleted) . ')'; ?></span>
                        <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
                    </a>
                </li>
            </ul>

            <!-- Secondary tab content -->
            <div class="tab-content">

                <!-- Tab: events -->
                <div class="tab-pane active" id="trash-events">
                    <?php echo
                        View::factory('events/views/backend/table.events')
                            ->assign('events', $events_deleted)
                            ->assign('categories', $categories)
                            ->assign('imagepath', $imagepath)
                            ->assign('is_trash', true)
                            ->display();
                    ?>
                </div>

                <!-- Tab: trash categories -->
                <div class="tab-pane" id="trash-categories">
                    <?php echo
                        View::factory('events/views/backend/table.categories')
                            ->assign('categories_list', $categories_deleted)
                            ->assign('categories', $categories)
                            ->assign('is_trash', true)
                            ->display();
                    ?>
                </div>

                <!-- Tab: trash locations -->
                <div class="tab-pane" id="trash-locations">
                    <?php echo
                        View::factory('events/views/backend/table.locations')
                            ->assign('locations_list', $locations_deleted)
                            ->assign('locations', $locations)
                            ->assign('is_trash', true)
                            ->display();
                    ?>
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

<!-- modal: category add/edit form -->
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
                            Form::label('category_title', __('Title', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Category header, should be unique', 'events'))) .
                            Form::input('category_title', Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('category-color', __('Color', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Category specific color, sets color for each assigned event, that has no color set', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon code" id="category-color-addon">#</span>
                            <?php echo Form::input('category_color', '', array('class' => 'form-control clear', 'id' => 'category-color', 'aria-describedby' => 'category-color-addon')); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('category_hidden_in_archive', __('Past events of this category', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Specifies, if events of this category will be displayed in archive view or not', 'events'))) .
                            Form::select('category_hidden_in_archive', array(0 => __('Show in archive', 'events'), 1 => __('Hide in archive', 'events')), Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    <?php echo __('Cancel', 'events'); ?>
                </button>
                <button
                    type="submit"
                    name="add_category"
                    id="add-edit-submit-category"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Add', 'events'); ?>"
                >
                    <?php echo __('Add', 'events'); ?>
                </button>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>

<!-- modal: location add/edit form -->
<div id="modal-location" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss="modal">&times;</div>
                <h4 class="modal-title"><?php echo __('New location', 'filesmanager'); ?></h4>
            </div>
            <?php echo
                Form::open(Null, array('role' => 'form')) .
                Form::hidden('csrf', Security::token());
            ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('location_title', __('Title', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Location header, should be unique', 'events'))) .
                            Form::input('location_title', Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('location_website', __('Website URL', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Complete URL for this specific location', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon" id="location-website-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></span>
                            <?php echo Form::input('location_website', Null, array('class' => 'form-control clear', 'aria-describedby' => 'location-website-addon')); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo Form::label('location_address', __('Address', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Location address, used to generate OpenStreetMap link', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon" id="location-address-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
                            <?php echo Form::input('location_address', Null, array('class' => 'form-control clear', 'aria-describedby' => 'location-address-addon')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    <?php echo __('Cancel', 'events'); ?>
                </button>
                <button
                    type="submit"
                    name="add_location"
                    id="add-edit-submit-location"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Add', 'events'); ?>"
                >
                    <?php echo __('Add', 'events'); ?>
                </button>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>

<!-- modal: event add/edit form -->
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
                            Form::label('event_status', __('Status', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event status: "published" or "draft"', 'events'))) .
                            Form::select('event_status', array('published' => __('Published', 'events'), 'draft' => __('Draft', 'events')), Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-9">
                        <?php echo
                            Form::label('event_title', __('Title', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event header, should be unique', 'events'))) .
                            Form::input('event_title', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo
                            Form::label('event_date', __('Date', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event date as free text to specify single dates or ranges', 'events'))) .
                            Form::input('event_date', Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <?php echo
                                Form::label('event_timestamp_date', __('Start', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Local datetime timestamp in format "2015-04-18", "19:00". Used to specify event date beginning and put events in chronological order', 'events'))) .
                                Form::input('event_timestamp_date', '', array('class' => 'form-control clear', 'placeholder' => 'yyyy-mm-dd'));
                            ?>
                            <span class="input-group-btn" style="width:0px;"></span>
                            <?php echo
                                Form::label('event_timestamp_time', Html::nbsp()) .
                                Form::input('event_timestamp_time', '', array('class' => 'form-control clear', 'placeholder' => 'hh:mm'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <?php echo
                            Form::label('event_openat', __('Open at', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event admittance time as free text', 'events'))) .
                            Form::input('event_openat', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo
                            Form::label('event_time', __('Time', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event starting time as free text', 'events'))) .
                            Form::input('event_time', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <?php echo Form::label('event_timestamp_end_date', __('End', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Local datetime timestamp in format "2015-04-18", "19:00". Used to specify event end and calculate date ranges', 'events'))) . Html::nbsp(); ?>
                            <span id="copy-timestamp" class="glyphicon glyphicon-chevron-down helper" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="<?php echo __('Copy field data from »Start«', 'events'); ?>"></span>
                            <?php echo Form::input('event_timestamp_end_date', '', array('class' => 'form-control clear', 'placeholder' => 'yyyy-mm-dd')); ?>
                            <span class="input-group-btn" style="width:0px;"></span>
                            <?php echo
                                Form::label('event_timestamp_end_time', Html::nbsp()) .
                                Form::input('event_timestamp_end_time', '', array('class' => 'form-control clear', 'placeholder' => 'hh:mm'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('event-color', __('Color', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event specific color in hex format (without #), inherits color of category if not set', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon code" id="event-color-addon">#</span>
                            <?php echo Form::input('event_color', Null, array('class' => 'form-control clear', 'id' => 'event-color', 'aria-describedby' => 'event-color-addon')); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_category', __('Category', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event category, used to group similar events', 'events'))) .
                            Form::select('event_category', $categories_select, Null, array('class' => 'form-control clear', 'required' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('event_location', __('Location', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event location object', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon code" id="event-location-addon">@</span>
                            <?php echo Form::select('event_location', $locations_select, Null, array('class' => 'form-control clear', 'aria-describedby' => 'event-location-addon')); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_facebook', __('Facebook URL', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to facebook event', 'events'))) .
                            Form::input('event_facebook', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Form::label('event_hashtag', __('Hashtag', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event specific hashtag in social nets', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon code" id="event-hashtag-addon">#</span>
                            <?php echo Form::input('event_hashtag', Null, array('class' => 'form-control clear', 'aria-describedby' => 'event-hashtag-addon')); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo
                            Form::label('event_short', __('Short description', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event description in one line', 'events'))) .
                            Form::input('event_short', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo
                            Form::label('event_description', __('Description', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Long event description used for single event view, line breaks are preserved', 'events'))) .
                            Form::textarea('event_description', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                            echo Form::label('event_image', __('Image file', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event title image from preconfigured image directory', 'events')));
                            if (sizeof($files)>1) {
                                echo Form::select('event_image', $files, Null, array('class' => 'form-control clear'));
                            } else {
                                echo Form::select('event_image', array(), Null, array('class' => 'form-control clear', 'disabled' => 'disabled', 'title' => __('No file available in configured image directory', 'events')));
                            }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_imagesection', __('Clip image', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Specifies how to clip rectangular image to square', 'events'))) . Html::br();
                        ?>
                        <select class="image-picker" name="event_imagesection">
                            <option data-img-src="/plugins/events/images/image-section-t.png" value="t"><?php echo __('Clip to top', 'events'); ?></option>
                            <option data-img-src="/plugins/events/images/image-section-m.png" value="m"><?php echo __('Clip to middle', 'events'); ?></option>
                            <option data-img-src="/plugins/events/images/image-section-b.png" value="b"><?php echo __('Clip to bottom', 'events'); ?></option>
                            <option data-img-src="/plugins/events/images/image-section-l.png" value="l"><?php echo __('Clip to left', 'events'); ?></option>
                            <option data-img-src="/plugins/events/images/image-section-c.png" value="c"><?php echo __('Clip to center', 'events'); ?></option>
                            <option data-img-src="/plugins/events/images/image-section-r.png" value="r"><?php echo __('Clip to right', 'events'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php //echo Html::heading('Archive', 4); ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_gallery', __('Gallery URL', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to gallery (e.g. facebook gallery)', 'events'))) .
                            Form::input('event_gallery', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_audio', __('Audio file', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to audio file', 'events'))) .
                            Form::input('event_audio', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo
                            Form::label('event_archive', __('Archive description', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event description used for archive view, line breaks are preserved', 'events'))) .
                            Form::textarea('event_archive', Null, array('class' => 'form-control clear', 'placeholder' => Option::get('events_placeholder_archive')));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <?php echo
                            Form::label('event_number_staff', __('Staff members', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Number of all staff members, used for statistics', 'events'))) .
                            Form::input('event_number_staff', Null, array('class' => 'form-control clear', 'type' => 'number'));
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo
                            Form::label('event_number_visitors', __('Visitors', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Number of all visitors, used for statistics', 'events'))) .
                            Form::input('event_number_visitors', Null, array('class' => 'form-control clear', 'type' => 'number'));
                        ?>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    <?php echo __('Cancel', 'events'); ?>
                </button>
                <button
                    type="submit"
                    name="add_event"
                    id="add-edit-submit-event"
                    class="btn btn-primary"
                    value="1"
                    title="<?php echo __('Add', 'events'); ?>"
                >
                    <?php echo __('Add', 'events'); ?>
                </button>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>
