<?php

 include 'header.php';
  include 'footer.php';
  require 'dbconnect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from both tables using a JOIN
$sql = "
    SELECT 
        todolist.pid, 
        todolist.title AS project_title, 
        todolist.description AS project_description, 
        todolist.currentdate, 
        tasklist.task, 
        tasklist.status 
    FROM 
        todolist 
    LEFT JOIN 
        tasklist 
    ON 
        todolist.pid = tasklist.pid
";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project and Task List</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Link to CSS file -->
</head>
<body>
<center><h1>Project and Task List</h1></center>
    
    <!-- Check if data is available -->
   
    <table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Project Title</th>
                <th>Description</th>
                <th>Task</th>
                <th>Created Date</th>
                <th>Actions</th> <!-- Edit and delete links -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $index = 1; // To number the rows
            while ($row = $result->fetch_assoc()): 
            ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo htmlspecialchars($row['project_title']); ?></td>
                <td><?php echo htmlspecialchars($row['project_description']); ?></td>
                <td><?php echo htmlspecialchars($row['task']); ?></td>
                <td><?php echo htmlspecialchars($row['currentdate']); ?></td>
                <td>
                    <!-- Edit and delete links -->
                    <a href="projectstatus.php?pid=<?php echo $row['pid']; ?>">View Project Status</a> | 
                    <a href="edittodo.php?pid=<?php echo $row['pid']; ?>">Edit</a> | 
                    <a href="deletetodo.php?pid=<?php echo $row['pid']; ?>" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
   
    
    <!-- Add a link to create a new project -->
    <a href="create.php">Create New Project</a>
    
    <!-- Close the connection -->
    <?php $conn->close(); ?>
</body>
</html>
