
<?php
  include 'header.php';
  include 'footer.php';
  require 'dbconnect.php';


  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
       
        $title = $_POST['title'];
        $description = $_POST['description'];
    
        // Insert into the first table
        $insert_project = "INSERT INTO todolist (title, description) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_project);
        $stmt->bind_param('ss', $title, $description);
    
        if ($stmt->execute()) {
            // Get the last inserted project ID
            $pid = $stmt->insert_id;
    
            // Insert tasks into the second table
            $task = $_POST['task'];
            $status = $_POST['status'];
    
            $insert_task = "INSERT INTO tasklist (pid, task, status) VALUES (?, ?, ?)";
            $stmt2 = $conn->prepare($insert_task);
            $stmt2->bind_param('iss', $pid, $task, $status);
    
            if ($stmt2->execute()) {
                echo "Data inserted successfully!";
            } else {
                echo "Error inserting task: " . $stmt2->error;
            }
    
        } else {
            echo "Error inserting project: " . $stmt->error;
        }
    
       
        $stmt->close();
        $stmt2->close();
    }
    
    
    $conn->close();


    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Todo</title>
    <link rel="stylesheet" href="style1.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
     $(document).ready(function() {
        let taskCount = 1; 
        $('#addTask').click(function() {
            
            const uniqueName = `status_${taskCount}`;
            $('#tasks').append(`
                <div class="task">
                    <input type="text" name="task[]" placeholder="Task" required>
                    <label>
                        <input type="radio" name="status[]" value="completed"> Completed
                    </label>
                    <label>
                        <input type="radio" name="status[]" value="pending" checked> Pending
                    </label>
                    <button type="button" class="removeTask">Remove</button>
                </div>
            `);
            taskCount++; 
        });

      
        $(document).on('click', '.removeTask', function() {
            $(this).parent('.task').remove();
        });
    });
</script>
</head>
<body>
    
    <main>
        <form action="" method="POST">
            <label for="project_title">Project Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            
            <div id="tasks">
                <div class="task">
                    <input type="text" name="task" placeholder="Task" required>
                    <label>
                        <input type="radio" name="status" value="completed"> Completed
                    </label>
                    <label>
                        <input type="radio" name="status" value="pending" checked> Pending
                    </label>
                    <button type="button" class="removeTask">Remove</button>
                </div>
            </div>
            
            <button type="button" id="addTask">Add Task</button> <br><br>
            <button type="submit" name="submit">Save Project</button>
        </form>
    </main>
    
   

</body>
</html>

<script>
    function validateForm() {
            var title = document.getElementById('title').value;
            if (title.length <   2 || title.length > 100) {
                alert(' Title  should be in between 2 and 100 characters.');
                return false;
            }

           

            var description = document.getElementById('description').value;
            if (description.length <   10 || description.length > 99) {
                alert(' Description should be in between 10 and 100 characters.');
                return false;
            }

            var task = document.getElementById('task').value;
            if (task.length <   3 || task.length > 99) {
                alert('Job title  should be in between 3 and 100 characters.');
                return false;
            }

            alert("Todo's Added Succesfully");
             return true; 
        }
</script>


