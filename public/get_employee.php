<?php
include 'database/db.php';

header('Content-Type: application/json'); // Set the content type to JSON

$response = [];

if (isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];
    
    $stmt = $con->prepare("SELECT first_name, last_name, rate_hour, allowance FROM employees WHERE company_id = ?");
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $rate, $allowance);
    
    if ($stmt->fetch()) {
        $response = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'rate' => $rate,
            'allowance' => $allowance,
        ];
    } else {
        $response = [
            'error' => 'No employee found for the provided company ID'
        ];
    }
    
    $stmt->close();
} else {
    $response = [
        'error' => 'No company ID provided'
    ];
}

echo json_encode($response);
?>
