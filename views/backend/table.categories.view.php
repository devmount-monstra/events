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
            <?php if (sizeof($categories_list) > 0) {
                foreach ($categories_list as $category) { ?>
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
                                <?php if(!$is_trash) { ?>
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
                                <?php } else { ?>
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
                                <?php } ?>
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
