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
            <?php if (sizeof($events) > 0) {
                foreach ($events as $event) { ?>
                    <tr>
                        <td>
                            <?php if ($event['image'])
                                echo Html::anchor(
                                    '',
                                    $imagepath . $event['image'],
                                    array(
                                        'rel' => $imagepath . $event['image'],
                                        'class' => 'chocolat pull-left event-image section-' . $event['imagesection'],
                                        'data-toggle' => 'lightbox',
                                        'style' => 'background-image: url(' . $imagepath . $event['image'] . ');'
                                    )
                                );
                            ?>
                        </td>
                        <td>
                            <?php echo Html::heading($event['title'], 4); ?>
                        </td>
                        <td class="visible-lg hidden-xs">
                            <?php
                                echo date('d.m.Y H:i', strtotime($event['timestamp']));
                                if ($event['timestamp_end']) {
                                    if (date('d.m.Y', strtotime($event['timestamp'])) == date('d.m.Y', strtotime($event['timestamp_end']))) {
                                        echo ' – ' . date('H:i', strtotime($event['timestamp_end']));
                                    } else {
                                        echo ' – ' . date('d.m.Y H:i', strtotime($event['timestamp_end']));
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
                                <?php if(!$is_trash) { ?>
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
                                    <div class="btn-group">
                                        <button
                                            class="btn btn-primary edit-event"
                                            value="<?php echo $event['id'] ?>"
                                            title="<?php echo __('Edit', 'events'); ?>"
                                        >
                                            <?php echo __('Edit', 'events'); ?>
                                        </button>
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="new-event" title="<?php echo __('New Event', 'events'); ?>"><?php echo __('Add', 'events'); ?></a></li>
                                            <!--<li><a href="#" class="clone-event" title="<?php echo __('Clone Event', 'events'); ?>"><?php echo __('Clone', 'events'); ?></a></li>-->
                                            <li class="divider"></li>
                                            <li class="dropdown-header"><?php echo __('Status', 'events'); ?></li>
                                            <li>
                                                <a href="index.php?id=events&action=update_status&event_id=<?php echo $event['id']; ?>&status=published&token=<?php echo Security::token(); ?>">
                                                    <?php echo __('Published', 'events'); ?>
                                                    <?php if ($event['status'] == 'published') { ?><i class="glyphicon glyphicon-ok"></i><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?id=events&action=update_status&event_id=<?php echo $event['id']; ?>&status=draft&token=<?php echo Security::token(); ?>">
                                                    <?php echo __('Draft', 'events'); ?>
                                                    <?php if ($event['status'] == 'draft') { ?><i class="glyphicon glyphicon-ok"></i><?php } ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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
                                <?php } else { ?>
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
                                <?php } ?>
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
