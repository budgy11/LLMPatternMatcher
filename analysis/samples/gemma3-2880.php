

<?php

/**
 * User Review Function
 *
 * This function takes a product ID, user data, and a review text,
 * and saves the review to a database.  It also includes basic validation
 * and error handling.
 *
 * @param int $productId The ID of the product the review is for.
 * @param array $userData  An associative array containing user data:
 *                      - 'username' => string - The user's username.
 *                      - 'rating' => int - The rating given (e.g., 1-5).
 *                      - 'review' => string - The text of the review.
 * @param PDO $pdo        A PDO database connection object.
 *
 * @return bool True on success, false on failure.
 */
function saveUserReview(int $productId, array $userData, PDO $pdo)
{
    // Validate input data
    $username = isset($userData['username']) ? trim($userData['username']) : '';
    $rating = isset($userData['rating']) ? (int)$userData['rating'] : 0; // Ensure rating is an integer
    $review = isset($userData['review']) ? trim($userData['review']) : '';

    if (empty($username) || empty($review)) {
        error_log("Invalid review data: Username and review text are required."); // Log the error
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating: Rating must be between 1 and 5.");
        return false;
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (product_id, username, rating, review)
            VALUES (:product_id, :username, :rating, :review)";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false;
    }
}


// Example Usage (Assuming you have a database connection established)

// **Important: Replace with your actual database connection details**
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Sample User Data
$userData = [
    'username' => 'john.doe',
    'rating' => 4,
    'review' => 'Great product!  I really liked it.',
];

// Save the review
if (saveUserReview(123, $userData, $pdo)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.  Check the error logs.";
}

?>
