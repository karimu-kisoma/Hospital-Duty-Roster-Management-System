<?php
include 'config.php';

$query = "SELECT dr.roster_id, s.full_name, d.name as department, dr.duty_date, dr.shift 
          FROM duty_roster dr
          JOIN staff s ON dr.staff_id = s.staff_id
          JOIN departments d ON dr.department_id = d.department_id
          ORDER BY dr.duty_date ASC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Duty Roster</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,#ACB6E5,#74ebd5);
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

a.back-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(145deg, #6a11cb, #2575fc);
    padding: 10px 20px;
    border-radius: 8px;
}

a.back-btn:hover {
    opacity: 0.9;
}
</style>
</head>
<body>
<div class="container">
    <h2>Duty Roster</h2>
    <?php if($result->num_rows > 0){ ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-primary">
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
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo $row['duty_date']; ?></td>
                    <td><?php echo $row['shift']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } else { ?>
        <p class="text-center">No duties assigned yet.</p>
    <?php } ?>

    <p class="text-center"><a href="dashboard.php" class="back-btn">Back to Dashboard</a></p>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>