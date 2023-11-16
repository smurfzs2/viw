<?php
include_once 'viel_connection.php';

$sql = "SELECT tbl_viel.departmentId, departmentName, id, firstName, lastName, gender, address, birthday FROM tbl_viel INNER JOIN `hr_department` ON tbl_viel.departmentId=hr_department.departmentId";

if (isset($_POST['submit'])) 
{
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $departmentId = $_POST['departmentId'];
    $search = $_POST['search'];
    $searchConditions = array();

    if (!empty($firstName)) {
        $searchConditions[] = "firstName LIKE '%$firstName%'";
    }
    if (!empty($lastName)) {
        $searchConditions[] = "lastName LIKE '%$lastName%'";
    }
    if (!empty($address)) {
        $searchConditions[] = "address LIKE '%$address%'";
    }
    if ($gender!='') {
        $searchConditions[] = "gender = '$gender'";
    }
    if (!empty($birthday)) {
        $searchConditions[] = "birthday = '$birthday'";
    }
    if (!empty($departmentId)) {
        $searchConditions[] = "tbl_viel.departmentId = $departmentId";
    }

    if (!empty($searchConditions)) {
        $sql .= " WHERE " . implode(' AND ', $searchConditions);
    }
}

$query = "SELECT COUNT(*) AS total FROM ($sql) AS total_records";
$totalRecordsResult = mysqli_query($conn, $query);
$totalRecordsData = mysqli_fetch_assoc($totalRecordsResult);
$totalRecords = $totalRecordsData['total'];

// Process and return the data based on DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$query = $sql . "GROUP BY tbl_viel.departmentID LIMIT $start, $length";

$result = mysqli_query($conn, $query);
$data = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $gender = $row['gender'] == 0 ? "Male" : "Female";
        $address = $row['address'];
        $birthday = date("F d, Y", strtotime($row['birthday']));
        $departmentId = $row['departmentName'];

        $button = "<a class='btn btn-primary btn-sm' href='viel_update.php?id=$id' name='update'><i class='fas fa-edit'></i></a>";
        $button .= "<a class='btn btn-danger btn-sm' href='viel_delete.php?id=$id' name='delete'><i class='delete fas fa-trash'></i></a>";

        $data[] = array(
            $id,
            $firstName,
            $lastName,
            $gender,
            $address,
            $birthday,
            $departmentId,
            $button
        );
    }
}

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Table Records</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(to bottom, #fcf0f5 20%, #f6cdde 50%, #efaac8 80%);
            background-size: cover;
            background-position: center;
        }
        input.form-control {
            border: 2px solid purple;
        }

        select.form-control{
            border: 2px solid purple;
        }

        .dataTables_scrollBody {
            /* height: 50vh; */
            /* Set a max height for the scrolling area */
            overflow-y: scroll;
            /* Enable vertical scrolling */
            scroll-behavior: smooth;
            /* background-color: red; */
            /* Add smooth scrolling behavior */
        }

        h1{
            color: purple;
        }

        h4{
            color: purple;
        }
        
    </style>

</head>

<body style="margin: 50px;">

    <h1> Table Records </h1>
    <div class="container-fluid text-center">
        <div class="row">

            <div class="col">
                <form method="post">
                    <input type="text" class="form-control rounded bs-danger-bg-subtle" placeholder="Fisrtname" name="firstName" value="<?php if (isset($_POST['firstName'])) {echo $_POST['firstName'];} ?>">
            </div>

            <div class="col">
                <input type="text" class="form-control rounded" placeholder="Lastname" name="lastName" value="<?php if (isset($_POST['lastName'])) {echo $_POST['lastName'];} ?>">
            </div>

            <div class="col">
                <input type="text" class="form-control rounded" placeholder="Address" name="address" value="<?php if (isset($_POST['address'])) {echo $_POST['address'];} ?>">
            </div>

            <div class="col">
                <select class="form-control rounded" name="gender">
                    <option disabled selected hidden>Gender</option>
                    <option value="">
                        <?php
                        $selectedData = "";
                        $selectedData1 = "";
                        if (isset($_POST['gender'])) {
                            $selectedData = ($_POST['gender'] == 0) ? 'selected' : '';
                            $selectedData1 = ($_POST['gender'] == 1) ? 'selected' : '';
                        } else {
                            echo "Gender";
                        }
                        ?>
                    </option>
                    <option <?php echo $selectedData; ?> value="0">Male</option>
                    <option <?php echo $selectedData1; ?> value="1">Female</option>
                </select> 
            </div>

            <div class="col">

                <input type="date" class="form-control rounded" placeholder="birthday" name="birthday" value="<?php if (isset($_POST['birthday'])) {echo $_POST['birthday'];} ?>">
            </div>

            <div class="col">
            <select class="form-control rounded" name="departmentId">
            <option  disabled selected hidden >Department</option>
            <option value="">
            <?php
                if (isset($_POST['departmentId'])) {
                    $departmentId = $_POST['departmentId'];
                }
                    $query = $sql . " GROUP BY tbl_viel.departmentId";
                    $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) > 0) {
                while ($r = mysqli_fetch_assoc($res)) {
                    $selected = ($departmentId == $r['departmentId']) ? "selected" : "";
                echo "<option " . $selected . " value='" . $r['departmentId'] . "'>" . $r['departmentName'] . "</option>";
                }
            }
            ?>
            </option>
            </select>
            </div>

            <div class="col">
                <button class="btn btn-outline-primary" name="submit">Go</button>
                <a class="btn btn-outline-danger" name="add" href="viel_addForm.php">Add</a>
            </div>

            <div class="col">
                <a class="btn btn-outline-primary" name="pdf" href="viel_generatePDF.php?sqlData=<?php echo $sql; ?>">PDF</a>
                <a class="btn btn-outline-danger" name="csv" href="viel_generateCSV.php?sqlData=<?php echo $sql; ?>">CSV</a>
            </div>

            <div class="col">
                <a class="btn btn-outline-primary" name="pie" href="viel_pieChart.php?sqlData=<?php echo $sql; ?>">Pie</a>
                <a class="btn btn-outline-danger" name="bar" href="viel_barGraph.php?sqlData=<?php echo $sql; ?>">Bar</a>
            </div>

            </form>
        </div>
    </div>

    <div class="container-fluid text-left">
        <div class="row">

            <div class="col">
                <form method="post">
                    <h4>Total:<?php echo $totalRecords; ?></h4>
            </div>
    
    <div class="container-fluid text-center">
    <table class="table table-hover" id="tblUser" style="width:100%tbl_viel">
    <thead>
        <tr>
            <th class="table-danger text-danger">ID</th>
            <th class="table-danger text-danger">First Name</th>
            <th class="table-danger text-danger">Last Name</th>
            <th class="table-danger text-danger">Gender</th>
            <th class="table-danger text-danger">Address</th>
            <th class="table-danger text-danger">Birthday</th>
            <th class="table-danger text-danger">Department</th>
            <th class="table-danger text-danger">Actions</th>
        </tr>
    </thead>
        </div>
</body>

<!-- datatables source -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.1.0/js/dataTables.scroller.min.js"></script>
<!-- end -->


<script>
    $(document).ready(function() {
        var totalRecords = "<?php echo $totalRecords; ?>";
        var table = $('#tblUser').DataTable({
            'processing': true,
            'serverSide': true,
            "bLengthChange": false,
            "info": false,
            "sort": false,
            "sDom": "lrti", // Adjust if needed
            "ajax": {
                url: "viel_dataSource.php",
                type: "POST",
                data: {

                    "totalRecords": totalRecords,
                    "query": "<?php echo $sql; ?>"
                },
                error: function(data) {
                    console.log(data);
                }
            },
            scrollY: 450,
            // scrollX: true,
            scrollCollapse: false,
            scroller: {
                loadingIndicator: true
            },
            stateSave: false
            // Custom buttons configuration
            // buttons: ['createState', 'savedStates']
        });
    });
    //console.log(totalRecords)
</script>

</body>

</html>