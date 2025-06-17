

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 * It includes basic validation and error handling.
 *
 * @param string $product_id  The ID of the product being reviewed.
 * @param string $username   The username of the reviewer.
 * @param string $review_text The text of the review.
 * @return array  An array containing:
 *                - 'success':  True if the review was submitted successfully, False otherwise.
 *                - 'message':   A message indicating the result of the operation.
 *                - 'review':    The submitted review (if successful).
 */
function submit_review(string $product_id, string $username, string $review_text) {
    // Basic validation -  Important for security and data integrity
    if (empty($product_id) || empty($username) || empty($review_text)) {
        return [
            'success' => false,
            'message' => 'Error: All fields are required.',
            'review' => null
        ];
    }

    if (strlen($review_text) > 1000) {
        return [
            'success' => false,
            'message' => 'Error: Review text exceeds maximum length (1000 characters).',
            'review' => null
        ];
    }

    // Simulate saving the review to a database (replace with your database logic)
    // In a real application, you would connect to your database and insert the data.
    // This is just a placeholder.
    $review = [
        'product_id' => $product_id,
        'username' => $username,
        'review_text' => $review_text,
        'timestamp' => time()
    ];

    //  Simulate successful save
    $success = true;
    $message = 'Review submitted successfully!';

    return [
        'success' => $success,
        'message' => $message,
        'review' => $review
    ];
}



/**
 * Displays reviews for a given product.
 *
 * @param array $reviews An array of review objects, as returned by submit_review.
 */
function display_reviews(array $reviews) {
    echo "<h2>Reviews for Product ID: " . $reviews[0]['product_id'] . "</h2>"; // Assuming the product ID is always the first element.

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<p><strong>Username:</strong> " . htmlspecialchars($review['username']) . "</p>"; // Sanitize output
            echo "<p><strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "</p>";
            echo "<p><strong>Date:</strong> " . date("Y-m-d H:i:s", strtotime($review['timestamp'])) . "</p>";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example Usage:

// Submit a review
$review_result = submit_review("123", "John Doe", "This is a fantastic product! I highly recommend it.");

if ($review_result['success']) {
    echo "<p>Review submitted successfully!</p>";
    echo "<p>Review Data: " . print_r($review_result['review'], true) . "</p>";  // Display review data
} else {
    echo "<p>Error submitting review: " . $review_result['message'] . "</p>";
}

// Display the reviews
$reviews = [];
$review_result = submit_review("123", "Jane Smith", "Excellent value for money.");
if ($review_result['success']) {
    $reviews[] = $review_result['review'];
}

$review_result = submit_review("123", "Peter Jones", "Could be better...");
if ($review_result['success']) {
    $reviews[] = $review_result['review'];
}
display_reviews($reviews);



?>
