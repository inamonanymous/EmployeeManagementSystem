<?php
session_start();
include 'database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST["name"];
    $password = $_POST["password"];

    // Prepare a SQL statement to retrieve the admin's data
    $sql = "SELECT id, name FROM admin WHERE name = ? AND password = ?";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ss", $admin_name, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Admin name and password match, log in the user and redirect to the dashboard
            session_start();
            $_SESSION["admin_name"] = $admin_name;
            header("Location: dashboard.php");
            exit();
        } else {
            // Admin name and/or password are incorrect
            echo "Incorrect admin name and/or password. Please try again.";
        }

        $stmt->close();
    }

    $con->close();
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
    <title>Document</title>
</head>
<body class="grid place-items-center min-h-screen bg-slate-50 overflow-hidden">
    <div class="w-3/5 shadow-xl p-2 z-10 bg-white">
        <div class="px-16 pt-10
         pb-5">
            <h1 class="font-bold text-2xl"><span class="text-main">C</span>reative</h1>
        </div>
        <div class="flex w-full">
            <div class="w-2/4">
                <img src="../src/img/hero.png" alt="">
            </div>
            <div class="w-2/4">
                <div class="w-10/12 mx-auto">
                    <h1 class="text-xl font-semibold">Welcome back! </h1>
                    <p class="text-xs">Login to continue</p>
                    <div class="w-full mt-10">
                        <form action="" method="POST">
                            <div class="flex flex-col mb-3">
                                <label class="mb-1" for="">Admin name</label>
                                <input id="name" class="w-full px-2 py-1 rounded-md border outline-none" type="text" name="name">
                            </div>
                            <div class="flex flex-col mb-5">
                                <label class="mb-1" for="">Password</label>
                                <input class="w-full px-2 py-1 rounded-md border outline-none" type="password" name="password">
                            </div>
                            <div class="flex justify-between">
                                <button class="w-1/3 px-4 py-2 bg-main rounded-3xl text-white">Login</button>
                                <a href="#" class="text-blue-500">Forget password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-[1920px] h-[1920px] bg-main absolute top-[40%] rounded-[50%] -z-10 shadow-2xl"></div>
</body>
</html>