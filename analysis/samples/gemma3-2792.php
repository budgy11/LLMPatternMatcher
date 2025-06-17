

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier of the product or service.
 * @param string $userName  The name of the user submitting the review.
 * @param string $userRating  The user's rating (e.g., 1-5 stars).
 * @param string $reviewText The user's written review.
 * @param array $dbConnection  Database connection object (optional - handles connection)
 *
 * @return array  An array containing:
 *               - 'success': true if the review was successfully saved, false otherwise.
 *               - 'message': A message indicating the success or failure of the operation.
 *               - 'review': The newly created review data (if successful).
 */
function saveUserReview(
    string $productId,
    string $userName,
    string $userRating,
    string $reviewText,
    $dbConnection = null // Optional: Database connection object
) {
    $success = false;
    $message = '';
    $review = [];

    // Validate inputs (basic example - expand as needed)
    if (empty($productId) || empty($userName) || empty($userRating) || empty($reviewText)) {
        $message = 'Error: All fields are required.';
        return ['success' => false, 'message' => $message, 'review' => $review];
    }

    // Convert rating to integer (e.g., '5' becomes 5)
    $userRating = (int) $userRating;

    // Sanitize inputs to prevent SQL injection
    $productId = mysqli_real_escape_string($dbConnection, $productId);
    $userName = mysqli_real_escape_string($dbConnection, $userName);
    $reviewText = mysqli_real_escape_string($dbConnection, $reviewText);

    // ***  Database Logic  ***
    try {
        // Construct the SQL query
        $sql = "INSERT INTO reviews (product_id, user_name, rating, review_text)
                VALUES ('$productId', '$userName', $userRating, '$reviewText')";

        // Execute the query
        $result = mysqli_query($dbConnection, $sql);

        if ($result) {
            $success = true;
            $review = [
                'id' => mysqli_insert_id($dbConnection),  // Get the ID of the newly inserted row
                'product_id' => $productId,
                'user_name' => $userName,
                'rating' => $userRating,
                'review_text' => $reviewText,
                'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
            ];
        } else {
            $message = 'Error: Query failed: ' . mysqli_error($dbConnection);
        }
    } catch (Exception $e) {
        $message = 'Database Error: ' . $e->getMessage();
    }

    return ['success' => $success, 'message' => $message, 'review' => $review];
}


/**
 * Function to retrieve reviews for a product
 * @param string $productId The product ID to retrieve reviews for
 * @param array $dbConnection Database connection object
 * @return array An array containing the reviews.
 */
function getReviewsForProduct(string $productId, $dbConnection = null)
{
    $reviews = [];

    try {
        $sql = "SELECT id, user_name, rating, review_text, created_at FROM reviews WHERE product_id = '$productId'";
        $result = mysqli_query($dbConnection, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reviews[] = [
                    'id' => $row['id'],
                    'user_name' => $row['user_name'],
                    'rating' => $row['rating'],
                    'review_text' => $row['review_text'],
                    'created_at' => $row['created_at']
                ];
            }
        }
    } catch (Exception $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error for debugging
        // Handle the error appropriately (e.g., display a message to the user)
    }

    return $reviews;
}


// ** Example Usage (Simulated Database Connection) **
// This is just for demonstration.  In a real application, you'd establish
// a proper database connection.

// Create a dummy database connection (replace with your actual connection)
$dbConnection = mysqli_connect("localhost", "username", "password", "database_name");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}



// Example 1: Save a review
$review_data = saveUserReview(
    "123",
    "John Doe",
    "5",
    "This is a fantastic product!  I highly recommend it.",
    $dbConnection
);

if ($review_data['success']) {
    echo "Review saved successfully!<br>";
    print_r($review_data['review']); // Display the saved review data
} else {
    echo "Error saving review: " . $review_data['message'] . "<br>";
}


// Example 2: Get Reviews for Product
$product_reviews = getReviewsForProduct("123", $dbConnection);
echo "<h2>Reviews for Product 123:</h2>";
if (count($product_reviews) > 0) {
    echo "<ul>";
    foreach ($product_reviews as $review) {
        echo "<li><b>User:</b> " . $review['user_name'] . "<br><b>Rating:</b> " . $review['rating'] . "<br><b>Review:</b> " . $review['review_text'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews found for this product.</p>";
}

// Close the database connection
mysqli_close($dbConnection);

?>
