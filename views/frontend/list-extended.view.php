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
                                <?php echo $event['title'] == '' ? $event['short'] : '»' . $event['title'] . '«' ?><br />
                                <?php echo $event['location'] == '' ? '' : '@' . $event['location'] ?>
                            </div>
                            <div class="date">
                                <?php echo $event['date'] ?>
                            </div>
                            <div class="event-content-extend">
                                <div>
                                    <?php echo
                                        $event['date'] .
                                        ($event['time'] ? ' – ' . $event['time'] : '') .
                                        ($event['openat'] ? ', ' . __('Lounge ab ', 'events') . $event['openat'] : '')
                                    ?>
                                </div>
                                <div><?php echo ($event['address'] ? $event['address'] : '') ?></div>
                                <div><?php echo ($event['short'] ? $event['short'] : '') ?></div>
                                <div>
                                    <?php if ($event['facebook']) {
                                        echo
                                            Html::arrow('right') . ' ' . Html::anchor(__('Facebook', 'events'), 'https://www.facebook.com/events/' . $event['facebook'], array('class' => 'facebook', 'target' => '_blank'));
                                    } ?>
                                    <?php if ($event['address']) {
                                        echo
                                            Html::nbsp() . Html::nbsp() . Html::nbsp() .
                                            Html::arrow('right') . ' ' . Html::anchor(__('Map', 'events'), 'http://nominatim.openstreetmap.org/search?q=' . $event['address'], array('class' => 'map', 'target' => '_blank'));
                                    } ?>
                                </div>
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