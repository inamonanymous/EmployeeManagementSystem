<?php
    include 'database/db.php';
    
    // Step 1: Retrieve the 'id' from the URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Step 2: Sanitize and validate the 'id'
    if ($id <= 0) {
        // Handle invalid or missing 'id'
        echo "Invalid 'id' parameter.";
        exit();
    }

    $delete = "DELETE FROM employees WHERE id = ?";

    if($stmt = mysqli_prepare($con, $delete)){

        mysqli_stmt_bind_param($stmt, "i", $id); // 'i' represents an integer parameter.

        if(mysqli_stmt_execute($stmt)){
            header('location: dashboard.php'); // Redirect to a confirmation page or elsewhere after deleting.
        } else {
            echo 'Error: ' . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    }