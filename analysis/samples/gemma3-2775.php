

<?php

/**
 * User Review Function
 *
 * This function allows you to add, display, and potentially moderate user reviews.
 * It provides basic functionalities like adding a review, retrieving reviews,
 * and displaying them.  More sophisticated moderation features (like flagging,
 * deleting, etc.) would require further development.
 *
 * @param string $productName The name of the product the review is for.
 * @param string $reviewText The text of the review submitted by the user.
 * @param array $user_id The user's ID who submitted the review (optional, for tracking)
 * @return array An array containing the review data (ID, product, review, user_id) 
 *               or an array of errors if adding the review fails.
 */
function add_review(string $productName, string $reviewText, ?int $user_id = null) : array
{
    // Validate inputs (important!)
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    // In a real application, you'd sanitize and validate $reviewText
    // (e.g., prevent XSS attacks, limit length, etc.).  This is a placeholder.

    // Generate a unique review ID (simplified for this example)
    $review_id = bin2hex(uniqid()); // Use a more robust method in production!

    // Store the review (replace with database interaction in a real app)
    // This is a placeholder; consider using a database connection here.
    $review = [
        'id' => $review_id,
        'product' => $productName,
        'review' => $reviewText,
        'user_id' => $user_id,
        'date' => date('Y-m-d H:i:s') // Add timestamp for sorting/filtering
    ];

    // Save the review to a file or database
    //  Example:  file_put_contents('reviews.txt', $review . PHP_EOL, FILE_APPEND);
    // In a real application, use a database:  $db->insert('reviews', $review);

    return $review;
}


/**
 * Retrieves reviews for a given product.
 *
 * @param string $productName The product to search for reviews for.
 * @return array An array of reviews for the product, or an empty array if none are found.
 */
function get_reviews(string $productName) : array
{
    // In a real application, you'd fetch this from a database.
    // This is just a placeholder to demonstrate the concept.

    $reviews = [];

    // Simulate retrieving reviews from a file or database
    $reviews_data = get_all_reviews();

    foreach ($reviews_data as $review) {
        if ($review['product'] == $productName) {
            $reviews[] = $review;
        }
    }

    return $reviews;
}

/**
 * Placeholder function to get all reviews.  In a real application, 
 * this would fetch data from a database.
 * 
 * @return array An array of review data.
 */
function get_all_reviews() : array {
  return [
    ['id' => bin2hex(uniqid()), 'product' => 'Laptop X100', 'review' => 'Great laptop, fast and reliable!', 'user_id' => 123, 'date' => date('Y-m-d H:i:s')],
    ['id' => bin2hex(uniqid()), 'product' => 'Tablet Z200', 'review' => 'Good tablet for the price.', 'user_id' => 456, 'date' => date('Y-m-d H:i:s')],
    ['id' => bin2hex(uniqid()), 'product' => 'Laptop X100', 'review' => 'Excellent value.', 'user_id' => 789, 'date' => date('Y-m-d H:i:s')]
  ];
}



// --- Example Usage ---

// Add a review
$new_review = add_review('Laptop X100', 'Fantastic performance!');
if (isset($new_review['error'])) {
    echo "Error adding review: " . $new_review['error'] . PHP_EOL;
} else {
    echo "Review added successfully.  Review ID: " . $new_review['id'] . PHP_EOL;
}

// Get reviews for Laptop X100
$laptop_reviews = get_reviews('Laptop X100');

echo "Reviews for Laptop X100:" . PHP_EOL;
if (empty($laptop_reviews)) {
    echo "No reviews found." . PHP_EOL;
} else {
    foreach ($laptop_reviews as $review) {
        echo "- " . $review['review'] . " (User ID: " . $review['user_id'] . ")" . PHP_EOL;
    }
}

// Example of error handling when adding an invalid review
$invalid_review = add_review("", "This is a test.");
if (isset($invalid_review['error'])) {
    echo "Error adding review: " . $invalid_review['error'] . PHP_EOL;
}
?>
