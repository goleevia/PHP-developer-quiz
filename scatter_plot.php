<?php

// Load CSV data
$csvData = array_map('str_getcsv', file('data.csv'));

// Separate the columns (assuming two columns for scatter plot)
$x = [];
$y = [];

// Skip the first row (header)
array_shift($csvData);

foreach ($csvData as $row) {
    // Check if both values are numbers and sanitize
    $xValue = is_numeric($row[0]) ? (float)$row[0] : 0;
    $yValue = is_numeric($row[1]) ? (float)$row[1] : 0;

    $x[] = $xValue;
    $y[] = $yValue;
}

// Data processing and plot generation using Google Charts

// Convert data to JSON format for use in Google Charts
$xData = json_encode($x);
$yData = json_encode($y);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scatter Plot</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart', 'scatter']
        });

        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Convert PHP arrays to JavaScript arrays
            var xData = <?php echo $xData; ?>;
            var yData = <?php echo $yData; ?>;
            
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'X');
            data.addColumn('number', 'Y');
            
            // Add the data points to the chart
            for (var i = 0; i < xData.length; i++) {
                data.addRow([xData[i], yData[i]]);
            }

            var options = {
                title: 'Scatter Plot of CSV Data',
                hAxis: {title: 'X Axis'},
                vAxis: {title: 'Y Axis'},
                legend: 'none'
            };

            var chart = new google.visualization.ScatterChart(document.getElementById('scatter-chart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="scatter-chart" style="width: 900px; height: 500px;"></div>
</body>
</html>
