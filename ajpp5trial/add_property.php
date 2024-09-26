<?php
// Error reporting for debugging (comment out in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'rs_webapp_db';
$username = 'root';
$password = '';

// Database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to safely output strings, handling null values
function safeEcho($str) {
    echo htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Simulate user authentication (replace with actual authentication in production)
$user_role = 'admin'; // Change this to 'buyer' or 'seller' to test different roles

$errors = [];
$success = false;
$properties = [];

// Handle property addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $property_type = trim($_POST['property_type'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $beds = filter_var($_POST['beds'] ?? null, FILTER_VALIDATE_INT);
    $baths = filter_var($_POST['baths'] ?? null, FILTER_VALIDATE_FLOAT);
    $features = trim($_POST['features'] ?? '');
    $label = trim($_POST['label'] ?? '');
    $area_sqft = filter_var($_POST['area_sqft'] ?? null, FILTER_VALIDATE_FLOAT);

    if (empty($title)) $errors[] = "Title is required.";
    if ($price === false || $price <= 0) $errors[] = "Valid price is required.";
    if (empty($property_type)) $errors[] = "Property type is required.";

    // Process image uploads (up to 3 files)
    $images = [];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES["image$i"]['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["image$i"]['tmp_name'], $targetFilePath)) {
                    $images[] = $targetFilePath;
                } else {
                    $errors[] = "Sorry, there was an error uploading your file $i.";
                }
            } else {
                $errors[] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
            }
        }
    }
    $image = implode(',', $images);

    if (empty($errors)) {
        $sql = "INSERT INTO properties (title, description, image, price, property_type, city, beds, baths, features, label, area_sqft) 
                VALUES (:title, :description, :image, :price, :property_type, :city, :beds, :baths, :features, :label, :area_sqft)";
        
        try {
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $title,
                ':description' => $description,
                ':image' => $image,
                ':price' => $price,
                ':property_type' => $property_type,
                ':city' => $city,
                ':beds' => $beds,
                ':baths' => $baths,
                ':features' => $features,
                ':label' => $label,
                ':area_sqft' => $area_sqft
            ];
            $stmt->execute($params);
            $success = true;
        } catch(PDOException $e) {
            $errors[] = "Failed to add property. Error: " . $e->getMessage();
        }
    }
}

// Fetch all properties
try {
    $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errors[] = "Failed to fetch properties. Error: " . $e->getMessage();
}

// Include header
include __DIR__ . '/templates/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Real Estate - Property Management</title>
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
        <h1 class="text-4xl font-bold mb-8 text-primary">AJ Real Estate - Property Management</h1>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">Property added successfully.</span>
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

        <?php if ($user_role === 'admin' || $user_role === 'seller'): ?>
        <h2 class="text-2xl font-bold mb-4 text-primary">Add New Property</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="action" value="add">
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="title">Title</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="title" type="text" name="title" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="description">Description</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="description" name="description"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="price">Price (PHP)</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="price" type="number" name="price" step="0.01" required>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="property_type">Property Type</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="property_type" name="property_type" required>
                    <option value="">Select a type</option>
                    <option value="House">House</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Condo">Condo</option>
                    <option value="Townhouse">Townhouse</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="city">City</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="city" type="text" name="city">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="beds">Bedrooms</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="beds" type="number" name="beds">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="baths">Bathrooms</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="baths" type="number" name="baths" step="0.5">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="area_sqft">Area (sq ft)</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="area_sqft" type="number" name="area_sqft" step="0.01">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="features">Features</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="features" name="features"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="label">Label</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="label" type="text" name="label">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="image1">Property Image 1</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="image1" type="file" name="image1" accept="image/*">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="image2">Property Image 2</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="image2" type="file" name="image2" accept="image/*">
            </div>
            <div class="mb-4">
                <label class="block text-primary text-sm font-bold mb-2" for="image3">Property Image 3</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-primary focus:border-primary" id="image3" type="file" name="image3" accept="image/*">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                    Add Property
                </button>
            </div>
        </form>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mb-4 text-primary">Property Listings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($properties as $property): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <?php 
                    $images = explode(',', $property['image']);
                    if (!empty($images[0])): 
                    ?>
                        <img src="<?php safeEcho($images[0]); ?>" alt="<?php safeEcho($property['title']); ?>" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-4">
                        <h3 class="font-bold text-xl mb-2"><?php safeEcho($property['title']); ?></h3>
                        <p class="text-gray-700 text-base mb-2"><?php safeEcho($property['description']); ?></p>
                        <p class="text-primary font-bold">₱<?php echo number_format($property['price'], 2); ?></p>
                        <p class="text-gray-600"><?php safeEcho($property['property_type']); ?> in <?php safeEcho($property['city']); ?></p>
                        <p class="text-gray-600"><?php echo $property['beds'] ?? 'N/A'; ?> bed, <?php echo $property['baths'] ?? 'N/A'; ?> bath</p>
                        <p class="text-gray-600"><?php echo $property['area_sqft'] ? number_format($property['area_sqft']) : 'N/A'; ?> sq ft</p>
                        <?php if (!empty($property['features'])): ?>
                            <p class="text-gray-600"><strong>Features:</strong> <?php safeEcho($property['features']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($property['label'])): ?>
                            <p class="text-gray-600"><strong>Label:</strong> <?php safeEcho($property['label']); ?></p>
                        <?php endif; ?>
                        <a href="contact.php?property_id=<?php echo $property['id']; ?>" class="mt-4 bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 block text-center">
                            Contact Owner
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($user_role === 'admin'): ?>
        <div class="mt-8 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">Note for Administrators:</p>
            <p>Property deletions should be handled directly in the database. Changes made in the database will be automatically reflected on this page upon refresh.</p>
        </div>
        <?php endif; ?>
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