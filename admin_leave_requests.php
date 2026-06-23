<?php
session_start();
include 'config.php';

if($_SESSION['role'] != 'Admin'){
    header("Location:index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| APPROVE / REJECT
|--------------------------------------------------------------------------
*/

if(isset($_GET['approve'])){

    $id = $_GET['approve'];

    $conn->query("UPDATE leave_requests 
                  SET status='Approved'
                  WHERE request_id='$id'");
}

if(isset($_GET['reject'])){

    $id = $_GET['reject'];

    $conn->query("UPDATE leave_requests 
                  SET status='Rejected'
                  WHERE request_id='$id'");
}

/*
|--------------------------------------------------------------------------
| FETCH REQUESTS
|--------------------------------------------------------------------------
*/

$query = "
SELECT lr.request_id,
       s.full_name,
       lr.reason,
       lr.status,
       lr.created_at,
       dr.duty_date,
       dr.shift
FROM leave_requests lr
JOIN staff s ON lr.user_id = s.staff_id
JOIN duty_roster dr ON lr.roster_id = dr.roster_id
ORDER BY lr.created_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Leave Requests</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    font-family:Arial;
    padding:30px;
}

.container{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.status-pending{
    color:orange;
    font-weight:bold;
}

.status-approved{
    color:green;
    font-weight:bold;
}

.status-rejected{
    color:red;
    font-weight:bold;
}

</style>

</head>
<body>

<div class="container">

<h2 class="mb-4 text-center">
    Leave Requests
</h2>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>
    <th>Staff</th>
    <th>Date</th>
    <th>Shift</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td>
<?php echo htmlspecialchars($row['full_name']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['duty_date']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['shift']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['reason']); ?>
</td>

<td>

<?php

$status = $row['status'];

if($status == 'Pending'){
    echo "<span class='status-pending'>Pending</span>";
}
elseif($status == 'Approved'){
    echo "<span class='status-approved'>Approved</span>";
}
else{
    echo "<span class='status-rejected'>Rejected</span>";
}

?>

</td>

<td>

<a href="?approve=<?php echo $row['request_id']; ?>"
   class="btn btn-success btn-sm">
   Approve
</a>

<a href="?reject=<?php echo $row['request_id']; ?>"
   class="btn btn-danger btn-sm">
   Reject
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<a href="dashboard.php" class="btn btn-primary mt-3">
    ← Back Dashboard
</a>

</div>

</body>
</html>