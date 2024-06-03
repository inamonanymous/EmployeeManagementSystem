<?php
session_start();
include 'database/db.php';

if (isset($_SESSION["user_email"])) {
    $user_email = $_SESSION["user_email"];
    $user_name = $_SESSION["user_name"];
}
include 'functions.php';
$sql = "SELECT * FROM employees";
$query = mysqli_query($con, $sql);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add'])) {
            $c_id = $_POST['c_id'];
            $time_in = $_POST['time_in'];
            $time_out = $_POST['time_out'];
            $shift = $_POST['shift'];

            // Prepare the SQL statement
            $stmt = $con->prepare("INSERT INTO attendance (c_id, time_in, time_out, hours, is_late) VALUES (?, ?, ?, ?, ?)");
            $interval = calculateTimeInterval($time_in, $time_out);
            
            $is_late = checkIfLate($time_in, $shift);
            
            if ($stmt === false) {
                throw new Exception("Error preparing the statement: " . $con->error);
            }

            // Bind parameters to the statement
             $stmt->bind_param("sssss", $c_id, $time_in, $time_out, $interval, $is_late);

            // Execute the statement
             if ($stmt->execute()) {
                header("Location: attendance.php");
                echo "New record created successfully";
            } else {
                throw new Exception("Error executing the statement: " . $stmt->error);
            } 

            $stmt->close(); 
        }
    } catch (Exception $e) {
        $error_message = "Company ID: undefined!";
        echo '<script> alert(' . json_encode($error_message) . '); window.location.href="attendance_add.php";</script>';
        echo '<script> alert(' . json_encode($e->getMessage()) . '); window.location.href="attendance_add.php";</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
                <h1 class="mb-10 text-lg font-semibold">Add Attendance</h1>
                <div class="w-full flex flex-col gap-16 wrap">
                    <form action="" class="w-full" method="POST">
                        <div class="w-full mb-10">
                            <div class="w-full flex gap-10 mb-5 wrap">
                                <div class="w-full flex items-center gap-5">
                                    <label for="" class="">Company ID</label>
                                    <select name="c_id" class="px-1 py-2 w-1/3" onchange="populatePayrollFields(this)">
                                        <option selected disabled>-Select Value-</option>
                                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                            <option value="<?php echo htmlspecialchars($row['company_id']); ?>"><?php echo htmlspecialchars($row['company_id']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="w-full flex items-center gap-5">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="px-1 py-2 w-full" readonly>
                                </div>
                                <div class="w-full flex items-center gap-5">
                                    <label for="" class="">Select time shift</label>
                                        <select name="shift" class="px-1 py-2 w-1/3"">
                                            <option selected disabled>-Select Value-</option>
                                            <option value="set_a">6am to 2pm</option>
                                            <option value="set_b">2pm to 10pm</option>
                                            <option value="set_c">10pm to 6am</option>
                                        </select>
                                </div>
                                <div class="w-full flex items-center gap-5">
                                    <p class="text-center font-semibold">Time In</p>
                                    <div class="flex items-center gap-5 text-sm">
                                        <label for="time">Enter Time (HH:MM:SS):</label>
                                        <input type="time" id="time_in" name="time_in" required>
                                    </div>
                                </div>
                                <div class="w-full flex items-center gap-5">
                                    <p class="text-center font-semibold">Time Out</p>
                                    <div class="flex items-center gap-5 text-sm">
                                        <label for="time">Enter Time (HH:MM:SS):</label>
                                        <input type="time" id="time_out" name="time_out" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="flex gap-5">
                                <button name="add" class="px-6 py-1 border border-main-2 bg-main-2 text-white rounded-md">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="script.js"></script>
</body>
</html>