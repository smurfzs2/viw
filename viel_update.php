<?php
include 'viel_connection.php';

$id = $_GET["id"];
$sql = "SELECT tbl_viel.departmentId, departmentName, id, firstName, lastName, gender, address, birthday FROM tbl_viel INNER JOIN `hr_department` ON tbl_viel.departmentId=hr_department.departmentId WHERE id = $id";
$query = $sql . " GROUP BY tbl_viel.departmentID";
$result = $conn->query($sql);
$row = $result->fetch_assoc(); // Fetch the data

$selectedData = ($gender == 0) ? 'checked' : "";
$selectedData1 = ($gender == 1) ? 'checked' : "";

?>

<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            ;
            background: url('pic.jpg')no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 500px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(20px);
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            color: white;
            border-radius: 10px;
            padding: 30px 40px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
            color: white;
        }

        .wrapper label {
            font-size: 20px;
            color: white;
        }


        .wrapper .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 30px 0;
        }

        input[type=text],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
            resize: vertical
        }


        input[type=submit] {
            background-color: #7214ac;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h1>ADD TABLE</h1>

        <form method="POST" action="viel_updateProcess.php" >
            <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">

            <label for="firstName">FirstName: </label>
            <input type="text" name="firstName" value="<?php echo $row["firstName"]; ?>">

            <label for="lastName">LastName: </label>
            <input type="text" name="lastName" value="<?php echo $row["lastName"]; ?>">

            <label for="gender">Gender:</label><br>
            <input type='radio' name='gender' value='0' <?php echo $selectedData; ?>> Male
            <input type='radio' name='gender' value='1' <?php echo $selectedData1; ?>> Female
            <br><br>

            <label>Address</label>
            <textarea id="address" name="address" rows="4" cols="50"><?php echo $row["address"]; ?></textarea>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo $row["birthday"]; ?>"><br>

            <select id="departmentId" name="departmentId" style='color:black'>
                <?php
                $query = $sql . " GROUP BY tbl_viel.departmentId";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) > 0) {
                    while ($r = mysqli_fetch_assoc($res)) {
                        $selected = ($row['departmentId'] == $r['departmentId']) ? "selected hidden" : "";
                        echo "<option " . $selected . " value='" . $r['departmentId'] . "'>" . $r['departmentName'] . "</option>";
                    }
                }
                ?>
                <option value="1">Accounting</option>
                <option value="2">Engineering</option>
                <option value="3">HR</option>
                <option value="4">IT</option>
                <option value="5">Purchasing</option>
                <option value="6">Production</option>
                <option value="7">IMPEX</option>
                <option value="8">PPIC</option>
                <option value="9">Sales</option>
                <option value="10">RPD</option>
                <option value="11">Logistics</option>
                <option value="12">FVI</option>
                <option value="13">QC</option>
                <option value="14">Utility</option>
                <option value="15">Security</option>
                <option value="16">QA</option>
                <option value="17">Top Management</option>
                <option value="18">DCC</option>
            </select>

            <input type="submit" name="update" value="Update">
        </form>
        <div>
</body>

</html>