

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product.
 * @param string $userName  The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating   The rating given by the user (e.g., 1-5).
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 *
 * @return array An array containing success/failure status and optionally the review ID.
 */
function createReview(
    string $productId,
    string $userName,
    string $reviewText,
    int $rating,
    string $dbHost,
    string $dbName,
    string $dbUser,
    string $dbPassword
) {
    // Validation (You can customize this based on your requirements)
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID is required.'];
    }
    if (empty($userName)) {
        return ['success' => false, 'message' => 'User name is required.'];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text is required.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    try {
        // Database connection (using PDO - a recommended practice)
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return results as associative arrays
        ];
        $pdo = new PDO($dsn, $dbUser, $dbPassword, $options);

        // Prepare the SQL statement
        $stmt = $pdo->prepare(
            "INSERT INTO reviews (productId, userName, reviewText, rating) VALUES (?, ?, ?, ?)"
        );

        // Bind the parameters
        $stmt->execute([$productId, $userName, $reviewText, $rating]);

        // Get the last inserted ID
        $reviewId = $pdo->lastInsertId();

        return ['success' => true, 'reviewId' => $reviewId, 'message' => 'Review created successfully.'];

    } catch (PDOException $e) {
        // Handle database errors (important!)
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}


// Example Usage:
// Replace with your actual database credentials
$productId = 'product123';
$userName = 'John Doe';
$reviewText = 'This product is amazing!  I highly recommend it.';
$rating = 5;
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPassword = 'your_database_password';



$result = createReview($productId, $userName, $reviewText, $rating, $dbHost, $dbName, $dbUser, $dbPassword);

if ($result['success']) {
    echo "Review created successfully! Review ID: " . $result['reviewId'] . "<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}

?>
