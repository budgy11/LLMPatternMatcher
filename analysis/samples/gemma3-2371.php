

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It provides basic functionality for adding, retrieving, and displaying reviews.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param int $rating   The rating given by the user (1-5).
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully added.
 *   - 'message': A message indicating the status of the review addition.
 *   - 'reviews': An array of all reviews for the product.
 */
function store_user_review(string $productId, string $username, string $reviewText, int $rating): array
{
    // Sanitize input (important for security!)
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $rating = intval($rating); // Ensure rating is an integer

    // Validation (essential for data integrity)
    if (empty($productId) || empty($username) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid review data.  Please ensure all fields are filled and ratings are between 1 and 5.', 'reviews' => []];
    }

    // Example: Store reviews in an array (replace with a database storage)
    $reviews = [];
    if (isset($_SESSION['reviews'][$productId])) {
        $reviews = $_SESSION['reviews'][$productId];
    }

    $newReview = [
        'username' => $username,
        'review' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add timestamp for ordering/filtering
    ];

    $reviews[] = $newReview;
    $_SESSION['reviews'][$productId] = $reviews; // Store in session (for simplicity)

    return ['success' => true, 'message' => 'Review submitted successfully!', 'reviews' => $reviews];
}


// --- Example Usage (for demonstration) ---

// Initialize session (important for storing reviews)
session_start();

// Simulate a product ID
$productId = 'product-123';

// Example 1: Successful submission
$reviewData = store_user_review($productId, 'John Doe', 'This product is amazing!', 5);
print_r($reviewData);

// Example 2: Invalid input (rating outside range)
$invalidReviewData = store_user_review($productId, 'Jane Smith', 'Good product', 7);
print_r($invalidReviewData);

// ---  Displaying Reviews (Example) ---
if (isset($_SESSION['reviews'][$productId])) {
    echo "<h2>Reviews for Product " . $productId . "</h2>";
    echo "<ul>";
    foreach ($_SESSION['reviews'][$productId] as $review) {
        echo "<li><strong>" . $review['username'] . ":</strong> " . htmlspecialchars($review['review']) . " (" . $review['rating'] . "/5) - " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews yet.</p>";
}
?>
