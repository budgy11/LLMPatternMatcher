

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $username The username of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $reviews  An array to store the reviews (used for demonstration).  In a real app, 
 *                         this would likely be a database.
 *
 * @return array  An array of reviews, including the new review if successful.
 */
function add_review(string $productId, string $username, string $rating, string $comment, array &$reviews = [])
{
    // Input validation - crucial for security and data integrity
    if (empty($productId)) {
        return ['error' => 'Product ID cannot be empty.'];
    }
    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }
    if (empty($rating)) {
        return ['error' => 'Rating cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }
    if (empty($comment)) {
        return ['error' => 'Comment cannot be empty.'];
    }

    // Create the review object (could be an object for better structure)
    $review = [
        'productId' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time() // Add a timestamp for ordering and tracking
    ];

    // Add the review to the array
    $reviews[] = $review;

    return $reviews;
}


/**
 * Display Reviews
 *
 * This function formats and displays the reviews for a given product.
 *
 * @param array $reviews An array of reviews.
 */
function display_reviews(array $reviews) {
    echo "<h2>Reviews for Product ID: " . $reviews[0]['productId'] . "</h2>" . "<br>"; // Display Product ID

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>Username:</strong> " . $review['username'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
        echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}


// --- Example Usage ---

// Initialize an empty array to store reviews (in a real app, this would be a database)
$reviews = [];

// Add some reviews
$reviews = add_review('P123', 'JohnDoe', 4, 'Great product, highly recommended!', $reviews);
$reviews = add_review('P456', 'JaneSmith', 5, 'Excellent value for money.', $reviews);
$reviews = add_review('P123', 'PeterJones', 3, 'Could be better.', $reviews);
$reviews = add_review('P789', 'AliceBrown', 1, 'Very disappointing.', $reviews);

// Display the reviews
display_reviews($reviews);

// Example of handling an error
$error_result = add_review('P123', '', 3, 'Invalid review', $reviews);
print_r($error_result);  // Output the error result
?>
