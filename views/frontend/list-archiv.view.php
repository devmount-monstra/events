<div class="events-plugin">
	<?php if (sizeof($eventlist) > 0) {
		foreach ($eventlist as $event) { ?>
			<div class="row">
				<div class="col-md-12 event">
					<div class="event-content" style="background: #<?php echo $event['color'] ? $event['color'] : $categories['color'][$event['category']]; ?>;">
                        <div
                            class="event-image section-<?php echo $event['imagesection']; ?>"
                            style="background-image: url(<?php echo $event['image']; ?>"
                        >
                        </div>
                        <div class="description">
                            <?php echo Html::heading($categories['title'][$event['category']], 3); ?>
                            <div class="text">
                                <?php echo $event['title'] == '' ? $event['short'] : '»' . $event['title'] . '«'; ?><br />
                                <?php echo $event['date'] . ($event['time'] ? ' – ' . $event['time'] : ''); ?><br />
                                <?php echo $event['location'] == '' ? '' : '@' . $event['location']; ?>
                                <div style="white-space: pre-wrap;"><?php echo $event['archiv']; ?></div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		<?php }
	} else { ?>
		<div class="event">
			<?php echo __('No events in this list view.', 'events'); ?>
		</div>
	<?php } ?>
</div>