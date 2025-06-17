

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 * It includes validation, data sanitization, and basic formatting.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text.
 * @param string $rating The rating (e.g., 1-5).
 *
 * @return array An array containing:
 *               - 'success': true if the review was created successfully, false otherwise.
 *               - 'message': A message indicating the status of the review.
 *               - 'review': The newly created review data if successful.
 */
function createAndDisplayReview(string $productId, string $reviewerName, string $reviewText, string $rating)
{
    // Validate inputs -  Important for security!
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.', 'review' => null];
    }
    if (empty($reviewerName)) {
        return ['success' => false, 'message' => 'Reviewer Name cannot be empty.', 'review' => null];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review Text cannot be empty.', 'review' => null];
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'review' => null];
    }

    // Sanitize Inputs (Crucial for security - prevent XSS)
    $reviewerName = htmlspecialchars($reviewerName);
    $reviewText = htmlspecialchars($reviewText);
    $rating = (int)$rating; // Cast to integer for database operations

    //  Simulate storing the review in a database (replace with your actual database logic)
    //  In a real application, you'd use a database query.
    $review = [
        'product_id' => $productId,
        'reviewer_name' => $reviewerName,
        'review_text' => $reviewText,
        'rating' => $rating,
        'created_at' => date('Y-m-d H:i:s') // Add timestamp
    ];

    // Store the review (simulated)
    // Example:  $db->insert('reviews', $review);


    return ['success' => true, 'message' => 'Review created successfully!', 'review' => $review];
}



/**
 * Display Reviews for a Product
 *
 * This function retrieves and displays reviews for a given product.
 *
 * @param string $productId The ID of the product.
 * @return array An array containing:
 *               - 'reviews': An array of review objects.
 *               - 'totalReviews': The total number of reviews.
 *               - 'noReviews':  true if no reviews exist for the product.
 */
function displayReviews(string $productId)
{
    // Simulate fetching reviews from a database
    // In a real application, you would execute a database query.

    $reviews = [
        ['product_id' => 1, 'reviewer_name' => 'Alice', 'review_text' => 'Great product!', 'rating' => 5, 'created_at' => '2023-10-27 10:00:00'],
        ['product_id' => 1, 'reviewer_name' => 'Bob', 'review_text' => 'Good value for money.', 'rating' => 4, 'created_at' => '2023-10-26 14:30:00'],
        ['product_id' => 2, 'reviewer_name' => 'Charlie', 'review_text' => 'Excellent!', 'rating' => 5, 'created_at' => '2023-10-25 09:15:00']
    ];

    $totalReviews = count($reviews);
    $noReviews = $totalReviews == 0;

    return ['reviews' => $reviews, 'totalReviews' => $totalReviews, 'noReviews' => $noReviews];
}


// Example Usage:

// 1. Create a Review
$reviewResult = createAndDisplayReview(1, 'David Smith', 'This product is amazing!', 5);

if ($reviewResult['success']) {
    echo "Review created successfully!<br>";
    echo "Review Details: " . print_r($reviewResult['review'], true) . "<br>";
} else {
    echo "Error creating review: " . $reviewResult['message'] . "<br>";
}


// 2. Display Reviews for Product 1
$reviewsData = displayReviews(1);

if ($reviewsData['noReviews']) {
    echo "No reviews found for product 1.<br>";
} else {
    echo "Reviews for Product 1:<br>";
    echo "Total Reviews: " . $reviewsData['totalReviews'] . "<br>";
    foreach ($reviewsData['reviews'] as $review) {
        echo "Reviewer: " . $review['reviewer_name'] . ", Rating: " . $review['rating'] . ", Text: " . $review['review_text'] . "<br>";
    }
}

?>
