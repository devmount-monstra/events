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
