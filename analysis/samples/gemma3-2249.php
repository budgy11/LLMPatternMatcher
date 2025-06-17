

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productId  The unique identifier for the product/service being reviewed.
 * @param string $reviewText  The text of the user's review.
 * @param int    $rating      The rating given by the user (e.g., 1-5).
 * @param string $username    The username of the reviewer.
 *
 * @return array  An array containing the review details, or an error message if the submission fails.
 */
function submitReview(string $productId, string $reviewText, int $rating, string $username): array
{
    // **Input Validation** (Important for security and data integrity)
    if (empty($productId)) {
        return ['error' => 'Product ID cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }
    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }

    // **Data Sanitization** (Crucial for security - prevent XSS and SQL injection)
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'); // Escape for HTML
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    // **Store the Review (Simulated Database Interaction)**
    // In a real application, you'd connect to a database here.
    // This is a simplified example.
    $review = [
        'productId' => $productId,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'username' => $username,
        'timestamp' => time(), // Add a timestamp for tracking review creation
    ];

    // **Store Review in an Array (for demonstration)**
    // In a real application, you'd save this data to a database.
    // Example:  $reviews[] = $review;  or use a session variable or other persistent storage.

    return $review; // Return the new review data
}

/**
 * Display Reviews Function
 *
 * This function takes an array of reviews and displays them in a user-friendly format.
 *
 * @param array $reviews  An array of review objects (as returned by `submitReview`).
 *
 * @return string  HTML markup to display the reviews.
 */
function displayReviews(array $reviews): string
{
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<ul>";
    foreach ($reviews as $review) {
        $html .= '<li>';
        $html .= '<strong>' . $review['username'] . '</strong> - Rating: ' . $review['rating'] . ' - ' . $review['reviewText'];
        $html .= '</li>';
    }
    $html .= "</ul>";
    return $html;
}


// **Example Usage:**

// Simulate submitting a review
$reviewResult = submitReview('product123', 'This is a great product!', 5, 'john.doe');

if (isset($reviewResult['error'])) {
    echo "<p style='color:red;'>Error: " . $reviewResult['error'] . "</p>";
} else {
    // Display the review
    echo displayReviews($reviewResult);
}

//Example with an error
$errorReview = submitReview('', 'bad review', 3, 'jane');
if (isset($errorReview['error'])) {
    echo "<p style='color:red;'>Error: " . $errorReview['error'] . "</p>";
} else {
    echo displayReviews($errorReview);
}
?>
