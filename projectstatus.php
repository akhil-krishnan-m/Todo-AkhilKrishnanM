<?php
  include 'header.php';
  include 'footer.php';
require 'dbconnect.php';

// Fetch data from both tables using a JOIN
$sql = "
    SELECT 
        todolist.title AS project_title, 
        tasklist.task, 
        tasklist.status 
    FROM 
        todolist 
    LEFT JOIN 
        tasklist 
    ON 
        todolist.pid = tasklist.pid
    ORDER BY 
        todolist.pid
";
$result = $conn->query($sql);

$projects = []; // Array to group projects by title

// Group tasks under their respective project titles
while ($row = $result->fetch_assoc()) {
    $project_title = $row['project_title'];
    $projects[$project_title][] = [
        'task' => $row['task'],
        'status' => $row['status']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Status</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Link to CSS file -->
</head>
<body>
    <center><h1>Project Status</h1></center>
    
    <!-- Display projects with their tasks -->
    <?php foreach ($projects as $project_title => $tasks): ?>
        <h2><?php echo htmlspecialchars($project_title); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Completed</th>
                    <th>Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['task']); ?></td>
                    <td>
                        <!-- Checkbox for completed tasks -->
                        <input type="checkbox" <?php if ($task['status'] == 'completed') echo 'checked'; ?> disabled>
                    </td>
                    <td>
                        <!-- Checkbox for pending tasks -->
                        <input type="checkbox" <?php if ($task['status'] == 'pending') echo 'checked'; ?> disabled>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    
    <!-- Close the connection -->
    <?php $conn->close(); ?>
</body>
</html>