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
            <?php echo
                Html::anchor(__('New Event', 'events'), '#', array('class' => 'btn btn-phone btn-primary new-event', 'title' => __('New Event', 'events'))) . Html::nbsp() .
                Html::anchor(__('New Category', 'events'), '#', array('class' => 'btn btn-phone btn-primary new-category', 'title' => __('New Category', 'events'))) . Html::nbsp() .
                Html::anchor(__('New Location', 'events'), '#', array('class' => 'btn btn-phone btn-primary new-location', 'title' => __('New Location', 'events'))) . Html::nbsp() .
                Html::anchor(__('Documentation', 'events'), '#', array('class' => 'btn btn-phone btn-default readme-plugin', 'data-toggle' => 'modal', 'data-target' => '#modal-documentation', 'readme-plugin' => 'events'));
            ?>
        </div>
    </div>

    <!-- Main tab navigation -->
    <ul class="nav nav-tabs">
        <li class="active"><?php echo Html::anchor(__('Events', 'events') . ' (' . sizeof($events_active) . ')', '#events', array('data-toggle' => 'tab')); ?></li>
        <li><?php echo Html::anchor(__('Categories', 'events') . ' (' . sizeof($categories_active) . ')', '#categories', array('data-toggle' => 'tab')); ?></li>
        <li><?php echo Html::anchor(__('Locations', 'events') . ' (' . sizeof($locations_active) . ')', '#locations', array('data-toggle' => 'tab')); ?></li>
        <li><?php echo Html::anchor(__('Configuration', 'events'), '#configuration', array('data-toggle' => 'tab')); ?></li>
        <li><?php echo Html::anchor(__('Trash', 'events'), '#trash', array('data-toggle' => 'tab')); ?></li>
    </ul>

    <!-- Main tab content -->
    <div class="tab-content">

        <!-- Tab: events -->
        <div class="tab-pane active" id="events">

            <!-- Secondary pill navigation -->
            <ul class="nav nav-pills">
                <li class="active"><?php echo Html::anchor(__('Upcoming', 'events') . ' (' . sizeof($events_upcoming) . ')', '#upcoming-events', array('data-toggle' => 'tab')); ?></li>
                <li><?php echo Html::anchor(__('Past', 'events') . ' (' . sizeof($events_past) . ')', '#past-events', array('data-toggle' => 'tab')); ?></li>
                <li><?php echo Html::anchor(__('Draft', 'events') . ' (' . sizeof($events_draft) . ')', '#draft-events', array('data-toggle' => 'tab')); ?></li>
            </ul>

            <!-- Secondary tab content -->
            <div class="tab-content">

                <!-- Tab: upcoming events -->
                <div class="tab-pane active" id="upcoming-events">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo __('Image', 'events'); ?></th>
                                    <th><?php echo __('Title', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Description', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Color', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Category', 'events'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (sizeof($events_upcoming) > 0) {
                                    foreach ($events_upcoming as $event) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($event['image'])
                                                    echo Html::anchor(
                                                        '',
                                                        $event['image'],
                                                        array(
                                                            'rel' => $event['image'],
                                                            'class' => 'chocolat pull-left event-image section-' . $event['imagesection'],
                                                            'data-toggle' => 'lightbox',
                                                            'style' => 'background-image: url(' . $event['image'] . ')'
                                                        )
                                                    );
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo Html::heading($event['title'], 4); ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php
                                                    echo date('d.m.Y H:i', $event['timestamp']);
                                                    if ($event['timestamp_end']) {
                                                        if (date('d.m.Y', $event['timestamp']) == date('d.m.Y', $event['timestamp_end'])) {
                                                            echo ' – ' . date('H:i', $event['timestamp_end']);
                                                        } else {
                                                            echo ' – ' . date('d.m.Y H:i', $event['timestamp_end']);
                                                        }
                                                    }
                                                    echo Html::br() . $event['short'];
                                                ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <div
                                                    class="color-text-box"
                                                    title="#<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>"
                                                    style="border-left: 1.4em solid #<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>; padding-left: 10px;"
                                                >
                                                    <?php if(!$event['color']){ ?>
                                                        <span
                                                            class="glyphicon glyphicon-arrow-left"
                                                            aria-hidden="true"
                                                            title="<?php echo __('Inherited from category', 'events'); ?>"
                                                        ></span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php echo $categories[$event['category']]['title']; ?>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <?php if($event['facebook']) { ?>
                                                        <a
                                                            class="btn btn-info"
                                                            href="<?php echo $event['facebook'] ?>"
                                                            title="<?php echo __('Facebook URL', 'events'); ?>"
                                                            target="_blank"
                                                        >
                                                            FB
                                                        </a>
                                                    <?php } ?>
                                                    <button
                                                        class="btn btn-primary edit-event"
                                                        value="<?php echo $event['id'] ?>"
                                                        title="<?php echo __('Edit', 'events'); ?>"
                                                    >
                                                        <?php echo __('Edit', 'events'); ?>
                                                    </button>
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('delete_event', $event['id']);
                                                    ?>
                                                        <button
                                                            class="btn btn-danger"
                                                            value="1"
                                                            onclick="return confirmDelete('<?php echo __('Delete event »:title«', 'events', array(':title' => $event['title'])); ?>')"
                                                            title="<?php echo __('Delete', 'events'); ?>"
                                                        >
                                                            <?php echo __('Delete', 'events'); ?>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6">
                                            <?php echo __('No upcoming events', 'events'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: past events -->
                <div class="tab-pane" id="past-events">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo __('Image', 'events'); ?></th>
                                    <th><?php echo __('Title', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Description', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Color', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Category', 'events'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (sizeof($events_past) > 0) {
                                    foreach ($events_past as $event) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($event['image']) echo Html::anchor(
                                                    '',
                                                    $event['image'],
                                                    array(
                                                        'rel' => $event['image'],
                                                        'class' => 'chocolat pull-left event-image section-' . $event['imagesection'],
                                                        'data-toggle' => 'lightbox',
                                                        'style' => 'background-image: url(' . $event['image'] . ')'
                                                    ));
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo Html::heading($event['title'], 4); ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php
                                                    echo date('d.m.Y H:i', $event['timestamp']);
                                                    if ($event['timestamp_end']) {
                                                        if (date('d.m.Y', $event['timestamp']) == date('d.m.Y', $event['timestamp_end'])) {
                                                            echo ' – ' . date('H:i', $event['timestamp_end']);
                                                        } else {
                                                            echo ' – ' . date('d.m.Y H:i', $event['timestamp_end']);
                                                        }
                                                    }
                                                    echo Html::br() . $event['short'];
                                                ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <div
                                                    class="color-text-box"
                                                    title="#<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>"
                                                    style="border-left: 1.4em solid #<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>; padding-left: 10px;"
                                                >
                                                    <?php if(!$event['color']){ ?>
                                                        <span
                                                            class="glyphicon glyphicon-arrow-left"
                                                            aria-hidden="true"
                                                            title="<?php echo __('Inherited from category', 'events'); ?>"
                                                        ></span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php echo $categories[$event['category']]['title']; ?>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <?php if($event['facebook']) { ?>
                                                        <a
                                                            class="btn btn-info"
                                                            href="<?php echo $event['facebook'] ?>"
                                                            title="<?php echo __('Facebook URL', 'events'); ?>"
                                                            target="_blank"
                                                        >
                                                            FB
                                                        </a>
                                                    <?php } ?>
                                                    <button
                                                        class="btn btn-primary edit-event"
                                                        value="<?php echo $event['id'] ?>"
                                                        title="<?php echo __('Edit', 'events'); ?>"
                                                    >
                                                        <?php echo __('Edit', 'events'); ?>
                                                    </button>
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('delete_event', $event['id']);
                                                    ?>
                                                        <button
                                                            class="btn btn-danger"
                                                            value="1"
                                                            onclick="return confirmDelete('<?php echo __('Delete event »:title«', 'events', array(':title' => $event['title'])); ?>')"
                                                            title="<?php echo __('Delete', 'events'); ?>"
                                                        >
                                                            <?php echo __('Delete', 'events'); ?>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6">

                                            <?php echo __('No past events', 'events'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: draft events -->
                <div class="tab-pane" id="draft-events">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo __('Image', 'events'); ?></th>
                                    <th><?php echo __('Title', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Description', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Color', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Category', 'events'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (sizeof($events_draft) > 0) {
                                    foreach ($events_draft as $event) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($event['image']) echo Html::anchor(
                                                    '',
                                                    $event['image'],
                                                    array(
                                                        'rel' => $event['image'],
                                                        'class' => 'chocolat pull-left event-image section-' . $event['imagesection'],
                                                        'data-toggle' => 'lightbox',
                                                        'style' => 'background-image: url(' . $event['image'] . ')'
                                                    ));
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo Html::heading($event['title'], 4); ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php
                                                    echo date('d.m.Y H:i', $event['timestamp']);
                                                    if ($event['timestamp_end']) {
                                                        if (date('d.m.Y', $event['timestamp']) == date('d.m.Y', $event['timestamp_end'])) {
                                                            echo ' – ' . date('H:i', $event['timestamp_end']);
                                                        } else {
                                                            echo ' – ' . date('d.m.Y H:i', $event['timestamp_end']);
                                                        }
                                                    }
                                                    echo Html::br() . $event['short'];
                                                ?>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <div
                                                    class="color-text-box"
                                                    title="#<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>"
                                                    style="border-left: 1.4em solid #<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>; padding-left: 10px;"
                                                >
                                                    <?php if(!$event['color']){ ?>
                                                        <span
                                                            class="glyphicon glyphicon-arrow-left"
                                                            aria-hidden="true"
                                                            title="<?php echo __('Inherited from category', 'events'); ?>"
                                                        ></span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td class="visible-lg hidden-xs">
                                                <?php echo $categories[$event['category']]['title']; ?>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <?php if($event['facebook']) { ?>
                                                        <a
                                                            class="btn btn-info"
                                                            href="<?php echo $event['facebook'] ?>"
                                                            title="<?php echo __('Facebook URL', 'events'); ?>"
                                                            target="_blank"
                                                        >
                                                            FB
                                                        </a>
                                                    <?php } ?>
                                                    <button
                                                        class="btn btn-primary edit-event"
                                                        value="<?php echo $event['id'] ?>"
                                                        title="<?php echo __('Edit', 'events'); ?>"
                                                    >
                                                        <?php echo __('Edit', 'events'); ?>
                                                    </button>
                                                    <?php echo
                                                        Form::open() .
                                                        Form::hidden('csrf', Security::token()) .
                                                        Form::hidden('delete_event', $event['id']);
                                                    ?>
                                                        <button
                                                            class="btn btn-danger"
                                                            value="1"
                                                            onclick="return confirmDelete('<?php echo __('Delete event »:title«', 'events', array(':title' => $event['title'])); ?>')"
                                                            title="<?php echo __('Delete', 'events'); ?>"
                                                        >
                                                            <?php echo __('Delete', 'events'); ?>
                                                        </button>
                                                    <?php echo Form::close(); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6">
                                            <?php echo __('No draft events', 'events'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: categories -->
        <div class="tab-pane" id="categories">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __('Color', 'events'); ?></th>
                            <th><?php echo __('Title', 'events'); ?></th>
                            <th><?php echo __('Assigned', 'events'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (sizeof($categories_active) > 0) {
                            foreach ($categories_active as $category) { ?>
                                <tr>
                                    <td>
                                        <div
                                            class="color-text-box"
                                            style="border-left: 1.4em solid #<?php echo $category['color']; ?>; padding-left: 10px;"
                                        >
                                            #<?php echo $category['color']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo Html::heading($category['title'], 4); ?>
                                    </td>
                                    <td>
                                        <!-- number of events for each category -->
                                        <?php echo $categories[$category['id']]['count'] . ' ' . __('events', 'events'); ?>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                            <button
                                                class="btn btn-primary edit-category"
                                                value="<?php echo $category['id'] ?>"
                                                title="<?php echo __('Edit', 'events'); ?>"
                                            >
                                                <?php echo __('Edit', 'events'); ?>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_category', $category['id']);
                                            ?>
                                                <button
                                                    class="btn btn-danger"
                                                    value="1"
                                                    onclick="return confirmDelete('<?php echo __('Delete category »:title«', 'events', array(':title' => $category['title'])); ?>')"
                                                    title="<?php echo __('Delete', 'events'); ?>"
                                                >
                                                    <?php echo __('Delete', 'events'); ?>
                                                </button>
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

        <!-- Tab: locations -->
        <div class="tab-pane" id="locations">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __('Title', 'events'); ?></th>
                            <th><?php echo __('Address', 'events'); ?></th>
                            <th><?php echo __('Assigned', 'events'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (sizeof($locations_active) > 0) {
                            foreach ($locations_active as $location) { ?>
                                <tr>
                                    <td>
                                        <?php echo Html::heading($location['title'], 4); ?>
                                    </td>
                                    <td>
                                        <?php echo $location['address']; ?>
                                    </td>
                                    <td>
                                        <!-- number of events for each location -->
                                        <?php echo $locations[$location['id']]['count'] . ' ' . __('events', 'events'); ?>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                            <?php if($location['website']) { ?>
                                                <a
                                                    class="btn btn-info"
                                                    href="<?php echo $location['website'] ?>"
                                                    title="<?php echo __('Website URL', 'events'); ?>"
                                                    target="_blank"
                                                >
                                                    <span class="glyphicon glyphicon-link" aria-hidden="true"></span>
                                                </a>
                                            <?php } ?>
                                            <?php if($location['address']) { ?>
                                                <a
                                                    class="btn btn-info"
                                                    href="http://nominatim.openstreetmap.org/search?q=<?php echo $location['address'] ?>"
                                                    title="<?php echo __('Show map', 'events'); ?>"
                                                    target="_blank"
                                                >
                                                    <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                                </a>
                                            <?php } ?>
                                            <button
                                                class="btn btn-primary edit-location"
                                                value="<?php echo $location['id'] ?>"
                                                title="<?php echo __('Edit', 'events'); ?>"
                                            >
                                                <?php echo __('Edit', 'events'); ?>
                                            </button>
                                            <?php echo
                                                Form::open() .
                                                Form::hidden('csrf', Security::token()) .
                                                Form::hidden('delete_location', $location['id']);
                                            ?>
                                                <button
                                                    class="btn btn-danger"
                                                    value="1"
                                                    onclick="return confirmDelete('<?php echo __('Delete location »:title«', 'events', array(':title' => $location['title'])); ?>')"
                                                    title="<?php echo __('Delete', 'events'); ?>"
                                                >
                                                    <?php echo __('Delete', 'events'); ?>
                                                </button>
                                            <?php echo Form::close(); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="3">
                                    <?php echo __('No location available.', 'events'); ?>
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
                        Form::label('events_image_directory', __('Image directory for events', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Existing directory for event images, that will be displayed in the select list of the events add/edit form', 'events'))) .
                        Form::select('events_image_directory', $directories, Option::get('events_image_directory'), array('class' => 'form-control'));
                    ?>
                </div>
                <div class="col-md-6">
                    <!-- config archiv description placeholder -->
                    <?php echo
                        Form::label('events_placeholder_archiv', __('Placeholder for archiv description field', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Custom placeholder text for "Archiv description" textarea in the events add/edit form', 'events'))) .
                        Form::input('events_placeholder_archiv', Option::get('events_placeholder_archiv'), array('class' => 'form-control'));
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
                <li class="active"><?php echo Html::anchor(__('Events', 'events') . ' (' . sizeof($events_deleted) . ')', '#trash-events', array('data-toggle' => 'tab')); ?></li>
                <li><?php echo Html::anchor(__('Categories', 'events') . ' (' . sizeof($categories_deleted) . ')', '#trash-categories', array('data-toggle' => 'tab')); ?></li>
                <li><?php echo Html::anchor(__('Locations', 'events') . ' (' . sizeof($locations_deleted) . ')', '#trash-locations', array('data-toggle' => 'tab')); ?></li>
            </ul>

            <!-- Secondary tab content -->
            <div class="tab-content">

                <!-- Tab: events -->
                <div class="tab-pane active" id="trash-events">

                    <!-- deleted events -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo __('Image', 'events'); ?></th>
                                    <th><?php echo __('Title', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Description', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Color', 'events'); ?></th>
                                    <th class="visible-lg hidden-xs"><?php echo __('Category', 'events'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (sizeof($events_deleted) > 0) {
                                foreach ($events_deleted as $event) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($event['image']) echo Html::anchor(
                                                '',
                                                $event['image'],
                                                array(
                                                    'rel' => $event['image'],
                                                    'class' => 'chocolat pull-left event-image section-' . $event['imagesection'],
                                                    'data-toggle' => 'lightbox',
                                                    'style' => 'background-image: url(' . $event['image'] . ')'
                                                ));
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo Html::heading($event['title'], 4); ?>
                                        </td>
                                        <td class="visible-lg hidden-xs">
                                            <?php
                                                echo date('d.m.Y H:i', $event['timestamp']);
                                                if ($event['timestamp_end']) {
                                                    if (date('d.m.Y', $event['timestamp']) == date('d.m.Y', $event['timestamp_end'])) {
                                                        echo ' – ' . date('H:i', $event['timestamp_end']);
                                                    } else {
                                                        echo ' – ' . date('d.m.Y H:i', $event['timestamp_end']);
                                                    }
                                                }
                                                echo Html::br() . $event['short'];
                                            ?>
                                        </td>
                                        <td class="visible-lg hidden-xs">
                                            <div
                                                class="color-text-box"
                                                title="#<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>"
                                                style="border-left: 1.4em solid #<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>; padding-left: 10px;"
                                            >
                                                <?php if(!$event['color']){ ?>
                                                    <span
                                                        class="glyphicon glyphicon-arrow-left"
                                                        aria-hidden="true"
                                                        title="<?php echo __('Inherited from category', 'events'); ?>"
                                                    ></span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="visible-lg hidden-xs">
                                            <?php echo $categories[$event['category']]['title']; ?>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                <?php if($event['facebook']) { ?>
                                                    <a
                                                        class="btn btn-info"
                                                        href="<?php echo $event['facebook'] ?>"
                                                        title="<?php echo __('Facebook URL', 'events'); ?>"
                                                        target="_blank"
                                                    >
                                                        FB
                                                    </a>
                                                <?php } ?>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('restore_trash_event', $event['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-primary"
                                                        value="<?php echo $event['id'] ?>"
                                                        title="<?php echo __('Restore', 'events'); ?>"
                                                    >
                                                        <?php echo __('Restore', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('delete_trash_event', $event['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-danger"
                                                        value="1"
                                                        onclick="return confirmDelete('<?php echo __('Delete event »:title« permanently (can not be undone)', 'events', array(':title' => $event['title'])); ?>')"
                                                        title="<?php echo __('Delete permanently', 'events'); ?>"
                                                    >
                                                        <?php echo __('Delete', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="6">
                                        <?php echo __('No deleted events', 'events'); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: trash categories -->
                <div class="tab-pane" id="trash-categories">

                    <!-- deleted categories -->
                    <div class="table-responsive"> 
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo __('Color', 'events'); ?></th>
                                    <th><?php echo __('Title', 'events'); ?></th>
                                    <th><?php echo __('Assigned', 'events'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (sizeof($categories_deleted) > 0) {
                                foreach ($categories_deleted as $category) { ?>
                                    <tr>
                                        <td>
                                            <div
                                                class="color-text-box"
                                                style="border-left: 1.4em solid #<?php echo $category['color']; ?>; padding-left: 10px;"
                                            >
                                                #<?php echo $category['color']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo Html::heading($category['title'], 4); ?>
                                        </td>
                                        <td>
                                            <!-- number of events for each category -->
                                            <?php echo $categories[$category['id']]['count'] . ' ' . __('events', 'events'); ?>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('restore_trash_category', $category['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-primary"
                                                        value="<?php echo $category['id'] ?>"
                                                        title="<?php echo __('Restore', 'events'); ?>"
                                                    >
                                                        <?php echo __('Restore', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('delete_trash_category', $category['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-danger"
                                                        value="1"
                                                        onclick="return confirmDelete('<?php echo __('Delete category »:title« permanently (can not be undone)', 'events', array(':title' => $category['title'])); ?>')"
                                                        title="<?php echo __('Delete permanently', 'events'); ?>"
                                                    >
                                                        <?php echo __('Delete', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="4">
                                        <?php echo __('No deleted categories', 'events'); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: trash locations -->
                <div class="tab-pane" id="trash-locations">

                    <!-- deleted locations -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo __('Title', 'events'); ?></th>
                                <th><?php echo __('Address', 'events'); ?></th>
                                <th><?php echo __('Assigned', 'events'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (sizeof($locations_deleted) > 0) {
                                foreach ($locations_deleted as $location) { ?>
                                    <tr>
                                        <td>
                                            <?php echo Html::heading($location['title'], 4); ?>
                                        </td>
                                        <td>
                                            <?php echo $location['address']; ?>
                                        </td>
                                        <td>
                                            <!-- number of events for each location -->
                                            <?php echo $locations[$location['id']]['count'] . ' ' . __('events', 'events'); ?>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                <?php if($location['website']) { ?>
                                                    <a
                                                        class="btn btn-info"
                                                        href="<?php echo $location['website'] ?>"
                                                        title="<?php echo __('Website URL', 'events'); ?>"
                                                        target="_blank"
                                                    >
                                                        <span class="glyphicon glyphicon-link" aria-hidden="true"></span>
                                                    </a>
                                                <?php } ?>
                                                <?php if($location['address']) { ?>
                                                    <a
                                                        class="btn btn-info"
                                                        href="http://nominatim.openstreetmap.org/search?q=<?php echo $location['address'] ?>"
                                                        title="<?php echo __('Show map', 'events'); ?>"
                                                        target="_blank"
                                                    >
                                                        <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                                    </a>
                                                <?php } ?>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('restore_trash_location', $location['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-primary"
                                                        value="<?php echo $location['id'] ?>"
                                                        title="<?php echo __('Restore', 'events'); ?>"
                                                    >
                                                        <?php echo __('Restore', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                                <?php echo
                                                    Form::open() .
                                                    Form::hidden('csrf', Security::token()) .
                                                    Form::hidden('delete_trash_location', $location['id']);
                                                ?>
                                                    <button
                                                        class="btn btn-danger"
                                                        value="1"
                                                        onclick="return confirmDelete('<?php echo __('Delete location »:title« permanently (can not be undone)', 'events', array(':title' => $location['title'])); ?>')"
                                                        title="<?php echo __('Delete permanently', 'events'); ?>"
                                                    >
                                                        <?php echo __('Delete', 'events'); ?>
                                                    </button>
                                                <?php echo Form::close(); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="3">
                                        <?php echo __('No deleted locations', 'events'); ?>
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
                    <div class="col-sm-12">
                        <?php echo
                            Form::checkbox('category_hidden_in_archive', 1) . Html::nbsp() .
                            Form::label('category_hidden_in_archive', __('Hide in archive', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('If checked, events of this category will not be displayed in archive view', 'events')));
                        ?>
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
                        <?php echo
                            Form::label('event_timestamp', __('Start', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Local datetime timestamp in format "2015-04-18T19:00:00.000", used to specify event date beginning and put events in chronological order', 'events'))) .
                            Form::input('event_timestamp', '', array('class' => 'form-control clear', 'type' => 'datetime-local'));
                        ?>
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
                        <?php echo
                            Form::label('event_timestamp_end', __('End', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Local datetime timestamp in format "2015-04-18T19:00:00.000", used to specify event end, calculate date ranges and classify events "upcoming" or "past"', 'events'))) .
                            Form::input('event_timestamp_end', '', array('class' => 'form-control clear', 'type' => 'datetime-local'));
                        ?>
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
                    <div class="col-sm-12">
                        <?php echo
                            Form::label('event_archiv', __('Archiv description', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event description used for archiv view, line breaks are preserved', 'events'))) .
                            Form::textarea('event_archiv', Null, array('class' => 'form-control clear', 'placeholder' => Option::get('events_placeholder_archiv')));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo Form::label('event_hashtag', __('Hashtag', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Event specific hashtag in social nets', 'events'))); ?>
                        <div class="input-group">
                            <span class="input-group-addon code" id="event-hashtag-addon">#</span>
                            <?php echo Form::input('event_hashtag', Null, array('class' => 'form-control clear', 'aria-describedby' => 'event-hashtag-addon')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_facebook', __('Facebook URL', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to facebook event', 'events'))) .
                            Form::input('event_facebook', Null, array('class' => 'form-control clear'));
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
                            Form::label('event_gallery', __('Gallery URL', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to gallery (e.g. facebook gallery)', 'events'))) .
                            Form::input('event_gallery', Null, array('class' => 'form-control clear'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_imagesection', __('Clip image', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('Specifies how to clip image to square', 'events'))) . Html::br();
                        ?>
                        <label class="image-section-label" title="Clip to top"><?php echo Form::radio('event_imagesection', 't'); ?>
                            <span class="image-section section-portrait section-top"></span>
                        </label>
                        <label class="image-section-label" title="Clip to middle"><?php echo Form::radio('event_imagesection', 'm', True); ?>
                            <span class="image-section section-portrait section-middle"></span>
                        </label>
                        <label class="image-section-label" title="Clip to bottom"><?php echo Form::radio('event_imagesection', 'b'); ?>
                            <span class="image-section section-portrait section-bottom"></span>
                        </label>
                        <label class="image-section-label" title="Clip to left"><?php echo Form::radio('event_imagesection', 'l'); ?>
                            <span class="image-section section-landscape section-left"></span>
                        </label>
                        <label class="image-section-label" title="Clip to center"><?php echo Form::radio('event_imagesection', 'c'); ?>
                            <span class="image-section section-landscape section-center"></span>
                        </label>
                        <label class="image-section-label" title="Clip to right"><?php echo Form::radio('event_imagesection', 'r'); ?>
                            <span class="image-section section-landscape section-right"></span>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <?php echo
                            Form::label('event_audio', __('Audio file', 'events'), array('data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => __('URL to audio file', 'events'))) .
                            Form::input('event_audio', Null, array('class' => 'form-control clear'));
                        ?>
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
