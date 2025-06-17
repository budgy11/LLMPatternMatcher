

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 * It includes basic input validation and sanitization.
 *
 * @param string $productId  The ID of the product being reviewed.
 * @param string $username   The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $dbConnection  A database connection object.
 *
 * @return array An array containing:
 *              - 'success': True if the review was successfully saved, false otherwise.
 *              - 'message':  A message indicating the status of the review.
 *              - 'review': The review data if successful.
 */
function saveReview(string $productId, string $username, string $reviewText, array $dbConnection)
{
    // Input Validation and Sanitization -  IMPORTANT
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.', 'review' => null];
    }

    if (empty($username)) {
        return ['success' => false, 'message' => 'Username cannot be empty.', 'review' => null];
    }

    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => null];
    }

    // Sanitize input -  Consider more robust sanitization based on your needs
    $reviewText = trim($reviewText);  // Remove leading/trailing whitespace
    //You could add further sanitization here, for example, escaping HTML tags
    //if you were allowing users to input HTML content.  Don't do this for simple text.

    // SQL Injection Prevention -  Using Prepared Statements
    $sql = "INSERT INTO reviews (product_id, username, review_text) VALUES (?, ?, ?)";
    $stmt = $dbConnection->prepare($sql);

    if ($stmt === false) {
        return ['success' => false, 'message' => 'Error preparing statement.  Check database connection.', 'review' => null];
    }

    $stmt->bind_param("ss", $productId, $username, $reviewText); // "ss" specifies string parameters

    if (!$stmt->execute()) {
        return ['success' => false, 'message' => 'Error executing query: ' . $stmt->error, 'review' => null];
    }

    $stmt->close();

    // Get the last inserted ID (if your database generates an auto-increment ID)
    $lastProductId = $dbConnection->lastInsertId();


    return ['success' => true, 'message' => 'Review saved successfully.', 'review' => ['product_id' => $lastProductId, 'username' => $username, 'review_text' => $reviewText]];
}


/**
 * Get Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param int $productId The ID of the product.
 * @param array $dbConnection A database connection object.
 *
 * @return array An array containing:
 *              - 'reviews': An array of review objects, or an empty array if no reviews exist.
 *              - 'success': True if reviews were successfully retrieved, false otherwise.
 */
function getReviewsForProduct(int $productId, array $dbConnection)
{
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $dbConnection->prepare($sql);

    if ($stmt === false) {
        return ['reviews' => [], 'success' => false, 'message' => 'Error preparing statement. Check database connection.'];
    }

    $stmt->bind_param("i", $productId); // "i" specifies an integer parameter

    $stmt->execute();

    $result = $stmt->get_result();

    $reviews = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    $stmt->close();

    return ['reviews' => $reviews, 'success' => true];
}



// Example Usage (Illustrative - Requires Database Setup)
// Replace with your actual database connection
$dbConnection = new mysqli("localhost", "your_username", "your_password", "your_database");
if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}

// Save a review
$reviewResult = saveReview(123, "John Doe", "This is a great product!", $dbConnection);
print_r($reviewResult);


// Get reviews for product 123
$reviewsResult = getReviewsForProduct(123, $dbConnection);
print_r($reviewsResult);

// Close the connection (Important!)
$dbConnection->close();

?>
