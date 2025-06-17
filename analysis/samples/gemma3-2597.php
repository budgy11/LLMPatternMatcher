

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userName The name of the user writing the review.
 * @param string $reviewText The text of the review.
 * @param int $rating  The rating given by the user (1-5).
 *
 * @return array An array containing success/failure status and optionally the review ID.
 */
function createReview(string $productId, string $userName, string $reviewText, int $rating): array
{
    // Sanitize inputs - Important for security!
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $userName = filter_var($userName, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $rating = intval($rating);  // Ensure rating is an integer

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be between 1 and 5.'];
    }

    // **In a real application, you would connect to a database here.**
    // This is a placeholder for demonstration.

    // Simulate storing the review (replace with your database logic)
    $reviewId = generateUniqueId(); // Use a function to generate a unique ID
    $reviewData = [
        'product_id' => $productId,
        'user_name' => $userName,
        'review_text' => $reviewText,
        'rating' => $rating,
        'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
    ];


    // Store the review in a data structure (replace with database insertion)
    // Example: $reviews[$productId][] = $reviewData;


    return ['success' => true, 'review_id' => $reviewId];
}

/**
 * Generate a unique ID for a review
 *  (Replace with a more robust solution like UUID)
 * @return string
 */
function generateUniqueId(): string
{
    return uniqid();
}



// -------------------- Example Usage --------------------

// 1. Create a review
$productId = 'product123';
$userName = 'John Doe';
$reviewText = 'This is a great product! I highly recommend it.';
$rating = 5;

$result = createReview($productId, $userName, $reviewText, $rating);

if ($result['success']) {
    echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
    echo "Error creating review: " . $result['message'] . "
";
}


// 2. Example with invalid rating
$result = createReview($productId, $userName, $reviewText, 0);
if ($result['success']) {
   echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
   echo "Error creating review: " . $result['message'] . "
";
}


?>
