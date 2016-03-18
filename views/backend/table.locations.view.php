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
            <?php if (sizeof($locations_list) > 0) {
                foreach ($locations_list as $location) { ?>
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
                                <?php if(!$is_trash) { ?>
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
                                <?php } else { ?>
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
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="4">
                        <?php echo __('No location available.', 'events'); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
