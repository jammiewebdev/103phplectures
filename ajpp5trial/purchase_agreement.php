<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'rs_webapp_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Handle purchase agreement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST request received in purchase_agreement.php");
    error_log("POST data: " . print_r($_POST, true));

    $buyer_name = sanitizeInput($_POST['buyer_name'] ?? '');
    $buyer_email = filter_input(INPUT_POST, 'buyer_email', FILTER_VALIDATE_EMAIL);
    $buyer_phone = sanitizeInput($_POST['buyer_phone'] ?? '');
    $purchase_price = filter_input(INPUT_POST, 'purchase_price', FILTER_VALIDATE_FLOAT);
    $closing_date = sanitizeInput($_POST['closing_date'] ?? '');
    $contingencies = sanitizeInput($_POST['contingencies'] ?? '');

    // Validate inputs
    $errors = [];
    if (empty($buyer_name)) {
        $errors[] = "Buyer name is required.";
    }
    if (!$buyer_email) {
        $errors[] = "Valid buyer email is required.";
    }
    if (empty($buyer_phone)) {
        $errors[] = "Buyer phone is required.";
    }
    if (!$purchase_price) {
        $errors[] = "Valid purchase price is required.";
    }
    if (empty($closing_date) || !strtotime($closing_date)) {
        $errors[] = "Valid closing date is required.";
    }

    if (empty($errors)) {
        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Insert purchase agreement
            $stmt = $pdo->prepare("INSERT INTO purchase_agreements (buyer_name, buyer_email, buyer_phone, purchase_price, closing_date, contingencies, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
            $result = $stmt->execute([$buyer_name, $buyer_email, $buyer_phone, $purchase_price, $closing_date, $contingencies]);
            
            if (!$result) {
                throw new Exception("Failed to insert purchase agreement: " . implode(", ", $stmt->errorInfo()));
            }

            // Commit transaction
            $pdo->commit();

            $message = "Purchase Agreement submitted successfully!";
            $details = json_encode([
                'buyer_name' => $buyer_name,
                'buyer_email' => $buyer_email, 
                'buyer_phone' => $buyer_phone,
                'purchase_price' => $purchase_price,
                'closing_date' => $closing_date,
                'contingencies' => $contingencies
            ]);

            error_log("Purchase agreement submitted successfully");
            
            // Redirect with success message
            header("Location: properties.php?message=" . urlencode($message) . "&details=" . urlencode($details) . "&status=success");
            exit();
        } catch(Exception $e) {
            // Rollback transaction on error, only if a transaction is active
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Failed to submit Purchase Agreement. Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Redirect with error message
            $error_message = "Failed to submit Purchase Agreement. Error: " . $e->getMessage();
            header("Location: properties.php?message=" . urlencode($error_message) . "&status=error");
            exit();
        }
    } else {
        $error_message = "Please correct the following errors: " . implode(" ", $errors);
        error_log("Validation errors: " . implode(" ", $errors));
        
        // Redirect with validation errors
        header("Location: properties.php?message=" . urlencode($error_message) . "&status=error");
        exit();
    }
}

// If the script reaches here, it means the request method was not POST
$error_message = "Invalid request.";
error_log("Invalid request to purchase_agreement.php");
header("Location: properties.php?message=" . urlencode($error_message) . "&status=error");
exit();
?>