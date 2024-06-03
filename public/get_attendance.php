<?php
include 'database/db.php';

header('Content-Type: application/json'); // Set the content type to JSON

$response = [];

if (isset($_POST['employee_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    $stmt = $con->prepare("SELECT * FROM attendance WHERE c_id = ? AND curr_date BETWEEN ? AND ? AND is_paid = 0");
    $stmt->bind_param("sss", $employee_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $attendances = [];
    $holidays = 0;
    $lates = 0;
    $total_hours = 0;
    while ($row = $result->fetch_assoc()) {
        $attendances[] = $row;
        $total_hours += $row['hours'];
        if ($row['is_holiday']==1){
            $holidays++;
        }
        if ($row['is_late']==1){
            $lates++;
        }
    }
    
    if (!empty($attendances)) {
        $response = [
            'attendances' => $attendances,
            'total_hours' => $total_hours,
            'holidays' => $holidays,
            'lates' => $lates,
            'c_id' => $employee_id
        ];
    } else {
        $response = [
            'error' => 'No attendance records found for the provided employee ID and date range'
        ];
    }
    
    $stmt->close();
} else {
    $response = [
        'error' => 'Employee ID, start date, or end date not provided'
    ];
}

echo json_encode($response);
?>
