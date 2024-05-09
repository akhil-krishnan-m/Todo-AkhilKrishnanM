<?php
require 'dbconnect.php';


$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;


$title = $_POST['title'];
$description = $_POST['description'];

$update_project = "UPDATE todolist SET title = ?, description = ? WHERE pid = ?";
$stmt_project = $conn->prepare($update_project);
$stmt_project->bind_param("ssi", $title, $description, $pid);

if (!$stmt_project->execute()) {
    die("Error updating project: " . $stmt_project->error);
}

// Update the tasks
if (isset($_POST['tid']) && isset($_POST['task']) && isset($_POST['status'])) {
    foreach ($_POST['tid'] as $index => $tid) {
        $task = $_POST['task'][$index];
        $status = $_POST['status_' . $tid]; 
        $update_task = "UPDATE tasklist SET task = ?, status = ? WHERE tid = ?";
        $stmt_task = $conn->prepare($update_task);
        $stmt_task->bind_param("ssi", $task, $status, $tid);
        
        if (!$stmt_task->execute()) {
            die("Error updating task: " . $stmt_task->error);
        }
    }
}

echo "Project and tasks updated successfully!";
 
$stmt_project->close();
$stmt_task->close();
$conn->close();

?>
