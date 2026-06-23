
<?php
session_start();
include 'config.php';

/*
|--------------------------------------------------------------------------
| SAFE SESSION
|--------------------------------------------------------------------------
*/
$staff_id = $_SESSION['staff_id'] ?? null;
$role = strtolower($_SESSION['role'] ?? 'staff');

$user_id = $staff_id;

/*
|--------------------------------------------------------------------------
| FETCH ROSTER
|--------------------------------------------------------------------------
*/
$query = "SELECT dr.roster_id,
                 s.full_name,
                 d.name as department,
                 dr.duty_date,
                 dr.shift
          FROM duty_roster dr
          JOIN staff s ON dr.staff_id = s.staff_id
          JOIN departments d ON dr.department_id = d.department_id
          ORDER BY dr.duty_date ASC";

$result = $conn->query($query);

/*
|--------------------------------------------------------------------------
| COMMENT
|--------------------------------------------------------------------------
*/
if(isset($_POST['add_comment']) && $user_id){

    $roster_id = $_POST['roster_id'];
    $comment = trim($_POST['comment']);

    if(!empty($comment)){
        $stmt = $conn->prepare("
            INSERT INTO roster_comments(roster_id,user_id,comment)
            VALUES(?,?,?)
        ");
        $stmt->bind_param("iis",$roster_id,$user_id,$comment);
        $stmt->execute();
    }
}

/*
|--------------------------------------------------------------------------
| LEAVE REQUEST
|--------------------------------------------------------------------------
*/
if(isset($_POST['request_leave']) && $user_id){

    $roster_id = $_POST['roster_id'];
    $reason = trim($_POST['reason']);

    if(!empty($reason)){
        $stmt = $conn->prepare("
            INSERT INTO leave_requests(roster_id,user_id,reason,status)
            VALUES(?,?,?,'Pending')
        ");
        $stmt->bind_param("iis",$roster_id,$user_id,$reason);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Duty Roster</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,#ACB6E5,#74ebd5);
    padding:20px;
}

.container{
    max-width:1400px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
}

.status{
    margin-top:10px;
    padding:6px 10px;
    border-radius:6px;
    display:inline-block;
    font-weight:bold;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.approved{
    background:#d4edda;
    color:#155724;
}

.rejected{
    background:#f8d7da;
    color:#721c24;
}

.action-bar{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
}

.btn-custom{
    padding:10px 18px;
    border-radius:8px;
    color:white;
    text-decoration:none;
    background:#2575fc;
}

.logout-btn{
    background:#ff4d4d;
}

textarea{
    min-height:80px;
}
</style>
</head>

<body>

<div class="container">

<h2 class="text-center mb-4">Duty Roster</h2>

<?php if($result && $result->num_rows > 0){ ?>

<div class="table-responsive">

<table class="table table-bordered table-striped align-middle">

    <thead class="table-dark">
        <tr>
            <th>Staff Name</th>
            <th>Department</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Comment</th>
            <th>Leave Request</th>
            <th>Leave Status</th>
        </tr>
    </thead>

    <tbody>

    <?php while($row = $result->fetch_assoc()){ ?>

        <tr>

            <td><?= htmlspecialchars($row['full_name']); ?></td>

            <td><?= htmlspecialchars($row['department']); ?></td>

            <td><?= htmlspecialchars($row['duty_date']); ?></td>

            <td><?= htmlspecialchars($row['shift']); ?></td>

            <td width="250">

                <form method="POST">
                    <input type="hidden" name="roster_id" value="<?= $row['roster_id']; ?>">

                    <textarea
                        name="comment"
                        class="form-control mb-2"
                        required
                    ></textarea>

                    <button
                        class="btn btn-primary btn-sm"
                        name="add_comment">
                        Comment
                    </button>
                </form>

            </td>

            <td width="250">

                <form method="POST">
                    <input type="hidden" name="roster_id" value="<?= $row['roster_id']; ?>">

                    <textarea
                        name="reason"
                        class="form-control mb-2"
                        required
                    ></textarea>

                    <button
                        class="btn btn-warning btn-sm"
                        name="request_leave">
                        Request Leave
                    </button>
                </form>

            </td>

            <td>

                <?php
                $leave_q = $conn->query("
                    SELECT status, reason, created_at
                    FROM leave_requests
                    WHERE user_id = '$user_id'
                    AND roster_id = '".$row['roster_id']."'
                    ORDER BY created_at DESC
                ");

                if($leave_q && $leave_q->num_rows > 0){

                    while($l = $leave_q->fetch_assoc()){

                        $statusClass = strtolower($l['status']);

                        echo "<div class='status $statusClass'>"
                            . htmlspecialchars($l['status']) .
                            "</div>
                            <p>" . htmlspecialchars($l['reason']) . "</p>
                            <small>" . $l['created_at'] . "</small>
                            <hr>";
                    }

                }else{

                    echo "<p>No leave request yet.</p>";
                }
                ?>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

</div>

<?php }else{ ?>

<div class="alert alert-info">
    No roster records found.
</div>

<?php } ?>

<div class="action-bar">

<?php if($role === 'admin'){ ?>
    <a href="dashboard.php" class="btn-custom">
        ← Dashboard
    </a>
<?php } ?>

<a href="user_logout.php" class="btn-custom logout-btn">
    Logout
</a>

</div>

</div>

</body>
</html>

