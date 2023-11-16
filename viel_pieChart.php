<?php
include "viel_connection.php";

// Query to count the number of repeated department names
// $query = "SELECT count(*) FROM tbl_khenneth INNER JOIN `hr_department` ON tbl_khenneth.id=hr_department.departmentId ";

$query = "SELECT count(*) as total,gender FROM tbl_viel group by gender";
$result= mysqli_query($conn,$query);

$chartData = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    $chartData[] = ['gender' => ($row['gender']==0)?'Male':'Female', 'value' => (int)$row['total'] ];
    }

    $data = json_encode($chartData);
} else {
    echo "No records found";
}

?>
<style>

body {
            background: url('b1.jpg')no-repeat;
            background-size: cover;
            background-position: center;
        }

        h1{
            color: blue;
            font-family: arial;
            text-align: center;
        }

#chartdiv {
    width: 100%;
    height: 500px;
}
</style>

<h1> Gender Pie Chart</h1>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
    am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
var chart = root.container.children.push(
    am5percent.PieChart.new(root, {
    endAngle: 270
    })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
var series = chart.series.push(
    am5percent.PieSeries.new(root, {
    valueField: "value",
    categoryField: "gender",
    endAngle: 270
    })
);

series.states.create("hidden", {
    endAngle: -90
});

// Set data
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
series.data.setAll(<?php echo $data; ?>);

series.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>