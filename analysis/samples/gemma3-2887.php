

<?php

/**
 * User Review Function
 *
 * This function allows you to capture user reviews, sanitize input,
 * and optionally store them in a database.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userId      The ID of the user submitting the review.  Can be NULL for guest reviews.
 * @param string $reviewText  The text of the user's review.
 * @param string $rating      (Optional) The rating given by the user (e.g., 1-5).  Defaults to null.
 * @param string $dbHost       (Optional) Database host.  Required if storing reviews in a database.
 * @param string $dbUser       (Optional) Database user.  Required if storing reviews in a database.
 * @param string $dbPassword  (Optional) Database password.  Required if storing reviews in a database.
 * @param string $dbName       (Optional) Database name.  Required if storing reviews in a database.
 * @param string $table        (Optional) Name of the table to store the reviews in (if using database).
 *
 * @return array An array containing the review data or an error message if invalid input is detected.
 */
function createReview(
    string $productName,
    string $userId,
    string $reviewText,
    string $rating = null,
    string $dbHost = null,
    string $dbUser = null,
    string $dbPassword = null,
    string $dbName = null,
    string $table = null
) {
    // Input Validation - Basic Check
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    // Sanitize input (Important for security)
    $reviewText = trim($reviewText);
    $reviewText = htmlspecialchars($reviewText); // Prevent XSS attacks

    // Validation for rating (if provided)
    if ($rating !== null) {
        if (!is_numeric($rating)) {
            return ['error' => 'Rating must be a number.'];
        }
        if ($rating < 1 || $rating > 5) {
            return ['error' => 'Rating must be between 1 and 5.'];
        }
    }

    // Construct the review data
    $reviewData = [
        'product_name' => $productName,
        'user_id'      => $userId,
        'review_text'  => $reviewText,
        'rating'       => $rating,
    ];

    // Store in Database (Optional)
    if ($dbHost !== null && $dbUser !== null && $dbPassword !== null && $dbName !== null && $table !== null) {
        // Implement database connection and insertion logic here.
        // This is just a placeholder.  You'll need to adapt it to your database system.

        try {
            // Example (MySQL) - Replace with your connection details and query
            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            $query = "INSERT INTO `$table` (product_name, user_id, review_text, rating) VALUES ('" . $conn->real_escape_string($productName) . "', '" . $conn->real_escape_string($userId) . "', '" . $conn->real_escape_string($reviewText) . "', '" . $conn->real_escape_string($rating) . "')";
            $conn->query($query);

            $conn->close();

        } catch (Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    return $reviewData;
}


// Example Usage:
// Simulate a review submission
$review = createReview(
    'Awesome Widget',
    'user123',
    'This widget is fantastic!  I love it.',
    5,
    'localhost',
    'myuser',
    'secretpassword',
    'my_database',
    'product_reviews'
);

if (isset($review['error'])) {
    echo "<p style='color: red;'>Error: " . $review['error'] . "</p>";
} else {
    echo "<p>Review submitted successfully! Data: " . print_r($review, true) . "</p>";
}


?>
