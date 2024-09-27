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

$property_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($property_id) {
    // Fetch a specific property
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$property_id]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        $_SESSION['error_message'] = "Property not found";
        header("Location: index.php");
        exit();
    }
} else {
    // Fetch all properties
    $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
    $allProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">';
    echo '<span class="block sm:inline">' . $_SESSION['success_message'] . '</span>';
    echo '</div>';
    unset($_SESSION['success_message']);
}

// Display error message if set
if (isset($_SESSION['error_message'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">';
    echo '<span class="block sm:inline">' . $_SESSION['error_message'] . '</span>';
    echo '</div>';
    unset($_SESSION['error_message']);
}



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
            // Check if the property exists
            $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
            $stmt->execute([$property_id]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$property) {
                throw new Exception("Property not found.");
            }
            
            // Insert reservation
            $stmt = $pdo->prepare("INSERT INTO reservations (property_id, name, email, phone, check_in_date, check_out_date, guests, message, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
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

// Fetch all properties from the database
try {
    $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
    $allProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Failed to fetch properties. Error: " . $e->getMessage();
}

// Include header
include __DIR__ . '/templates/header.php';

// Function to display property card
function displayPropertyCard($property, $isFeatured = false) {
    $isJustListed = (time() - strtotime($property['created_at'])) < (7 * 24 * 60 * 60); // 7 days
    ?>
    <div class="bg-[#052e16] bg-opacity-80 rounded-lg shadow-lg overflow-hidden" x-data="{ showModal: false, showReservationForm: false, showPurchaseAgreementForm: false }">
        <img src="<?php echo htmlspecialchars($property['image'] ?? 'path/to/default/image.jpg'); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-64 object-cover">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-white mb-2">
                <?php echo htmlspecialchars($property['title']); ?> 
                <span class="text-gray-300">₱<?php echo number_format($property['price'], 2); ?></span>
            </h2>
            <?php if ($isJustListed && !$isFeatured): ?>
                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold mb-2 inline-block">Just Listed</span>
            <?php endif; ?>
            <p class="text-gray-200 mb-4"><?php echo htmlspecialchars($property['description']); ?></p>
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-gray-200"><?php echo htmlspecialchars($property['city']); ?></span>
            </div>
            <div class="grid grid-cols-4 gap-2 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-sm text-gray-200"><?php echo htmlspecialchars($property['beds']); ?> beds</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    <span class="text-sm text-gray-200"><?php echo htmlspecialchars($property['baths']); ?> baths</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    <span class="text-sm text-gray-200"><?php echo number_format($property['area_sqft']); ?> sqft</span>
                </div>
                <?php if (isset($property['floors'])): ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-sm text-gray-200"><?php echo htmlspecialchars($property['floors']); ?> floors</span>
                </div>
                <?php endif; ?>
            </div>
            <button @click="showModal = true" class="w-full bg-white text-[#052e16] px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition duration-150 ease-in-out">View Details</button>
        </div>


        <!-- Property Details Modal -->
        <div x-show="showModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-2xl font-bold text-primary mb-4" id="modal-title"><?php echo htmlspecialchars($property['title']); ?></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <img src="<?php echo htmlspecialchars($property['image'] ?? 'path/to/default/image.jpg'); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-80 object-cover rounded-lg">
                            </div>
                            <div>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($property['description']); ?></p>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        <span class="text-gray-600">Bedrooms: <?php echo htmlspecialchars($property['beds']); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        <span class="text-gray-600">Bathrooms: <?php echo htmlspecialchars($property['baths']); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                        <span class="text-gray-600">Square Feet: <?php echo number_format($property['area_sqft']); ?></span>
                                    </div>
                                    <?php if (isset($property['floors'])): ?>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            <span class="text-gray-600">Floors: <?php echo htmlspecialchars($property['floors']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-gray-600">Location: <?php echo htmlspecialchars($property['city']); ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($property['features'])): ?>
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-primary mb-2">Features:</h4>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($property['features']); ?></p>
                                </div>
                                <?php endif; ?>
                                <div class="flex flex-col sm:flex-row justify-between items-center">
                                    <span class="text-primary font-bold text-2xl mb-4 sm:mb-0">₱<?php echo number_format($property['price'], 2); ?></span>
                                    <?php if ($isFeatured): ?>
                                        <button @click="showPurchaseAgreementForm = true; showModal = false" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">Purchase Agreement</button>
                                    <?php else: ?>
                                        <button @click="showReservationForm = true; showModal = false" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">Reserve Now</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary text-base font-medium text-white hover:bg-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Form Modal -->
        <div x-show="showReservationForm" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showReservationForm = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Reserve <?php echo htmlspecialchars($property['title']); ?></h3>
                        <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" name="phone" id="phone" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="check_in_date" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                                <input type="date" name="check_in_date" id="check_in_date" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="check_out_date" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                                <input type="date" name="check_out_date" id="check_out_date" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="guests" class="block text-sm font-medium text-gray-700">Number of Guests</label>
                            <input type="number" name="guests" id="guests" required min="1" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700">Message (Optional)</label>
                            <textarea name="message" id="message" rows="3" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:col-start-2 sm:text-sm">
                                Submit Reservation
                            </button>
                            <button @click="showReservationForm = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:col-start-1 sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

      <!-- Purchase Agreement Form Modal -->
<div x-show="showPurchaseAgreementForm" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showPurchaseAgreementForm = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="purchase_agreement.php" method="POST" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Submit Purchase Agreement for <?php echo htmlspecialchars($property['title']); ?></h3>
                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                <div class="mb-4">
                    <label for="buyer_name" class="block text-sm font-medium text-gray-700">Buyer Name</label>
                    <input type="text" name="buyer_name" id="buyer_name" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="buyer_email" class="block text-sm font-medium text-gray-700">Buyer Email</label>
                    <input type="email" name="buyer_email" id="buyer_email" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="buyer_phone" class="block text-sm font-medium text-gray-700">Buyer Phone</label>
                    <input type="tel" name="buyer_phone" id="buyer_phone" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700">Purchase Price</label>
                    <input type="number" name="purchase_price" id="purchase_price" required step="0.01" value="<?php echo $property['price']; ?>" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="closing_date" class="block text-sm font-medium text-gray-700">Closing Date</label>
                    <input type="date" name="closing_date" id="closing_date" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="contingencies" class="block text-sm font-medium text-gray-700">Contingencies (Optional)</label>
                    <textarea name="contingencies" id="contingencies" rows="3" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:col-start-2 sm:text-sm">
                        Submit Purchase Agreement
                    </button>
                    <button @click="showPurchaseAgreementForm = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
          
    </div>
<?php
}
?>

<main>
    <div class="relative bg-cover bg-center bg-fixed" style="background-image: url('/uploads/propertiescover.jpg');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative z-10">
            <!-- Hero Section with integrated "Why Choose Us" -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center mb-16">
                    <h1 class="text-5xl font-bold text-white mb-6">Discover Your Dream Home</h1>
                </div>

                <div class="text-center mb-12">
                    <p class="text-xl text-white">We offer a premium real estate experience tailored to your needs</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-[#052e16] bg-opacity-80 p-6 rounded-lg shadow-md">
                        <svg class="w-12 h-12 text-white mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-xl font-semibold mb-2 text-center text-white">Curated Selection</h3>
                        <p class="text-gray-200 text-center">We handpick only the finest properties to ensure quality and value</p>
                    </div>
                    <div class="bg-[#052e16] bg-opacity-80 p-6 rounded-lg shadow-md">
                        <svg class="w-12 h-12 text-white mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <h3 class="text-xl font-semibold mb-2 text-center text-white">Fast & Efficient</h3>
                        <p class="text-gray-200 text-center">Our streamlined process ensures a smooth and quick transaction</p>
                    </div>
                    <div class="bg-[#052e16] bg-opacity-80 p-6 rounded-lg shadow-md">
                        <svg class="w-12 h-12 text-white mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <h3 class="text-xl font-semibold mb-2 text-center text-white">Expert Support</h3>
                        <p class="text-gray-200 text-center">Our team of professionals is always ready to assist you</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const details = urlParams.get('details');
    const status = urlParams.get('status');

    if (message) {
        showMessage(status, message, details ? JSON.parse(details) : null);
    }
});

function showMessage(type, message, details = null) {
    var overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50';
    
    var content = document.createElement('div');
    content.className = 'bg-white p-8 rounded-lg shadow-xl max-w-md w-full';
    
    var title = document.createElement('h2');
    title.className = 'text-2xl font-bold mb-4';
    title.textContent = type === 'success' ? 'Purchase Agreement Submitted Successfully!' : 'Error';
    title.style.color = type === 'success' ? '#1a2e05' : '#e53e3e';
    
    var text = document.createElement('p');
    text.className = 'text-gray-600 mb-4';
    text.textContent = message;
    
    content.appendChild(title);
    content.appendChild(text);
    
    if (type === 'success' && details) {
        var detailsTitle = document.createElement('h3');
        detailsTitle.className = 'text-lg font-semibold mb-2';
        detailsTitle.textContent = 'Agreement Details:';
        detailsTitle.style.color = '#3f6212';
        
        var detailsList = document.createElement('ul');
        detailsList.className = 'list-disc pl-5';
        
        for (var key in details) {
            var item = document.createElement('li');
            item.textContent = key + ': ' + details[key];
            detailsList.appendChild(item);
        }
        
        content.appendChild(detailsTitle);
        content.appendChild(detailsList);
    }
    
    overlay.appendChild(content);
    document.body.appendChild(overlay);
    
    setTimeout(function() {
        document.body.removeChild(overlay);
    }, 7000);
}
</script>
        
        <!-- Exclusive Properties Section -->
        <div class="bg-gray-100">
        <section id="featured" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-5xl font-bold mb-6 text-[#052e16] text-center">Exclusive Properties</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        <?php 
            $featuredProperties = [
                
            [
                'id' => 1,
                'title' => 'Grand Villa Retreat', // Updated title for the first property
                'description' => 'A stunning villa with breathtaking views and top-of-the-line amenities.',
                'price' => 1500000,
                'beds' => 4,
                'baths' => 3,
                'floors' => 2,
                'area_sqft' => 3200,
                'image' => 'uploads/property9.jpg',
                'city' => 'Makati City',
                'features' => 'Swimming Pool, Gym, Garden, Smart Home System',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Contemporary Condo Suite', // Updated title for the second property
                'description' => 'Experience the pinnacle of urban living in this sleek, stunning views.',
                'price' => 800000,
                'beds' => 2,
                'baths' => 2,
                'floors' => 2,
                'area_sqft' => 1200,
                'image' => 'uploads/property8.jpg',
                'city' => 'Bonifacio Global City',
                'features' => 'Rooftop Lounge, Fitness Center, Concierge Service',
                'created_at' => date('Y-m-d H:i:s'),
            ],

                [
                    'id' => 3,
                    'title' => 'Exquisite Mansion',
                    'description' => 'Indulge in luxury living with this exquisite villa. From its awe-inspiring views.',
                    'price' => 1500000,
                    'beds' => 4,
                    'baths' => 3,
                    'floors' => 2,
                    'area_sqft' => 3200,
                    'image' => 'uploads/property7.jpg',
                    'city' => 'Makati City',
                    'features' => 'Swimming Pool, Gym, Garden, Smart Home System',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'title' => 'Urban Loft Retreat',
                    'description' => 'Discover the epitome of luxury in this stunning villa.',
                    'price' => 1500000,
                    'beds' => 4,
                    'baths' => 3,
                    'floors' => 2,
                    'area_sqft' => 3200,
                    'image' => 'uploads/property12.jpg',
                    'city' => 'Makati City',
                    'features' => 'Swimming Pool, Gym, Garden, Smart Home System',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'title' => 'Elegant Penthouse Suite',
                    'description' => 'Sleek and stylish condo in the heart of the city with stunning skyline views.',
                    'price' => 800000,
                    'beds' => 2,
                    'baths' => 2,
                    'floors' => 2,
                    'area_sqft' => 1200,
                    'image' => 'uploads/property4.jpg',
                    'city' => 'Bonifacio Global City',
                    'features' => 'Rooftop Lounge, Fitness Center, Concierge Service',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 6,
                    'title' => 'Chic City Apartment',
                    'description' => 'Immerse yourself in urban sophistication with this contemporary condo.',
                    'price' => 800000,
                    'beds' => 2,
                    'baths' => 2,
                    'floors' => 2,
                    'area_sqft' => 1200,
                    'image' => 'uploads/property10.jpg',
                    'city' => 'Bonifacio Global City',
                    'features' => 'Rooftop Lounge, Fitness Center, Concierge Service',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 7,
                    'title' => 'Contemporary Townhouse',
                    'description' => 'Experience opulence in this magnificent villa featuring panoramic vistas and state-of-the-art facilities. Perfect for those seeking a lavish lifestyle in a prime location.',
                    'price' => 800000,
                    'beds' => 2,
                    'baths' => 2,
                    'floors' => 2,
                    'area_sqft' => 1200,
                    'image' => 'uploads/property14.png',
                    'city' => 'Bonifacio Global City',
                    'features' => 'Rooftop Lounge, Fitness Center, Concierge Service',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 8,
                    'title' => 'Exclusive Sky Residence',
                    'description' => 'Elevate your lifestyle in this magnificent villa. From its awe-inspiring views to its premium features, every aspect is crafted to deliver an extraordinary living experience.',
                    'price' => 1500000,
                    'beds' => 4,
                    'baths' => 3,
                    'floors' => 2,
                    'area_sqft' => 3200,
                    'image' => 'uploads/property15.png',
                    'city' => 'Makati City',
                    'features' => 'Swimming Pool, Gym, Garden, Smart Home System',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 9,
                    'title' => 'Serene Garden Estate',
                    'description' => 'Immerse yourself in the epitome of urban sophistication with this modern condo. Enjoy panoramic city views and premium amenities in a highly sought-after location.',
                    'price' => 800000,
                    'beds' => 2,
                    'baths' => 2,
                    'floors' => 2,
                    'area_sqft' => 1200,
                    'image' => 'uploads/property16.png',
                    'city' => 'Bonifacio Global City',
                    'features' => 'Rooftop Lounge, Fitness Center, Concierge Service',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
    
   
                ];

    
                foreach ($featuredProperties as $property) {
                displayPropertyCard($property, true);
            }
            ?>
        </div>
        
           <!-- All Listed Properties Section -->
           <section id="all-properties" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-5xl font-bold mb-6 text-[#052e16] text-center">All Listed Properties</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-11">
            <?php
            // Fetch all properties from the database
            try {
                $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
                $allProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo '<p class="col-span-3 text-center text-red-600">Error fetching properties: ' . $e->getMessage() . '</p>';
            }

            if (!empty($allProperties)) {
                foreach ($allProperties as $property) {
                    displayPropertyCard($property);
                }
            } else {
                echo '<p class="col-span-3 text-center text-gray-600">No properties found.</p>';
            }
            ?>
        </div>
    </section>
</main>
<?php
// Include footer
include __DIR__ . '/templates/footer.php';
?>