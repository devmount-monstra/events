<?php //Debug::dump(); ?>
<?php echo Html::heading(__('Statistics', 'events'), 2); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo Html::heading(__('Events', 'events'), 3); ?>
        <?php echo Html::heading(__('Number of archived events per year', 'events'), 4); ?>
        <canvas id="year-events" height="400" width="600"></canvas>
        <hr />
        <?php echo Html::heading(__('Number of visiors and staff', 'events'), 4); ?>
        <p>Coming soon...</p>
    </div>
    <div class="col-md-6">
        <hr class="visible-sm visible-xs"/>
        <?php echo Html::heading(__('Categories', 'events'), 3); ?>
        <?php echo Html::heading(__('Total number of assigned events (drafts included)', 'events'), 4); ?>
        <canvas id="category-events" height="400" width="600"></canvas>
    </div>
</div>
<div class="row margin-top-2">
    <div class="col-md-6">
        <?php echo Html::anchor(__('Back', 'events'), 'index.php?id=events', array('title' => __('Back', 'events'), 'class' => 'btn btn-default')); ?>
    </div>
</div>
<script>
    // global configuration
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleBeginAtZero = true;
    // single configuration
    var categoryEvents = [
        <?php foreach ($categories_data as $c) { ?>
            {
                value: <?php echo $c['count']; ?>,
                color: <?php echo $c['color']; ?>,
                highlight: <?php echo $c['highlight']; ?>,
                label: <?php echo $c['title']; ?>,
            },
        <?php } ?>
    ]
    var yearEvents = {
        labels: [<?php echo implode(',', array_keys($years_data)); ?>],
        datasets: [
            // {
            //     label: "Total",
            //     fillColor: "rgba(252,0,86,0.2)",
            //     strokeColor: "rgba(252,0,86,1)",
            //     pointColor: "rgba(252,0,86,1)",
            //     pointStrokeColor: "#fff",
            //     pointHighlightFill: "#fff",
            //     pointHighlightStroke: "rgba(252,0,86,1)",
            //     data: [<?php echo implode(',', $years_data); ?>]
            // }
            {
                label: "Total",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?php echo implode(',', $years_data); ?>]
            },
            <?php foreach ($categories_years_data as $cid => $c) { ?>
                {
                    label: "<?php echo $categories[$cid]['title']; ?>",
                    fillColor: "rgba(<?php echo implode(',',EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,0.2)",
                    strokeColor: "rgba(<?php echo implode(',',EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointColor: "rgba(<?php echo implode(',',EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(<?php echo implode(',',EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    data: [<?php echo implode(',', $c); ?>]
                },
            <?php } ?>
        ]
    };
	window.onload = function(){
        // category events
		var ctx = $("#category-events").get(0).getContext("2d");
		new Chart(ctx).Pie(categoryEvents);
        // year events
		var ctx = $("#year-events").get(0).getContext("2d");
		new Chart(ctx).Line(yearEvents);
	}
</script>