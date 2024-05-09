<?php
// Include necessary configuration for database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get the project ID
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;

    // Update the project title and description
    $title = $_POST['title'];
    $description = $_POST['description'];

    $update_project = "UPDATE todolist SET title = ?, description = ? WHERE pid = ?";
    $stmt = $conn->prepare($update_project);
    $stmt->bind_param('ssi', $title, $description, $pid);
    
    if ($stmt->execute()) {
        // Remove existing tasks to allow a clean update
        $delete_tasks = "DELETE FROM tasklist WHERE pid = ?";
        $stmt2 = $conn->prepare($delete_tasks);
        $stmt2->bind_param('i', $pid);
        $stmt2->execute();
        
        // Add new tasks to the tasklist
        $tasks = $_POST['task'];
        $statuses = $_POST['status'];

        $insert_task = "INSERT INTO tasklist (pid, task, status) VALUES (?, ?, ?)";
        $stmt3 = $conn->prepare($insert_task);
        
        foreach ($tasks as $index => $task) {
            $status = $statuses[$index];
            $stmt3->bind_param('iss', $pid, $task, $status);
            $stmt3->execute();
        }
        
        echo "Project and tasks updated successfully!";
    } else {
        echo "Error updating project: " . $stmt->error;
    }

    // Close the prepared statements
    $stmt->close();
    $stmt2->close();
    $stmt3->close();
}

// Close the connection
$conn->close();
