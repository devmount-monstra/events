<?php //Debug::dump($categories_years_visitors); ?>
<div class='events-admin'>
    <div class="vertical-align margin-bottom-1">
        <div class="text-left row-phone">
            <?php echo Html::heading(__('Statistics', 'events'), 2); ?>
        </div>
        <div class="text-right row-phone">
            <div class="btn-group text-left">
                <button
                    class="btn btn-primary new-event"
                    title="<?php echo __('New', 'events'); ?>"
                >
                    <span class="hidden-sm hidden-xs"><?php echo __('Add', 'events'); ?></span>
                    <span class="glyphicon glyphicon-plus visible-sm visible-xs" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" class="new-event" title="<?php echo __('New Event', 'events'); ?>"><?php echo __('Add event', 'events'); ?></a></li>
                    <li><a href="#" class="new-category" title="<?php echo __('New Category', 'events'); ?>"><?php echo __('Add Category', 'events'); ?></a></li>
                    <li><a href="#" class="new-location" title="<?php echo __('New Location', 'events'); ?>"><?php echo __('Add Location', 'events'); ?></a></li>
                </ul>
            </div>
            <a href="index.php?id=events" class="btn btn-default">
                <span class="hidden-sm hidden-xs"><?php echo __('List', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></span>
            </a>
            <a href="index.php?id=events&action=configuration" class="btn btn-default">
                <span class="hidden-sm hidden-xs"><?php echo __('Configuration', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></span>
            </a>
            <a href="index.php?id=events&action=stats" class="btn btn-default">
                <span class="hidden-sm hidden-xs"><?php echo __('Stats', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></span>
            </a>
            <a href="#" class="btn btn-default readme-plugin" title="<?php echo __('Documentation', 'events'); ?>" data-toggle="modal" data-target="#modal-documentation" readme-plugin="events">
                <span class="hidden-sm hidden-xs"><?php echo __('Documentation', 'events'); ?></span>
                <span class="visible-sm visible-xs"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php echo Html::heading(__('Events', 'events'), 3); ?>
            <?php echo Html::heading(__('Number of archived events per year', 'events'), 4); ?>
            <canvas id="year-events" height="400" width="500"></canvas>
            <?php echo Html::heading(__('Number of participants per year', 'events'), 4, array('class' => 'margin-top-2')); ?>
            <canvas id="year-visitors" height="400" width="500"></canvas>
            <p><?php echo __('Note that events without information about the number of participants cannot be included in years evaluation.', 'events'); ?></p>
            <?php foreach ($participants as $category => $events) { ?>
                <?php echo Html::heading(__('Participants', 'events') . ' in ' . $categories[$category]['title'], 4, array('class' => 'margin-top-2')); ?>
                <canvas id="event-visitors-<?php echo $category; ?>" height="400" width="500"></canvas>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <hr class="visible-sm visible-xs"/>
            <?php echo Html::heading(__('Categories', 'events'), 3); ?>
            <?php echo Html::heading(__('Total number of assigned events (drafts included)', 'events'), 4); ?>
            <canvas id="category-events" height="400" width="500"></canvas>
            <hr />
            <?php echo Html::heading(__('Locations', 'events'), 3); ?>
            <?php echo Html::heading(__('Map of all existing locations', 'events'), 4); ?>
            <div id="mapdiv" style="height: 400px; width: 100%;"></div>
            <?php echo Html::heading(__('Total number of assigned events (drafts included)', 'events'), 4, array('class' => 'margin-top-2')); ?>
            <canvas id="location-events" height="400" width="500"></canvas>
        </div>
    </div>
    <div class="row margin-top-2">
        <div class="col-md-6">
            <?php echo Html::anchor(__('Back', 'events'), 'index.php?id=events', array('title' => __('Back', 'events'), 'class' => 'btn btn-default')); ?>
        </div>
    </div>
</div>
<!-- Chart.js -->
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
    ];
    var locationEvents = {
        labels: [<?php echo implode(',', array_column($locations_data, 'title')); ?>],
        datasets: [
            {
                label: "Total",
                fillColor: "rgba(160,160,160,0.5)",
                strokeColor: "rgba(160,160,160,0.8)",
                highlightFill: "rgba(160,160,160,0.75)",
                highlightStroke: "rgba(160,160,160,1)",
                data: [<?php echo implode(',', array_column($locations_data, 'count')); ?>]
            }
        ]
    };
    var yearEvents = {
        labels: [<?php echo implode(',', array_keys($years_data)); ?>],
        datasets: [
            {
                label: "Total",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?php echo implode(',', array_column($years_data, 'number_events')); ?>]
            },
            <?php foreach ($categories_years_data as $cid => $c) { ?>
                {
                    label: "<?php echo $categories[$cid]['title']; ?>",
                    fillColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,0.2)",
                    strokeColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    data: [<?php echo implode(',', $c); ?>]
                },
            <?php } ?>
        ]
    };
    var yearVisitors = {
        labels: [<?php echo implode(',', array_keys($years_data)); ?>],
        datasets: [
            {
                label: "Total",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?php echo implode(',', array_column($years_data, 'number_visitors')); ?>]
            },
            <?php foreach ($categories_years_visitors as $cid => $c) { ?>
                {
                    label: "<?php echo $categories[$cid]['title']; ?>",
                    fillColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,0.2)",
                    strokeColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$cid]['color'])); ?>,1)",
                    data: [<?php echo implode(',', $c); ?>]
                },
            <?php } ?>
        ]
    };
    <?php foreach ($participants as $category => $events) { ?>
        var eventVisitors<?php echo $category; ?> = {
            labels: [<?php echo implode(',', array_map(function ($e) { return '"' . $e['title'] . '"'; }, $events)); ?>],
            datasets: [
                {
                    label: "Visitors",
                    fillColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$category]['color'])); ?>,0.2)",
                    strokeColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$category]['color'])); ?>,1)",
                    pointColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$category]['color'])); ?>,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb($categories[$category]['color'])); ?>,1)",
                    data: [<?php echo implode(',', array_column($events, 'visitors')); ?>]
                },
                {
                    label: "Staff",
                    fillColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb(EventsAdmin::adjustBrightness($categories[$category]['color'], -50))); ?>,0.2)",
                    strokeColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb(EventsAdmin::adjustBrightness($categories[$category]['color'], -50))); ?>,1)",
                    pointColor: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb(EventsAdmin::adjustBrightness($categories[$category]['color'], -50))); ?>,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(<?php echo implode(',', EventsAdmin::hex2rgb(EventsAdmin::adjustBrightness($categories[$category]['color'], -50))); ?>,1)",
                    data: [<?php echo implode(',', array_column($events, 'staff')); ?>]
                },
            ]
        };
    <?php } ?>
	window.onload = function(){
        // category events
		var ctx = $("#category-events").get(0).getContext("2d");
		new Chart(ctx).Doughnut(categoryEvents);
        // location events
		var ctx = $("#location-events").get(0).getContext("2d");
		new Chart(ctx).Bar(locationEvents);
        // year events
		var ctx = $("#year-events").get(0).getContext("2d");
		new Chart(ctx).Line(yearEvents);
        // year visitors
		var ctx = $("#year-visitors").get(0).getContext("2d");
		new Chart(ctx).Line(yearVisitors);
        // event visitors and staff
        <?php foreach ($participants as $category => $events) { ?>
            var ctx = $("#event-visitors-<?php echo $category; ?>").get(0).getContext("2d");
            new Chart(ctx).Line(eventVisitors<?php echo $category; ?>);
        <?php } ?>
	}
</script>

<!-- OSM -->
<script src="/plugins/events/lib/OpenLayers/OpenLayers.js"></script>
<script>
    $(document).ready(function(){
        map = new OpenLayers.Map("mapdiv");
        map.addLayer(new OpenLayers.Layer.OSM());
        epsg4326 = new OpenLayers.Projection("EPSG:4326"); // WGS 1984 projection
        projectTo = map.getProjectionObject(); // The map projection (Spherical Mercator)
        var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
        // get coordinates and loop through them
        var data = [[<?php echo implode('],[', $coordinates); ?>]];
        for (var i=0; i<data.length; i++) {
            if (!$.isEmptyObject(data)) {
                var lon = data[i][0];
                var lat = data[i][1];
                var feature = new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(lon, lat).transform(epsg4326, projectTo),
                    {description: "marker number " + i} ,
                    {externalGraphic: '/plugins/events/images/map-marker.png', graphicHeight: 25, graphicWidth: 25, graphicXOffset:-12, graphicYOffset:-25 }
                );
                vectorLayer.addFeatures(feature);
            }
        }
        var lonLat = new OpenLayers.LonLat(<?php echo $coordinates_average['lon']; ?>, <?php echo $coordinates_average['lat']; ?>).transform(epsg4326, projectTo);
        var zoom = 10;
        map.setCenter (lonLat, zoom);
        map.addLayer(vectorLayer);
    });
</script>

<!-- modal: README markup -->
<div id="modal-documentation" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss="modal">&times;</div>
                <h4 class="modal-title" id="myModalLabel">README.md</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
