

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 * It includes data sanitization, validation, and display logic.
 *
 * @param string $user_name   The name of the user submitting the review.
 * @param string $review_text The review text submitted by the user.
 * @param array  $reviews    (Optional) An array of existing reviews to display.
 *
 * @return array An array containing:
 *   - 'reviews':  An updated array of reviews (including the new one).
 *   - 'success':  True if the review was submitted successfully, false otherwise.
 *   - 'error':    Error message if there was an error, null otherwise.
 */
function submitReview(string $user_name, string $review_text, array &$reviews = [])
{
    // Sanitize input to prevent XSS and SQL injection
    $user_name = htmlspecialchars($user_name);
    $review_text = htmlspecialchars($review_text);

    // Validate input
    if (empty($user_name) || empty($review_text)) {
        return ['success' => false, 'error' => 'User name and review text cannot be empty.', 'reviews' => $reviews];
    }

    // Add the new review to the array
    $reviews[] = ['user' => $user_name, 'text' => $review_text, 'date' => date('Y-m-d H:i:s')];

    return ['success' => true, 'error' => null, 'reviews' => $reviews];
}


// Example Usage:

// Simulate a simple database (for demonstration)
$stored_reviews = [];

// Simulate a user submitting a review
$user_name = 'John Doe';
$review_text = 'This product is amazing! I highly recommend it.';
$result = submitReview($user_name, $review_text, $stored_reviews);

if ($result['success']) {
    echo "<p>Review submitted successfully!</p>";
    echo "<p>Review:</p>";
    echo "<p>" . $result['reviews'][count($result['reviews']) - 1]['text'] . "</p>";
} else {
    echo "<p>Error submitting review: " . $result['error'] . "</p>";
}

// Submit another review
$user_name = 'Jane Smith';
$review_text = 'It could be better.  A few things need improvement.';
$result = submitReview($user_name, $review_text, $stored_reviews);

if ($result['success']) {
    echo "<p>Review submitted successfully!</p>";
    echo "<p>Review:</p>";
    echo "<p>" . $result['reviews'][count($result['reviews']) - 1]['text'] . "</p>";
} else {
    echo "<p>Error submitting review: " . $result['error'] . "</p>";
}
?>
