<?php
session_start();
include 'config.php';

if(!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'Admin'){
    header("Location: index.php");
    exit();
}

// get users
$sql = "SELECT s.staff_id, s.full_name, s.email, s.role, d.name AS department, s.status
        FROM staff s
        LEFT JOIN departments d ON s.department_id = d.department_id
        ORDER BY s.staff_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>

    <style>
        body{
            font-family:Arial;
            background:#0f172a;
            padding:20px;
        }

        .box{
            background:white;
            padding:20px;
            border-radius:10px;
            max-width:1100px;
            margin:auto;
        }

        h2{
            text-align:center;
        }

        .top{
            display:flex;
            justify-content:space-between;
            margin-bottom:15px;
        }

        a.btn{
            padding:8px 12px;
            text-decoration:none;
            color:white;
            border-radius:6px;
        }

        .add{background:#2563eb;}
        .back{background:#f59e0b;}

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#2563eb;
            color:white;
            padding:10px;
        }

        td{
            padding:10px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        .edit{background:green;color:white;padding:5px 8px;border-radius:5px;text-decoration:none;}
        .delete{background:red;color:white;padding:5px 8px;border-radius:5px;text-decoration:none;}

    </style>
</head>

<body>

<div class="box">

    <!-- HEADER -->
    <h2>MANAGE USERS</h2>

    <!-- TOP BUTTONS (ONLY ONCE) -->
    <div class="top">
        <a href="add_staff.php" class="btn add">+ Add Staff</a>
        <a href="dashboard.php" class="btn back">← Dashboard</a>
    </div>

    <!-- TABLE -->
    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Department</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($row = $result->fetch_assoc()){ ?>

        <tr>
            <td><?php echo $row['staff_id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['department'] ?? 'N/A'; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <a class="edit" href="edit_user.php?id=<?php echo $row['staff_id']; ?>">Edit</a>
                <a class="delete" href="delete_user.php?id=<?php echo $row['staff_id']; ?>">Delete</a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>