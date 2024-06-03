<?php
    session_start();
    include 'database/db.php';
    include 'functions.php';
    if (isset($_SESSION["user_email"])) {
        $user_email = $_SESSION["user_email"];
        $user_name = $_SESSION["user_name"];
    }

    // Step 1: Retrieve the 'id' from the URL
    $c_id = isset($_GET['c_id']) ? intval($_GET['c_id']) : 0;
    $lookUp = "SELECT * FROM employees WHERE company_id = " . $c_id;
    $lookUpQuery = mysqli_query($con, $lookUp);
    $row = mysqli_fetch_assoc($lookUpQuery);
    
    // Step 2: Sanitize and validate the 'id'
    if ($c_id <= 0) {
        // Handle invalid or missing 'id'
        echo "Invalid 'id' parameter.";
        exit();
    }

    if(isset($_POST['update'])){
        $update = "UPDATE employees SET first_name = ?, last_name = ?, email = ?, position = ?, number = ?, department = ? WHERE id = ?";
    
        if($stmt = mysqli_prepare($con, $update)){
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $position = $_POST['job_position'];
            $number = $_POST['phone'];
            $department = $_POST['department'];
    
            mysqli_stmt_bind_param($stmt, "ssssssi", $first_name, $last_name, $email, $position, $number, $department, $id);
    
            if(mysqli_stmt_execute($stmt)){
                header('location: edit.php?id=' . $id); // Redirect to the employee's edit page after updating.
            } else {
                echo 'Error: ' . mysqli_error($con);
            }
    
            mysqli_stmt_close($stmt);
        }
    }
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/5bf9be4e76.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body class="grid place-items-center min-h-screen bg-main-2">
    <div class="w-11/12 flex bg-white shadow-2xl rounded-lg">
        <div class="w-[20%]">
        <div class="flex flex-col items-center py-5 mb-10">
                <img src="../src/img/dp.jpg" alt="" width="130" height="130" class="rounded-[50%] mb-2 bg-main">
                <div class="flex items-center gap-2">
                    <p class="text-lg font-semibold"><?php echo $user_name ?></p>
                    <button id="toggle" onclick="toggleLogout()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>
                    </button>
                </div>
                <button onclick="window.location.href='logout.php'" id="logout" class="w-2/4 bg-slate-500 text-white rounded-md text-sm py-1 hidden">Logout</button>   
            </div>
            <div class="flex flex-col mb-48">
                <button onclick="window.location.href='dashboard.php'" class="w-full text-left flex items-center gap-2 hover:bg-main-2 hover:text-white px-4 py-3 transition-all"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L512 185V64c0-17.7-14.3-32-32-32H448c-17.7 0-32 14.3-32 32v36.7L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32v69.7c-.1 .9-.1 1.8-.1 2.8V472c0 22.1 17.9 40 40 40h16c1.2 0 2.4-.1 3.6-.2c1.5 .1 3 .2 4.5 .2H160h24c22.1 0 40-17.9 40-40V448 384c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v64 24c0 22.1 17.9 40 40 40h24 32.5c1.4 0 2.8 0 4.2-.1c1.1 .1 2.2 .1 3.3 .1h16c22.1 0 40-17.9 40-40V455.8c.3-2.6 .5-5.3 .5-8.1l-.7-160.2h32z"/></svg><p>Home</p></button>
                <button onclick="window.location.href='attendance.php'" class="w-full text-left flex items-center gap-2 hover:bg-main-2 hover:text-white px-4 py-3 transition-all"><svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg><p>Attendance</p></button>
                <button onclick="window.location.href='add_employee.php'" class="w-full text-left flex items-center gap-2 hover:bg-main-2 hover:text-white px-4 py-3 transition-all"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M184 48H328c4.4 0 8 3.6 8 8V96H176V56c0-4.4 3.6-8 8-8zm-56 8V96H64C28.7 96 0 124.7 0 160v96H192 320 512V160c0-35.3-28.7-64-64-64H384V56c0-30.9-25.1-56-56-56H184c-30.9 0-56 25.1-56 56zM512 288H320v32c0 17.7-14.3 32-32 32H224c-17.7 0-32-14.3-32-32V288H0V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V288z"/></svg><p>Employees</p></button>
                <button onclick="window.location.href='payroll.php'" class="w-full text-left flex items-center gap-2 hover:bg-main-2 hover:text-white px-4 py-3 transition-all"><svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg><p>Payroll</p></button>
                <?php if (isUserAdmin($con, $user_email)==1) { ?>
                <button onclick="window.location.href='users.php'" class="w-full text-left flex items-center gap-2 hover:bg-main-2 hover:text-white px-4 py-3 transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/></svg><p>Users</p></button>
                <?php } ?>
            </div>
            <div class="px-4 py-6">
                <p class=" text-sm">Need help?</p>
                <a href="#" class="text-sm">companyname@gmail.com</a>
            </div>
        </div>
        <div class="bg-slate-100 rounded-tr-lg rounded-br-lg w-[80%]">
            <div class="w-11/12 mx-auto mt-10">
                <h1 class="mb-4 text-lg font-semibold">Employee Data</h1>
                <div class="w-full flex gap-5 text-sm">
                    <div class="w-2/3">
                        <div class="w-full mb-3 flex gap-3">
                            <div class="w-1/2">
                                <p class="mb-3">Department</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['department']; ?></p>
                            </div>
                            <div class="w-1/2">
                                <p class="mb-3">Company ID</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['company_id']; ?></p>
                            </div>
                        </div>
                        <div class="w-full mb-3 flex gap-3">
                            <div class="w-1/2">
                                <p class="mb-3">Rate per Hour</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['rate_hour']; ?></p>
                            </div>
                            <div class="w-1/2">
                                <p class="mb-3">Allowance</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['allowance']; ?></p>
                            </div>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="w-2/4">
                                <p class="mb-3">First Name</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['first_name'];?></p>
                            </div>
                            <div class="w-2/4">
                                <p class="mb-3">Last Name</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['last_name'];?></p>
                            </div>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="w-2/4">
                                <p class="mb-3">Age</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['age'];?></p>
                            </div>
                            <div class="w-2/4">
                                <p class="mb-3">Gender</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['sex'];?></p>
                            </div>
                        </div>
                        <div class="w-full mb-3">
                            <p class="mb-3">Address</p>
                            <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['address']; ?></p>
                        </div>
                        <div class="w-full mb-3">
                            <p class="mb-3">Email Address</p>
                            <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['email']; ?></p>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="w-2/4">
                                <p class="mb-3">Job Position</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['position'];?></p>
                            </div>
                            <div class="w-2/4">
                                <p class="mb-3">Mobile Number</p>
                                <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['number'];?></p>
                            </div>
                        </div>
                    </div>
                    <div class="w-2/6 grid place-items-center min-h-fit">
                        <img src="../src/img/dp.jpg" alt="">
                    </div>
                </div>
                <div class="w-full flex justify-between gap-4">
                    <div class="w-2/6">
                        <p class="mb-3">SSS</p>
                        <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['sss'];?></p>
                    </div>
                    <div class="w-2/6">
                        <p class="mb-3">Pag-IBIG</p>
                        <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['pagibig'];?></p>
                    </div>
                    <div class="w-2/6">
                        <p class="mb-3">PhilHealth</p>
                        <p class="w-full bg-white px-1 py-2 text-center font-semibold"><?php echo $row['philhealth'];?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>