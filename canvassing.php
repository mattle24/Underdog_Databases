<?php
session_start();
?>

<!DOCTYPE html>
<html>


<head>
    <?php include 'includes/head.php'; ?>
    <title>Canvassing</title>

    <!-- Plotly.js -->
    <script src="plotly/plotly-latest.min.js"></script>

</head>
<body>
    <!-- Loading before php fully processes query
         From: https://stackoverflow.com/questions/5427759/showing-a-progress-wheel-while-page-loads
     -->
    <script>
        $('.loading')
        .hide()  // hide it initially
        .ajaxStart(function() {
            $(this).show();
        })
        .ajaxStop(function() {
            $(this).hide();
        });
    </script>
    <?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>
    <div id = 'page-header1' class = 'container-fluid'>
        <div class = 'spacer'></div>
        <div id = 'white-container-large'>
            <div class = 'row'>
                <h3>Cut Turf</h3>
                <p>Use the "Lasso" or rectangle selected to split voters into
                    different groups. Then, export the lists for canvassing.
                </p>
            </div>

            <!-- the plot goes after this blank div -->
            <div id="graph" class = 'loading'></div>

            <?php
            // Actual implementation: retrieve the query stored in the session["query"]
            // and then use those voters to come up with addresses
            // Join the query and the geocded_addresses table to get the lat/lon
            // pairs and also to get the number of voters at the lat/lon so I can
            // size the markers.

            // Test implementation: retrieve all coordinate pairs from
            // geocoded_addresses and then map them

            // check if the query variable is set
            if (!isset($_SESSION["query"])) {
                $msg = "Please make a list before cutting turf.";
                header("Location: make_list.php");
            }
            require_once('configs/config.php');

            $db = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASSWORD,
                DB_NAME)or die("Failed to connect to database.");

            $query = $_SESSION["query"];

            // The query selects all the voters we want.
            // I need to get the lat/lon and then the number of voters
            // at each lat/lon

            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($Voter_ID, $First_Name, $Last_Name, $Age, $Street_Number,$Street_Name, $City);

            $voterIDS = array();

            while ($stmt->fetch()) {
                array_push($voterIDS, $Voter_ID);
            }

            $voterIDSQuery = "('".implode("','", $voterIDS)."')";

            $query = "SELECT COUNT(DISTINCT(voter_id)) as number, latitude,
            longitude FROM seneca, geocoded_addresses
            WHERE voter_id IN $voterIDSQuery
            AND CONCAT(street_number, ' ', street_name) = address
            AND latitude IS NOT NULL
            GROUP BY latitude, longitude;";

            // // For testing on local server:
            // $query = "SELECT COUNT(DISTINCT(voter_id)) as number, latitude,
            // longitude FROM seneca, geocoded_addresses
            // WHERE voter_id LIKE 'A%'
            // AND CONCAT(house_number, ' ', street_name) = address
            // GROUP BY latitude, longitude;";

            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($number, $lat, $lon);

            $Numbers = array();
            $Latitudes = array();
            $Longitudes = array();

            // get results and put them in the arrays
            while ($stmt->fetch()) {
                array_push($Numbers, $number);
                array_push($Latitudes, $lat);
                array_push($Longitudes, $lon);
            }

            $mapboxToken = MAPBOX_TOKEN;

            ?>
            <script>
                // get mean of array
                function getAverage(element) {
                    var total = 0;
                    for(var i = 0; i < element.length; i++) {
                        total += element[i];
                    }
                    var avg = total / element.length;
                    return avg;
                }

                var numbers = <?php echo json_encode($Numbers); ?>; // number of voters per coordinate pair
                var dotSize = [];
                // make the dot size the minimum of the sqrt of the number of voters
                // per coord pair and 20 so dots don't get too big
                numbers.forEach(function(ele) {
                 dotSize.push(Math.min(Math.sqrt(ele)+5, 25));
                });
		// console.log(size);

                // TODO: fix size. The calculation doesn't seem to be working,
                // and I never added it the the voterList object.

                var latitude = <?php echo json_encode($Latitudes); ?>;
                var longitude = <?php echo json_encode($Longitudes); ?>;

                var nVoters = latitude.length;
                var voter_keys = [];
                var groups = []; // init groups to all be 0 (akin to null)

                for (i = 0; i < nVoters; i++) {
                    voter_keys.push(i);
                    groups.push(0);
                };

                var voterList = {
                    lat: latitude,
                    lon: longitude,
                    voterId: voter_keys,
                    size: dotSize,
                    group: groups,
                };

                var colors = []
                for (i = 0; i < nVoters; i++) colors.push("#000000"); // init dots as black

                // TODO: add more colors
                var color_groups = ['#ff0000', '#009900', '#00ffff', '#cc33ff', '#ffff66','#ff6600', '#6600cc'];

                var group = 1; // init group at 1

                var data = [{
                  type:'scattermapbox',
                  lat: voterList.lat,
                  lon: voterList.lon,
                  mode: 'markers',
                  marker: {
                    size: 10,
                    color: colors,
                    opacity: 1,
                  },
                }]

                var layout = {
                  autosize: true,
                  hovermode:'closest',
                  dragmode: 'pan',
                  mapbox: {
                    bearing:0,
                    center: {
                      lat: getAverage(voterList.lat),
                      lon: getAverage(voterList.lon),
                    },
                    pitch:0,
                    zoom:7
                  },
                }

                var mapboxToken = "<?php echo $mapboxToken ?>";
                Plotly.setPlotConfig({
                    mapboxAccessToken:  mapboxToken,
                });

                // var graphDiv = document.getElementById('graph');
                (function() {
                    var d3 = Plotly.d3;

                    var WIDTH_IN_PERCENT_OF_PARENT = 100,
                        HEIGHT_IN_PERCENT_OF_PARENT = 90;

                    var gd3 = d3.select('#graph')
                        .append('div')
                        .style({
                            width: WIDTH_IN_PERCENT_OF_PARENT + '%',
                            'margin-left': (100 - WIDTH_IN_PERCENT_OF_PARENT) / 2 + '%',

                            height: HEIGHT_IN_PERCENT_OF_PARENT + 'vh',
                            'margin-top': (100 - HEIGHT_IN_PERCENT_OF_PARENT) / 2 + 'vh'
                        });

                    var gd = gd3.node();

                    Plotly.plot(gd, data, layout);

                    window.onresize = function() {
                        Plotly.Plots.resize(gd);
                    };

                    // When we make a selection, find the points that we selected.
                    // Change these markers' colors, and add them to the appropriate
                    // group.
                    gd.on('plotly_selected', function(eventData) {
                        var lat = [];
                        var lon = [];

                        var color_index = group % color_groups.length; // choose a color from the pre-defined 9

                        eventData.points.forEach(function(pt) {
                            lat.push(pt.lat);
                            lon.push(pt.lon);
                            colors[pt.pointNumber] = color_groups[color_index];
                            voterList.group[pt.pointNumber] = group; // set group of selected dots
                        });

                        group ++ ; // increment group

                        Plotly.restyle(gd, 'marker.color', [colors]);
                    });

                    // TODO: While selecting, do not fade the not-selected dots
                    gd.on('plotly_selecting', function(eventData) {
                        var opacities = [];
                        for(var i = 0; i < nVoters; i++) opacities.push(1);

                        eventData.points.forEach(function(pt) {
                            opacities[pt.pointNumber] = 1;
                        });
                        Plotly.restyle(gd, 'marker.opacity', [opacities], [0]);
                    });
                    console.log('If you can read this, please help me! Email underdogDatabases@gmail.com');

                })();
            </script>
        </div>
        <div class = 'spacer'></div>
    <footer>
    </footer>
</body>
</html>
