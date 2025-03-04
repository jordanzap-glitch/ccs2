<?php
include '../session.php';
include('../db.php');

// Initialize variables for the search term and date filter
$searchTerm = '';
$filterDate = '';

// Check if the form has been submitted
if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['fullname']);
}

// Check if the date filter has been submitted
if (isset($_POST['filter'])) {
    $filterDate = mysqli_real_escape_string($conn, $_POST['filter_date']);
}

// Fetch user logs from the database with optional search and date filtering
$query = "SELECT id, user_id, fullname, course, user_type, action, timestamp FROM user_logs WHERE 1=1"; // 1=1 for easier appending of conditions

if (!empty($searchTerm)) {
    $query .= " AND fullname LIKE '%$searchTerm%'";
}

if (!empty($filterDate)) {
    // Use DATE() to filter by the date part only
    $query .= " AND DATE(timestamp) = DATE('$filterDate')"; // Filter by the selected date
}

$query .= " ORDER BY timestamp ASC"; // Default sorting by timestamp
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Align form elements */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .form-group label {
            margin-right: 10px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<h1>User Logs</h1>

<!-- Search and Filter Form -->
<form method="POST" action="">
    <div class="form-group">
        <label for="fullname">Search by Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <input type="submit" name="search" value="Search">
    </div>
    
    <div class="form-group">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($filterDate); ?>">
        <input type="submit" name="filter" value="Filter">
    </div>
</form>

<table>
    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Course</th>
        <th>User Type</th>
        <th>Action</th>
        <th>Timestamp</th>
    </tr>
    <?php
    // Fetch and display each row of the result
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['course']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['action']) . "</td>";
        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>