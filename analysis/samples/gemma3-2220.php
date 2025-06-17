

<?php

/**
 * User Review Function
 *
 * This function takes a user's review and saves it to a database.
 * It also handles some basic validation and formatting.
 *
 * @param string $username    The username of the reviewer.
 * @param string $review_text The review text provided by the user.
 * @param int    $product_id  The ID of the product being reviewed.
 * @param PDO   $pdo        The PDO database connection object.
 *
 * @return bool True on success, false on failure.
 */
function saveUserReview(string $username, string $review_text, int $product_id, PDO $pdo): bool
{
    // Basic Validation
    if (empty($username) || empty($review_text) || $product_id <= 0) {
        error_log("Invalid review data received. Username: " . $username . ", Review: " . $review_text . ", Product ID: " . $product_id);
        return false;
    }

    // Sanitize Review Text (Important for Security)
    $review_text = trim($review_text); // Remove leading/trailing whitespace
    $review_text = htmlspecialchars($review_text, ENT_QUOTES, 'UTF-8'); // Escape special characters

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (user_name, review_text, product_id) VALUES (:username, :review_text, :product_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':product_id', $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error saving review: " . $stmt->errorInfo()[2]);  // Log the specific error
        return false;
    }
}

// Example Usage (Illustrative - Replace with your actual database setup)

// Assuming you have a PDO connection object named $pdo

// Example product ID
$product_id = 123;

// Example user review
$username = "JohnDoe";
$review_text = "This product is amazing!  I really enjoyed it.";

// Save the review
if (saveUserReview($username, $review_text, $product_id, $pdo)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.  Check the logs for details.";
}



// --------------------  Database Setup (Illustrative) --------------------
// Replace this with your actual database configuration
$host = 'localhost';
$db_name = 'your_database_name';
$username = 'your_db_user';
$password = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
    echo "Connected to database successfully!";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}


?>
