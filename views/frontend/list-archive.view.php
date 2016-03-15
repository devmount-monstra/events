<div class="events-plugin">
	<?php if (sizeof($eventlist) > 0) {
        foreach ($eventlist as $year => $yearevents) {
            echo Html::heading($year, 3, array('class' => 'heading-archive'));
            foreach ($yearevents as $event) { ?>
                <div class="row">
                    <div class="col-md-12 event archive">
                        <div class="event-content" style="background: #<?php echo $event['color'] ? $event['color'] : $categories[$event['category']]['color']; ?>;">
                            <div
                                class="event-image section-<?php echo $event['imagesection']; ?>"
                                style="background-image: url(<?php echo $event['image']; ?>"
                            >
                            </div>
                            <div class="description">
                                <?php echo Html::heading($categories[$event['category']]['title'], 3); ?>
                                <div class="text">
                                    <?php echo $event['title'] == '' ? $event['short'] : '<span class="title">' . $event['title'] . '</span>'; ?><br />
                                    <?php 
                                        if ($event['timestamp_end']) {
                                            if (date('j.n.Y', strtotime($event['timestamp'])) == date('j.n.Y', strtotime($event['timestamp_end']))) {
                                                // same day
                                                echo date('j.n.y', strtotime($event['timestamp']));
                                            } else {
                                                // day range
                                                echo date('j.n.', strtotime($event['timestamp'])) . '–' . date('j.n.y', strtotime($event['timestamp_end']));
                                            }
                                        } else {
                                            // only start date
                                            echo date('j.n.y', strtotime($event['timestamp']));
                                        }
                                    ?>
                                    <?php echo $event['location'] ? ' | @' . $locations[$event['location']]['title'] : ''; ?>
                                    <div style="white-space: pre-wrap;"><?php echo $event['archive']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		  <?php }
        }
	} else { ?>
		<div class="event">
			<?php echo __('No events in this list view.', 'events'); ?>
		</div>
	<?php } ?>
</div>