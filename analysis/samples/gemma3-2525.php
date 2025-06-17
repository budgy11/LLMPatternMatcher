

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and error handling.
 *
 * @param string $productId The unique identifier for the product or item.
 * @param string $reviewerName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (optional) The rating (e.g., 1-5). Defaults to 0.
 * @param string $dbConnection (optional)  A connection object to your database.  If not provided, a default is used.
 * @return array An array containing:
 *   - 'success': True if the review was saved successfully, false otherwise.
 *   - 'message':  A message indicating the result of the operation.
 *   - 'reviewId': The ID of the newly created review (if successful), or null.
 */
function saveUserReview(string $productId, string $reviewerName, string $reviewText, int $rating = 0, $dbConnection = null)
{
    $success = false;
    $message = '';
    $reviewId = null;

    // Database connection - Use a default if not provided
    if ($dbConnection === null) {
        // Replace this with your actual database connection setup
        $dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    }

    try {
        // Validate inputs (Important!)
        if (empty($reviewerName)) {
            return ['success' => false, 'message' => 'Reviewer name cannot be empty.', 'reviewId' => null];
        }
        if (empty($reviewText)) {
            return ['success' => false, 'message' => 'Review text cannot be empty.', 'reviewId' => null];
        }

        // Sanitize inputs (important to prevent SQL injection!)
        $reviewerName = trim($reviewerName);
        $reviewText = trim($reviewText);

        // Escape data for safe database insertion
        $stmt = $dbConnection->prepare("INSERT INTO reviews (productId, reviewerName, reviewText, rating) VALUES (:productId, :reviewerName, :reviewText, :rating)");
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewerName', $reviewerName);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        $reviewId = $dbConnection->lastInsertId();  // Get the ID of the newly inserted row

        $success = true;
        $message = 'Review saved successfully.';

    } catch (PDOException $e) {
        $message = 'Error saving review: ' . $e->getMessage();
        error_log($e->getMessage()); // Log the error for debugging
    }

    return ['success' => $success, 'message' => $message, 'reviewId' => $reviewId];
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $productId The unique identifier for the product.
 * @param PDO $dbConnection  A PDO database connection object.
 */
function displayUserReviews(string $productId, PDO $dbConnection)
{
    // Retrieve reviews for the product
    $stmt = $dbConnection->prepare("SELECT id, reviewerName, reviewText, rating FROM reviews WHERE productId = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<h2>Reviews for Product: " . $productId . "</h2>";
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . $review['reviewerName'] . ":</strong> " . htmlspecialchars($review['reviewText']) . " (Rating: " . $review['rating'] . ")";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example usage (in a web environment, you would typically call these functions
// in response to a form submission and then render the results)

// Simulate a database connection (replace with your actual connection)
$dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");


// Example 1: Save a review
$result = saveUserReview("product123", "John Doe", "This is a great product!", 5);
if ($result['success']) {
    echo "<p>Review saved successfully. Review ID: " . $result['reviewId'] . "</p>";
} else {
    echo "<p>Error saving review: " . $result['message'] . "</p>";
}


// Example 2: Display reviews for a product
displayUserReviews("product123", $dbConnection);

?>
