<?php
    include 'header.php';
    include 'footer.php';
    require 'dbconnect.php';

    // Fetch project ID from the URL parameter
    $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

    // Fetch project details
    $sql_project = "SELECT title, description FROM todolist WHERE pid = ?";
    $stmt_project = $conn->prepare($sql_project);
    $stmt_project->bind_param("i", $pid);
    $stmt_project->execute();
    $project = $stmt_project->get_result()->fetch_assoc();

    // Fetch associated tasks
    $sql_tasks = "SELECT tid, task, status FROM tasklist WHERE pid = ?";
    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("i", $pid);
    $stmt_tasks->execute();
    $tasks = $stmt_tasks->get_result();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Project</title>
        <link rel="stylesheet" type="text/css" href="style1.css"> <!-- Link to CSS file -->
    </head>
    <body>
        <h1>Edit Project</h1>
        
        <form action="update.php" method="POST">
           
            <input type="hidden" name="pid" value="<?php echo $pid; ?>">
            
        
            <label for="title">Project Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($project['description']); ?></textarea>
            
            <div id="tasks">
                <?php while ($task = $tasks->fetch_assoc()): ?>
                    <div class="task">
                        
                    Task <br> <input type="hidden" name="tid[]" value="<?php echo $task['tid']; ?>">
                        
                        <input type="text" name="task[]" value="<?php echo htmlspecialchars($task['task']); ?>" required>
                        
                        
                        <label>
                            <input type="radio" name="status_<?php echo $task['tid']; ?>" value="completed" 
                                <?php if ($task['status'] == 'completed') echo 'checked'; ?>> Completed
                        </label>
                        
                        <label>
                            <input type="radio" name="status_<?php echo $task['tid']; ?>" value="pending" 
                                <?php if ($task['status'] == 'pending') echo 'checked'; ?>> Pending
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
            
        
            <button type="submit" name="submit">Update Project</button>
        </form>
        
    
        <a href="index.php">Back to Project List</a>
        
    
        <?php $conn->close(); ?>
    </body>
</html>