<?php
include "viel_connection.php";

$query = "SELECT birthday FROM tbl_viel";
$result = mysqli_query($conn, $query);
$ages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $birthDate = new DateTime($row['birthday']);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        $ages[] = $age;
    }
}

$ageCount = array_count_values($ages);

// Create brackets and initialize bracket counts
$brackets = array(
    "0-10" => 0,
    "11-20" => 0,
    "21-30" => 0,
    "31-40" => 0,
    "41-50" => 0,
    "51-60" => 0,
    "61+" => 0
);

// Assign ages to brackets
foreach ($ageCount as $age => $count) {
    if ($age >= 0 && $age <= 10) {
        $brackets["0-10"] += $count;
    } elseif ($age <= 20) {
        $brackets["11-20"] += $count;
    } elseif ($age <= 30) {
        $brackets["21-30"] += $count;
    } elseif ($age <= 40) {
        $brackets["31-40"] += $count;
    } elseif ($age <= 50) {
        $brackets["41-50"] += $count;
    } elseif ($age <= 60) {
        $brackets["51-60"] += $count;
    } else {
        $brackets["61+"] += $count;
    }
}

$chartData = array();

foreach ($brackets as $bracket => $count) {
    $chartData[] = array('bracket' => $bracket, 'count' => $count);
}

$jsonChartData = json_encode($chartData);
?>

<html>
<head>
    <title>Bracketed Age Bar Graph</title>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<style>
    body {
            background: url('b2.jpg')no-repeat;
            background-size: cover;
            background-position: center;
        }

        h1{
            color: purple;
            font-family: arial;
            text-align: center;
        }
</style>

    <h1>Age Bar Graph</h1>
</head>
<body>
    <div id="chartdiv" style="width: 100%; height: 500px;"></div>
</body>
</html>

<script>
am5.ready(function() {
    var root = am5.Root.new("chartdiv");

    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
    xRenderer.labels.template.setAll({
        rotation: -45,
        centerY: am5.p50,
        centerX: am5.p50,
        paddingTop: 5,
        paddingBottom: 5
    });

    xRenderer.grid.template.setAll({
        location: 0
    });

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "bracket",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
    }));

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: am5xy.AxisRendererY.new(root, {
            strokeOpacity: 0.1
        })
    }));

    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Age Distribution",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "count",
        categoryXField: "bracket",
        tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY}"
        })
    }));

    series.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        strokeOpacity: 0
    });

    series.columns.template.adapters.add("fill", function(fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function(stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    var data = <?php echo $jsonChartData; ?>;
    xAxis.data.setAll(data);
    series.data.setAll(data);

    series.appear(1000);
    chart.appear(1000, 100);
});
</script>
