<div class="events-plugin">
    <div class="table-wrapper">
        <table>
            <tbody>
            <?php if (sizeof($eventlist) > 0) {
                foreach ($eventlist as $event) { ?>
                    <tr>
                        <td><?php echo $categories[$event['category']]['title'] ?></td>
                        <td><?php echo $event['title'] == '' ? $event['short'] : '<span class="title">' . $event['title'] . '</span>' ?></td>
                        <td><?php echo $event['date'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="3"><?php echo __('No events in this list view.', 'events'); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>