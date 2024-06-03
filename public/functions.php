<?php 
include 'database/db.php';
function calculateTimeInterval($timeIn, $timeOut) {
    $dateTimeFormat = 'H:i';
    $dateTimeIn = DateTime::createFromFormat($dateTimeFormat, $timeIn);
    $dateTimeOut = DateTime::createFromFormat($dateTimeFormat, $timeOut);
    
    // Calculate the time difference
    $timeInterval = $dateTimeOut->diff($dateTimeIn);
  
    // Format the time interval (optional)
    $hours = $timeInterval->h;
    $minutes = $timeInterval->i;
    $formattedInterval = "$hours hours $minutes minutes";
  
    // Return the formatted interval or the DateTime objects for further manipulation
    return $hours; 
}
function checkIfLate($timeIn, $shift) {
    // Define shift start times in a comparable format
    $shiftStartTimes = [
        "set_a" => "06:00",
        "set_b" => "14:00",
        "set_c" => "22:00",
    ];
    
    // Convert timeIn to a comparable format
    $timeIn = date("H:i", strtotime($timeIn));
    
    // Check shift and timeIn
    if (isset($shiftStartTimes[$shift])) {
        if ($timeIn > $shiftStartTimes[$shift]) {
            return 1;
        } else {
            return 0;
        }
    } else {
        return "invalid shift";
    }
}


function isUserAdmin($con, $email){
    $lookUp = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $lookUp);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row['type']==1){
        return true;
    }
    return false;
}
?>