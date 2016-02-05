<div class="events-plugin">
	<?php if (sizeof($eventlist) > 0) {
		foreach ($eventlist as $event) { ?>
			<div class="row">
				<div class="col-md-12 event">
					<div
						class="event-image section-<?php echo $event['imagesection']; ?>"
						style="background-image: url(<?php echo $event['image']; ?>"
					>
					</div>
					<div class="event-content" style="background: #<?php echo $event['color'] ? $event['color'] : $categories['color'][$event['category']]; ?>;">
						<?php echo Html::heading($categories['title'][$event['category']], 3); ?>
						<div class="description">
							<?php echo $event['title'] == '' ? $event['short'] : '»' . $event['title'] . '«' ?>
						</div>
						<div class="date">
							<?php echo $event['date'] ?>
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