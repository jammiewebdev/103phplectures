<?php
// functions/functions.php

/**
 * Get all properties from the database
 *
 * @return array An array of all properties
 */
function getProperties() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getProperties: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single property by ID
 *
 * @param int $id The ID of the property
 * @return array|null The property data or null if not found
 */
function getPropertyById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getPropertyById: " . $e->getMessage());
        return null;
    }
}

/**
 * Add a new property to the database
 *
 * @param array $propertyData The property data
 * @return bool|string True if successful, error message string if failed
 */
function addProperty($propertyData) {
    global $pdo;
    try {
        $sql = "INSERT INTO properties (title, description, price, property_type, bedrooms, bathrooms, area_sqft, image) 
                VALUES (:title, :description, :price, :property_type, :bedrooms, :bathrooms, :area_sqft, :image)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($propertyData);

        if ($result) {
            return true;
        } else {
            error_log("Failed to insert property: " . print_r($stmt->errorInfo(), true));
            return "Error: Failed to add property. Please try again.";
        }
    } catch (PDOException $e) {
        error_log("Database error in addProperty: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}

/**
 * Update an existing property in the database
 *
 * @param int $id The ID of the property to update
 * @param array $propertyData The updated property data
 * @return bool True if successful, false otherwise
 */
function updateProperty($id, $propertyData) {
    global $pdo;
    try {
        $sql = "UPDATE properties SET 
                title = :title, 
                description = :description, 
                price = :price, 
                property_type = :property_type, 
                bedrooms = :bedrooms, 
                bathrooms = :bathrooms, 
                area_sqft = :area_sqft, 
                image = :image 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $propertyData['id'] = $id;
        return $stmt->execute($propertyData);
    } catch (PDOException $e) {
        error_log("Database error in updateProperty: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a property from the database
 *
 * @param int $id The ID of the property to delete
 * @return bool True if successful, false otherwise
 */
function deleteProperty($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM properties WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Database error in deleteProperty: " . $e->getMessage());
        return false;
    }
}

/**
 * Upload an image file
 *
 * @param array $file The $_FILES array element
 * @return string|false The path to the uploaded file or false on failure
 */
function uploadImage($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return false;
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return false;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return false;
    }
}

/**
 * Sanitize user input
 *
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Validate property data
 *
 * @param array $data The property data to validate
 * @return array An array of error messages, empty if no errors
 */
function validatePropertyData($data) {
    $errors = [];

    if (empty($data['title'])) {
        $errors[] = "Title is required.";
    }

    if (empty($data['description'])) {
        $errors[] = "Description is required.";
    }

    if (empty($data['price']) || !is_numeric($data['price'])) {
        $errors[] = "Valid price is required.";
    }

    if (empty($data['property_type'])) {
        $errors[] = "Property type is required.";
    }

    if (empty($data['bedrooms']) || !is_numeric($data['bedrooms'])) {
        $errors[] = "Valid number of bedrooms is required.";
    }

    if (empty($data['bathrooms']) || !is_numeric($data['bathrooms'])) {
        $errors[] = "Valid number of bathrooms is required.";
    }

    if (empty($data['area_sqft']) || !is_numeric($data['area_sqft'])) {
        $errors[] = "Valid area in square feet is required.";
    }

    return $errors;
}

/**
 * Get all unique cities from properties
 *
 * @return array An array of unique cities
 */
function getAllCities() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT DISTINCT city FROM properties ORDER BY city");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Database error in getAllCities: " . $e->getMessage());
        return [];
    }
}

/**
 * Search properties based on criteria
 *
 * @param array $criteria The search criteria
 * @return array An array of matching properties
 */
function searchProperties($criteria) {
    global $pdo;
    try {
        $sql = "SELECT * FROM properties WHERE 1=1";
        $params = [];

        if (!empty($criteria['city'])) {
            $sql .= " AND city = ?";
            $params[] = $criteria['city'];
        }

        if (!empty($criteria['min_price'])) {
            $sql .= " AND price >= ?";
            $params[] = $criteria['min_price'];
        }

        if (!empty($criteria['max_price'])) {
            $sql .= " AND price <= ?";
            $params[] = $criteria['max_price'];
        }

        if (!empty($criteria['property_type'])) {
            $sql .= " AND property_type = ?";
            $params[] = $criteria['property_type'];
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in searchProperties: " . $e->getMessage());
        return [];
    }
}

/**
 * Book an appointment
 *
 * @param string $client_name The name of the person booking the appointment
 * @param string $client_email The email of the person booking the appointment
 * @param string $client_phone The phone number of the person booking the appointment
 * @param string $appointment_date The date and time of the appointment
 * @param string $notes Any additional notes for the appointment
 * @return bool|string True if successful, error message string if failed
 */
function bookAppointment($name, $email, $phone, $appointment_date, $notes) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (name, email, phone, appointment_date, notes) VALUES (:name, :email, :phone, :appointment_date, :notes)");
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':appointment_date', $appointment_date);
        $stmt->bindParam(':notes', $notes);
        
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

/**
 * Validate appointment data
 *
 * @param array $data The appointment data to validate
 * @return array An array of error messages, empty if no errors
 */
function validateAppointmentData($data) {
    $errors = [];

    if (empty($data['client_name'])) {
        $errors[] = "Client name is required.";
    }

    if (empty($data['client_email']) || !filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid client email is required.";
    }

    if (empty($data['appointment_date'])) {
        $errors[] = "Appointment date is required.";
    } else {
        $appointment_date = new DateTime($data['appointment_date']);
        $now = new DateTime();
        if ($appointment_date <= $now) {
            $errors[] = "Appointment date must be in the future.";
        }
    }

    // Add more validation as needed

    return $errors;
}

?>