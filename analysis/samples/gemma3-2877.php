

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $reviewerName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating    The rating given by the user (e.g., 1-5 stars).
 * @param int    $userId    The user's ID (optional, for database integration).
 *
 * @return array An array containing review data if successful, or an error message string if not.
 */
function createReview(string $productId, string $reviewerName, string $reviewText, int $rating, int $userId = null)
{
    // Input Validation - Important for security and data integrity
    if (empty($productId)) {
        return ["error" => "Product ID cannot be empty."];
    }
    if (empty($reviewerName)) {
        return ["error" => "Reviewer Name cannot be empty."];
    }
    if (empty($reviewText)) {
        return ["error" => "Review Text cannot be empty."];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ["error" => "Rating must be a number between 1 and 5."];
    }

    // In a real application, you would save this data to a database
    // For this example, we'll simulate storage using an array.

    $review = [
        "productId" => $productId,
        "reviewerName" => $reviewerName,
        "reviewText" => $reviewText,
        "rating" => $rating,
        "timestamp" => date("Y-m-d H:i:s"), // Add timestamp for tracking
        "userId" => $userId
    ];

    // Simulate saving to a database (replace with your database logic)
    //  Example:  $db->insert("reviews", $review);

    return $review;
}

/**
 * Displays a user review
 *
 * @param array $review  The review data (as returned by createReview).
 * @return string HTML markup to display the review.
 */
function displayReview(array $review)
{
    if (empty($review)) {
        return "<p>No reviews available for this product.</p>";
    }

    $html = "<div class='review'>";
    $html .= "<p><strong>Product ID:</strong> " . htmlspecialchars($review['productId']) . "</p>";
    $html .= "<p><strong>Reviewer:</strong> " . htmlspecialchars($review['reviewerName']) . "</p>";
    $html .= "<p><strong>Rating:</strong> " . htmlspecialchars($review['rating']) . " stars</p>";
    $html .= "<p><strong>Review:</strong> " . nl2br(htmlspecialchars($review['reviewText'])) . "</p>"; // nl2br for line breaks
    $html .= "<p><strong>Timestamp:</strong> " . $review['timestamp'] . "</p>";
    $html .= "</div>";
    return $html;
}

// Example Usage:
$newReview = createReview("product_123", "John Doe", "This product is amazing! I highly recommend it.", 5, 123);

if (isset($newReview['error'])) {
    echo "<p style='color:red;'>Error: " . $newReview['error'] . "</p>";
} else {
    echo "New Review Created Successfully!<br>";
    echo "<pre>";
    print_r($newReview);
    echo "</pre>";
    echo "<br>";

    // Display the review
    echo displayReview($newReview);
}

?>
