

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 * It includes functionality for adding new reviews, displaying existing reviews,
 * updating reviews, and deleting reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $dbConnection A database connection object.
 *
 * @return array An array containing:
 *   - 'success': True if the operation was successful, false otherwise.
 *   - 'message': A message describing the outcome of the operation.
 *   - 'reviews':  An array of review objects (or an empty array if no reviews exist).
 */
function create_manage_reviews(string $productId, string $username, string $rating, string $comment, array $dbConnection)
{
    $success = false;
    $message = '';
    $reviews = [];

    // 1. Retrieve Existing Reviews
    $query = "SELECT * FROM reviews WHERE productId = ?";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    // 2. Create New Review
    if (empty($reviews)) {
        // If no reviews exist, create a new one
        $query = "INSERT INTO reviews (productId, username, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("sss", $productId, $username, $comment);
        $result = $stmt->execute();

        if ($result) {
            $success = true;
            $message = "Review added successfully!";
        } else {
            $success = false;
            $message = "Error adding review: " . $stmt->error;
        }
    } else {
        // If reviews exist, check for duplicate usernames
        $usernameExists = false;
        foreach ($reviews as $review) {
            if ($review['username'] === $username) {
                $usernameExists = true;
                break;
            }
        }

        if (!$usernameExists) {
            // Create new review
            $query = "INSERT INTO reviews (productId, username, rating, comment) VALUES (?, ?, ?, ?)";
            $stmt = $dbConnection->prepare($query);
            $stmt->bind_param("sss", $productId, $username, $comment);
            $result = $stmt->execute();

            if ($result) {
                $success = true;
                $message = "Review added successfully!";
            } else {
                $success = false;
                $message = "Error adding review: " . $stmt->error;
            }
        } else {
            $success = false;
            $message = "Error: User already reviewed this product.";
        }
    }

    // 3. Return Results
    return [
        'success' => $success,
        'message' => $message,
        'reviews' => $reviews
    ];
}

// Example Usage (Illustrative - requires database setup)
// Assuming you have a database connection object $dbConnection
/*
$productId = "123";
$username = "JohnDoe";
$rating = 5;
$comment = "Great product, highly recommended!";

$result = create_manage_reviews($productId, $username, $rating, $comment, $dbConnection);

if ($result['success']) {
    echo "Message: " . $result['message'] . "<br>";
    echo "Reviews: ";
    foreach ($result['reviews'] as $review) {
        echo "Product ID: " . $review['productId'] . ", Username: " . $review['username'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "<br>";
    }
} else {
    echo "Error: " . $result['message'];
}
*/

?>
