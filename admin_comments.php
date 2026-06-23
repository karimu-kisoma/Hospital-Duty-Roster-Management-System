<?php
session_start();
include 'config.php';

if($_SESSION['role'] != 'Admin'){
    header("Location:index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| DELETE COMMENT
|--------------------------------------------------------------------------
*/

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $conn->query("DELETE FROM roster_comments 
                  WHERE comment_id='$id'");
}

/*
|--------------------------------------------------------------------------
| FETCH COMMENTS
|--------------------------------------------------------------------------
*/

$query = "
SELECT rc.comment_id,
       rc.comment,
       rc.created_at,
       s.full_name,
       dr.duty_date,
       dr.shift
FROM roster_comments rc
JOIN staff s ON rc.user_id = s.staff_id
JOIN duty_roster dr ON rc.roster_id = dr.roster_id
ORDER BY rc.created_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Roster Comments</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef2f7;
    padding:30px;
    font-family:Arial;
}

.container{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.comment-box{
    border-left:5px solid #0d6efd;
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<h2 class="text-center mb-4">
    Staff Comments
</h2>

<?php if($result->num_rows > 0){ ?>

<?php while($row = $result->fetch_assoc()){ ?>

<div class="comment-box">

<h5>
<?php echo htmlspecialchars($row['full_name']); ?>
</h5>

<p>
<strong>Date:</strong>
<?php echo htmlspecialchars($row['duty_date']); ?>
</p>

<p>
<strong>Shift:</strong>
<?php echo htmlspecialchars($row['shift']); ?>
</p>

<p>
<?php echo htmlspecialchars($row['comment']); ?>
</p>

<small class="text-muted">
<?php echo $row['created_at']; ?>
</small>

<br><br>

<a href="?delete=<?php echo $row['comment_id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this comment?')">
   Delete
</a>

</div>

<?php } ?>

<?php } else { ?>

<p>No comments found.</p>

<?php } ?>

<a href="dashboard.php" class="btn btn-primary">
    ← Back Dashboard
</a>

</div>

</body>
</html>