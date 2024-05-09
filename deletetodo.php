<?php
// Database connection details

// Create a connection
require 'dbconnect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the 'pid' parameter from the GET request
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

if ($pid > 0) {
    // Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Delete from 'tasklist'
        $delete_tasklist = "DELETE FROM tasklist WHERE pid = ?";
        $stmt1 = $conn->prepare($delete_tasklist);
        $stmt1->bind_param('i', $pid);
        $stmt1->execute();
        
        // Delete from 'todolist'
        $delete_todolist = "DELETE FROM todolist WHERE pid = ?";
        $stmt2 = $conn->prepare($delete_todolist);
        $stmt2->bind_param('i', $pid);
        $stmt2->execute();
        
        // If everything is successful, commit the transaction
        $conn->commit();
        
        echo "Record with pid = $pid has been deleted successfully.";
        
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "Error deleting record: " . $e->getMessage();
    }

    // Close prepared statements
    $stmt1->close();
    $stmt2->close();
} else {
    echo "Invalid 'pid' parameter.";
}

// Close the connection
$conn->close();
