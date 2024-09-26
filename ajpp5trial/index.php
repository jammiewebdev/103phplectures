<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the configuration file
$configFile = __DIR__ . '/config/config.php';
if (file_exists($configFile)) {
    require_once $configFile;
} else {
    die("Configuration file not found: $configFile");
}

// Include the functions file
$functionsFile = __DIR__ . '/functions/functions.php';
if (file_exists($functionsFile)) {
    require_once $functionsFile;
} else {
    die("Functions file not found: $functionsFile");
}


// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'])) {
    $property_id = $_POST['property_id'];

    // You should add more validation and sanitization here

    $result = bookAppointment($property_id);

    if ($result === true) {
        $success_message = "Reservation submitted successfully!";
    } else {
        $error_message = "Failed to submit reservation. Error: " . $result;
    }
}

// Fetch all properties from the database
$allProperties = getProperties();



// Handle booking form submission
$booking_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $appointment_date = filter_input(INPUT_POST, 'appointment_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($name && $email && $appointment_date) {
        $result = bookAppointment($name, $email, $phone, $appointment_date, $notes);
        
        if ($result === true) {
            $booking_message = "Appointment booked successfully!";
        } else {
            $booking_message = $result;
        }
    } else {
        $booking_message = "Invalid input. Please check your details and try again.";
    }
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}



// Include header
include __DIR__ . '/templates/header.php';
?>




<!-- Homepage Hero Section -->
<div class="relative h-screen bg-cover bg-center" style="background-image: url('uploads/homepage2.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center">
        <div class="md:w-1/2 p-8 rounded-lg text-center">
            <h2 class="text-6xl font-bold mb-11 text-white">Ready to Find Your Dream Home?</h2>
        </div>
        <div class="text-center p-8 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-lg">
            <h1 class="text-3xl font-bold text-white mb-4">Find your dream Home</h1>
            <p class="text-2xl text-white">Your Key to Perfect Living</p>
            <a href="properties.php" class="mt-6 inline-block bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition duration-300">Explore Properties</a>
        </div>
    </div>
</div>

<main class="container mx-auto px-4 py-16">
    <!-- Booking success/error messages -->
    <?php if ($booking_message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($booking_message); ?></span>
        </div>
    <?php endif; ?>

    <!-- Featured Properties Section -->
    <h2 id="featured-properties" class="text-6xl font-bold mb-12 text-center text-primary">Featured Properties</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <?php
        $featuredProperties = [
            ['id' => 1, 'title' => 'Luxury Penthouse', 'description' => 'Experience the pinnacle of urban living with panoramic city views', 'beds' => 3, 'baths' => 3, 'features' => ['Pool'], 'image' => 'uploads/featureproperty1.png', 'label' => 'Just Listed', 'sqft' => 2500, 'floors' => 2],
            ['id' => 2, 'title' => 'Modern House', 'description' => 'Sleek design meets comfort in this state-of-the-art family home', 'beds' => 3, 'baths' => 2, 'features' => [], 'image' => 'uploads/featureproperty2.png', 'label' => 'New Listing', 'sqft' => 2200, 'floors' => 2],
            ['id' => 3, 'title' => 'Stylish House', 'description' => 'Perfect urban retreat for singles or couples in the heart of the city', 'beds' => 1, 'baths' => 1, 'features' => [], 'image' => 'uploads/featureproperty3.png', 'label' => 'Hot Deal', 'sqft' => 800, 'floors' => 2],
        ];
        foreach ($featuredProperties as $property):
        ?>
        <div class="relative rounded-lg overflow-hidden shadow-lg group">
            <div class="absolute top-4 left-4 z-10">
                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg transform -rotate-2 hover:rotate-0 transition duration-300"><?php echo htmlspecialchars($property['label']); ?></span>
            </div>
            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-80 object-cover transition duration-300 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h3>
                <p class="text-sm mb-2"><?php echo htmlspecialchars($property['description']); ?></p>
                <div class="flex flex-wrap items-center space-x-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span><?php echo htmlspecialchars($property['beds']); ?> Beds</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                        <span><?php echo htmlspecialchars($property['baths']); ?> Baths</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                        <span><?php echo htmlspecialchars($property['sqft']); ?> sqft</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span><?php echo htmlspecialchars($property['floors']); ?> Floor<?php echo $property['floors'] > 1 ? 's' : ''; ?></span>
                    </div>
                    <?php foreach ($property['features'] as $feature): ?>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span><?php echo htmlspecialchars($feature); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button onclick="openBookingModal()" class="absolute bottom-4 right-4 text-xs text-white hover:text-secondary underline">
                Book Now
            </button>
            <button onclick="openFeaturedImageModal('<?php echo htmlspecialchars($property['image']); ?>')" class="absolute top-0 right-0 bg-black bg-opacity-50 text-white px-2 py-1 text-xs font-semibold uppercase tracking-wider hover:bg-opacity-75 transition duration-300">
                View Image
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="bookingForm" method="POST" action="">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Book Appointment</h3>
                    <div class="mt-2">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" id="phone" name="phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                            <input type="datetime-local" id="appointment_date" name="appointment_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" name="book_appointment" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Book Now
                    </button>
                    <button type="button" onclick="closeBookingModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Featured Image Modal -->
<div id="featuredImageModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:max-h-screen-75 sm:overflow-y-auto">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-center">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <img id="featuredImage" src="" alt="Featured Property" class="w-full h-auto">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
    <button type="button" onclick="closeFeaturedImageModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
        Close
    </button>
</div>
        </div>
    </div>
</div>
<script>
function openBookingModal() {
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function openFeaturedImageModal(imageSrc) {
    document.getElementById('featuredImage').src = imageSrc;
    document.getElementById('featuredImageModal').classList.remove('hidden');
}

function closeFeaturedImageModal() {
    document.getElementById('featuredImageModal').classList.add('hidden');
}
</script>
<?php
// Include footer
include __DIR__ . '/templates/footer.php';
?>


                