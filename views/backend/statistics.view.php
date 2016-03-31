<?php //Debug::dump($coordinates); ?>
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
        <hr />
        <?php echo Html::heading(__('Locations', 'events'), 3); ?>
        <?php echo Html::heading(__('Map of all existing locations', 'events'), 4); ?>
        <div id="mapdiv" style="height: 400px; width: 100%;"></div>
    </div>
</div>
<div class="row margin-top-2">
    <div class="col-md-6">
        <?php echo Html::anchor(__('Back', 'events'), 'index.php?id=events', array('title' => __('Back', 'events'), 'class' => 'btn btn-default')); ?>
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
    ]
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
                data: [<?php echo implode(',', $years_data); ?>]
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
	window.onload = function(){
        // category events
		var ctx = $("#category-events").get(0).getContext("2d");
		new Chart(ctx).Pie(categoryEvents);
        // year events
		var ctx = $("#year-events").get(0).getContext("2d");
		new Chart(ctx).Line(yearEvents);
	}
</script>

<!-- OSM -->
<script src="http://www.openlayers.org/api/OpenLayers.js"></script> 
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
                console.log(data[i][0]);
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