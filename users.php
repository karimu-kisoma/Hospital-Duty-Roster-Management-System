<?php
session_start();
include 'config.php';

// Ensure only admin can access
if(!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'Admin'){
    header("Location: index.php");
    exit();
}

// Fetch all staff
$result = $conn->query("SELECT s.staff_id, s.full_name, s.email, s.role, d.name as department, s.status
                        FROM staff s
                        LEFT JOIN departments d ON s.department_id = d.department_id
                        ORDER BY s.full_name ASC");
?>

<h2>Manage Users</h2>



<a href="add_staff.php">Add New Staff</a>
<table border="1" cellpadding="5" cellspacing="0">
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
    <td><?php echo $row['department'] ? $row['department'] : 'N/A'; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <a href="edit_user.php?id=<?php echo $row['staff_id']; ?>">Edit</a> | 
        <a href="delete_user.php?id=<?php echo $row['staff_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>

<!-- Back to Dashboard Link -->
<p><a href="dashboard.php">← Back to Dashboard</a></p>
<?php } ?>
</table>
