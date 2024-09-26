<?php
session_start();

// Error reporting for debugging (comment out in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file (adjust the path if needed)
require_once __DIR__ . '/config/config.php';

// Function to safely output strings, handling null values
function safeEcho($str) {
    echo htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = false;
// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'])) {
    $property_id = filter_input(INPUT_POST, 'property_id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $check_in_date = filter_input(INPUT_POST, 'check_in_date', FILTER_SANITIZE_STRING);
    $check_out_date = filter_input(INPUT_POST, 'check_out_date', FILTER_SANITIZE_STRING);
    $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if (!$property_id || !$name || !$email || !$phone || !$check_in_date || !$check_out_date || !$guests) {
        $_SESSION['error_message'] = "All fields except 'Message' are required.";
    } else {
        try {
            // Check if the property exists in the database
            $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
            $stmt->execute([$property_id]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$property) {
                throw new Exception("Property not found.");
            }
            
            // Insert reservation
            $stmt = $pdo->prepare("INSERT INTO reservations (property_id, name, email, phone, check_in_date, check_out_date, guests, message) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$property_id, $name, $email, $phone, $check_in_date, $check_out_date, $guests, $message]);
            
            $_SESSION['success_message'] = "Reservation submitted successfully!";
            $_SESSION['reservation_details'] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'check_in_date' => $check_in_date,
                'check_out_date' => $check_out_date,
                'guests' => $guests,
                'message' => $message,
                'property_title' => $property['title']
            ];
        } catch(Exception $e) {
            $_SESSION['error_message'] = "Failed to submit reservation. Error: " . $e->getMessage();
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Include header
include __DIR__ . '/templates/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Real Estate - Reserve Property</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3a5a40',
                        secondary: '#588157',
                        light: '#a7c4a0',
                        lighter: '#dad7cd',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-lighter">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-primary">Reserve Property</h1>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">Reservation submitted successfully.</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">
                    <?php echo implode('<br>', $errors); ?>
                </span>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="property_id" value="<?php safeEcho($_GET['property_id'] ?? ''); ?>">
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="name" type="text" name="name" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="email" type="email" name="email" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="phone">Phone</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="phone" type="tel" name="phone" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="check_in_date">Check-in Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="check_in_date" type="date" name="check_in_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="check_out_date">Check-out Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="check_out_date" type="date" name="check_out_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="guests">Number of Guests</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="guests" type="number" name="guests" min="1" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="message">Message (Optional)</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="message" name="message"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                    Submit Reservation
                </button>
            </div>
        </form>
    </div>

    <script>
    // You can add any necessary JavaScript here
    </script>
</body>
</html>

<?php
// Include footer
include __DIR__ . '/templates/footer.php';
?>