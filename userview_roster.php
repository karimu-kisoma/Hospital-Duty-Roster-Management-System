<?php
session_start();
include 'config.php';

// STRICT CHECK: Only allow users with role = 'user'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    // Redirect admins or anyone else to user login
    session_destroy(); // destroy session for safety
    header("Location: user_index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch only duties assigned to this user
$query = "SELECT dr.roster_id, s.full_name, d.name as department, dr.duty_date, dr.shift 
          FROM duty_roster dr
          JOIN staff s ON dr.staff_id = s.staff_id
          JOIN departments d ON dr.department_id = d.department_id
          WHERE dr.staff_id = ?
          ORDER BY dr.duty_date ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Duty Roster</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg,#ACB6E5,#74ebd5);
    padding: 20px;
}

.container {
    max-width: 950px;
    margin: auto;
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-weight: 600;
}

.table thead {
    background-color: #007BFF;
    color: #fff;
    font-weight: 500;
}

.logout-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(145deg, #ff4d4d, #ff1a1a);
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}
</style>
</head>
<body>
<div class="container">
    <h2>My Duty Roster</h2>

    <?php if($result->num_rows > 0){ ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Shift</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['duty_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['shift']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } else { ?>
        <p class="text-center fw-semibold">No duties assigned yet.</p>
    <?php } ?>

    <p class="text-center">
        <a href="user_logout.php" class="logout-btn">Logout</a>
    </p>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>