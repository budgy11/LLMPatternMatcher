

<?php

/**
 * User Review Function
 *
 * This function creates a new user review and adds it to a database.
 * It also includes basic input validation and error handling.
 *
 * @param string $username  The username of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $productID   The ID of the product the review is for.
 * @param PDO $pdo       The PDO database connection object.
 *
 * @return bool True if the review was successfully added, false otherwise.
 */
function addReview(string $username, string $reviewText, int $productID, PDO $pdo)
{
    // Input Validation
    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return false;
    }
    if (empty($reviewText)) {
        error_log("Error: Review text cannot be empty.");
        return false;
    }
    if (!is_numeric($productID)) {
        error_log("Error: Product ID must be a number.");
        return false;
    }

    // Sanitize Input (Important for Security)
    $username = trim($username); // Remove leading/trailing whitespace
    $reviewText = trim($reviewText);

    // Prepare and Execute the SQL Query
    try {
        $sql = "INSERT INTO reviews (username, review_text, product_id) VALUES (:username, :review_text, :product_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':product_id', $productID);
        $stmt->execute();

        return true; // Success
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false; // Failure (Database error)
    }
}


// Example Usage (Assuming you have a PDO database connection object named $pdo)
// -----------------------------------------------------------------------

// Dummy PDO connection (Replace with your actual connection)
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_user';
$password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Example Call
$username = 'JohnDoe';
$reviewText = 'This product is amazing!';
$productID = 123;

if (addReview($username, $reviewText, $productID, $pdo)) {
    echo "Review added successfully!";
} else {
    echo "Failed to add review.";
}

?>
