<?php
session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>


<head>
    <?php include 'includes/head.php'; ?>
    <title>Canvassing</title>

    <!-- Plotly.js -->
    <script src="packages/plotly-latest.min.js"></script>


    <!-- Export to CSV -->
    <script src="packages/download.js"></script>
    <script src = 'scripts/json_to_csv.js' type = 'text/javascript'></script>

</head>
<body>
    <?php include 'includes/navbar_loggedin.php';	?>
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
            // Implementation: retrieve the query stored in the session["query"]
            // and then use those voters to come up with addresses
            // Join the query and the geocded_addresses table to get the lat/lon
            // pairs and also to get the number of voters at the lat/lon so I can
            // size the markers.

            // check if the query variable is set
            if (!isset($_SESSION["query"])) {
                $msg = "Please make a list before cutting turf.";
                header("Location: make_list.php?err=$msg");
            }
            elseif (!isset($_SESSION['cmp'])) {
                $msg = "Please choose a campaign before cutting turf.";
                header("Location: choose_campaign.php?msg=$msg");
            }
            $cmp = $_SESSION['cmp'];
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
            $stmt->bind_result($Voter_ID, $First_Name, $Last_Name, $Age, $Address, $City, $Party, $Gender);

            $voterIDS = array();
            $firstNames = array();
            $lastNames = array();
            $Ages = array();
            $Addresses = array();
            $Cities = array();
            $Parties = array();
            $Genders = array();

            while ($stmt->fetch()) {
                array_push($voterIDS, $Voter_ID);
                array_push($firstNames, $First_Name);
                array_push($lastNames, $Last_Name);
                array_push($Ages, $Age);
                array_push($Addresses, $Address);
                array_push($Cities, $City);
                array_push($Parties, $Party);
                array_push($Genders, $Gender);
            }

            $voterIDSQuery = "('".implode("','", $voterIDS)."')";

            // $query = "SELECT voter_id, number, latitude,longitude FROM $cmp as voters
            //     (CONCAT(street_number, ' ', street_name ) = address
            //     and UPPER(voters.City) = UPPER(ga.city)) ";
            // // I had to separate this because otherwise it would not recognize $cmp as a variable.
            // $query = $query." JOIN (SELECT COUNT(voter_id) as number, latitude as group_lat, longitude as group_lon
            //     FROM $cmp as voters2
            //     JOIN geocoded_addresses as ga2 ON
            //         (CONCAT(street_number, ' ', street_name) = address
            //         and UPPER(voters2.City) = UPPER(ga2.city))
            //     WHERE voter_id IN $voterIDSQuery GROUP BY ga2.latitude, ga2.longitude) two ON latitude = group_lat AND longitude = group_lon;";

            $query = "SELECT voter_id, number, latitude,longitude
            FROM $cmp as voters
            JOIN geocoded_addresses as ga ON
                (CONCAT(street_number, ' ', street_name) = address
                and UPPER(voters.City) = UPPER(ga.city))
            JOIN (SELECT COUNT(voter_id) as number, latitude as group_lat, longitude as group_lon
                FROM $cmp as voters2
                JOIN geocoded_addresses as ga2 ON
                    (CONCAT(street_number, ' ', street_name) = address
                    and UPPER(voters2.City) = UPPER(ga2.city))
                WHERE voter_id IN $voterIDSQuery
                GROUP BY ga2.latitude, ga2.longitude) two ON latitude = group_lat AND longitude = group_lon;";
            // echo $query;
            // // For testing on local server:
            // $query = "SELECT voter_id, number, latitude,longitude
            // FROM seneca as voters
            // JOIN geocoded_addresses as ga ON
            //     (CONCAT(street_number, ' ', street_name) = address
            //     and UPPER(voters.City) = UPPER(ga.city))
            // JOIN (SELECT COUNT(voter_id) as number, latitude as group_lat, longitude as group_lon
            //     FROM seneca as voters2
            //     JOIN geocoded_addresses as ga2 ON
            //         (CONCAT(street_number, ' ', street_name) = address
            //         and UPPER(voters2.City) = UPPER(ga2.city))
            //     WHERE voter_id LIKE 'A%' GROUP BY ga2.latitude, ga2.longitude) two ON latitude = group_lat AND longitude = group_lon= group_lat AND longitude = group_lon;";
            // echo "preparing...";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($voter_id, $number, $lat, $lon);

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

            // TODO: fix dotSize. The calculation doesn't seem to be working,

            var voterIDs = <?php echo json_encode($voterIDS); ?>;
            var firstNames = <?php echo json_encode($firstNames); ?>;
            var lastNames = <?php echo json_encode($lastNames); ?>;
            var ages = <?php echo json_encode($Ages); ?>;
            var parties = <?php echo json_encode($Parties); ?>;
            var genders = <?php echo json_encode($Genders); ?>;
            var addresses = <?php echo json_encode($Addresses); ?>;
            var cities = <?php echo json_encode($Cities); ?>;

            var latitude = <?php echo json_encode($Latitudes); ?>;
            var longitude = <?php echo json_encode($Longitudes); ?>;
            var nVoters = latitude.length;

            // Store each voter's information in an object
            // This will make it easier to index data

            var allVoters = [];

            for (var i = 0; i < nVoters; i++) {
                var newVoter = {
                    VoterID: voterIDs[i],
                    First: firstNames[i],
                    Last: lastNames[i],
                    Age: ages[i],
                    Party: parties[i],
                    Gender: genders[i],
                    Address: addresses[i],
                    City: cities[i],
                    Group: 0,
                };
                allVoters.push(newVoter);
            };

            var coordsList = {
                lat: latitude,
                lon: longitude,
                size: dotSize,
            };

            var group = 1; // init group as 1

            var colors = []
            for (var i = 0; i < nVoters; i++) colors.push("#000000"); // init dots as black

            // TODO: add more colors
            var color_groups = ['#ff0000', '#009900', '#00ffff', '#cc33ff', '#ffff66','#ff6600', '#6600cc'];

            var data = [{
              type:'scattermapbox',
              lat: coordsList.lat,
              lon: coordsList.lon,
              mode: 'markers',
              marker: {
                size: coordsList.size,
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
                  lat: getAverage(coordsList.lat),
                  lon: getAverage(coordsList.lon),
                },
                pitch:0,
                zoom:8
              },
            }

            var mapboxToken = "<?php echo $mapboxToken ?>";
            Plotly.setPlotConfig({
                mapboxAccessToken:  mapboxToken,
            });

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

                // When we make a selection, for each point we selected:
                // Change these markers' colors, and add them to the appropriate
                // group.
                gd.on('plotly_selected', function(eventData) {
                    var color_index = group % color_groups.length; // choose a color from the pre-defined 9

                    eventData.points.forEach(function(pt) {
                        colors[pt.pointNumber] = color_groups[color_index];
                        allVoters[pt.pointNumber].Group = group; // set group of selected dots
                    });

                    group ++ ; // increment group
                    console.log("A");
                    Plotly.restyle(gd, 'marker.color', [colors]);
                    console.log("B");
                });

                // TODO: While selecting, do not fade the not-selected dots
                // TODO: find the undefined error in the below block
                // gd.on('plotly_selecting', function(eventData) {
                //     var opacities = [];
                //     for(var i = 0; i < nVoters; i++) opacities.push(1);
                //
                //     eventData.points.forEach(function(pt) {
                //         opacities[pt.pointNumber] = 1;
                //     });
                //     console.log(1);
                //     Plotly.restyle(gd, 'marker.opacity', [opacities], [0]);
                //     console.log(2);
                // });
                console.log('If you can read this, please help me! Email underdogDatabases@gmail.com');

            })();
            </script>
            <a href="#"  onclick = "exportTurf()"><button id = 'export_turf' class = 'btn btn-primary'>Export Turf</button></a>

            <script type = 'text/javascript'>
            function checkGroup(voter) {
                return voter.Group == g;
            }

            function exportTurf() {
                console.log("Exporting");
                // For each group, export the list when the button is clicked.
                // Will use global(?) variable to compare since I can't figure out how to do more arguments
                var baseFilename = 'canvass_list';
                for (var g = 1; g < group; g ++) {
                    // var currGroup = allVoters.filter(checkGroup);
                    var currGroup = allVoters.filter(function(voter) {
                        return voter.Group == g;
                    });
                    var group_filename = baseFilename + g + '.csv';
                    download(convertArrayOfObjectsToCSV({data:currGroup}), group_filename, 'text/javascript');
                    // downloadCSV({filename:group_filename, data:currGroup});
                    // TODO: also export a corresponding map
                }
            }
            </script>
        </div>
        <div class = 'spacer'></div>
    </div>
    <footer>
    </footer>
</body>
</html>
