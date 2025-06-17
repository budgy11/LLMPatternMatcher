

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating (optional) A rating from 1 to 5 (default: null).
 * @param int $timestamp (optional) Unix timestamp of when the review was created (default: current timestamp).
 * @return array|string  Returns an array on success, or an error message string on failure.
 */
function storeUserReview(string $product_id, string $user_name, string $review_text, ?int $rating = null, ?int $timestamp = null) {
    // Validation - basic checks to prevent malicious input.  Expand as needed.
    if (empty($product_id) || empty($user_name) || empty($review_text)) {
        return "Error: Product ID, User Name, and Review Text cannot be empty.";
    }
    if ($rating === null) {
        $rating = null; // Allow null rating.
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    //  Database interaction -  This is a placeholder.  Replace with your actual database logic.
    //  This example demonstrates how to store the data in an array (simulating a database).

    $review = [
        'product_id' => $product_id,
        'user_name' => $user_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp ?? time() // Use current time if timestamp is not provided.
    ];

    //  In a real application, you'd insert this data into a database.

    // Example database storage (replace with your actual database code)
    //  e.g.,  $db->insert('reviews', $review);

    // Successful Storage
    return $review;
}


/**
 *  Example function to retrieve reviews for a product.  This is just an example; 
 *  adapt it to your database design and retrieval method.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 * @return array|string Returns an array of reviews or an error message.
 */
function getProductReviews(string $product_id) {
    //  Example:  Retrieve reviews from a database table called 'reviews'
    //  Assuming the table has columns: product_id, user_name, review_text, rating, timestamp

    //  This is just an example; adapt it to your database design and retrieval method.

    $reviews = [];

    //  Replace this with your database query
    // Example using a simulated database (replace with actual query)
    // $result = $db->query("SELECT * FROM reviews WHERE product_id = ?", $product_id);
    // while ($row = $result->fetch_assoc()) {
    //     $reviews[] = $row;
    // }

    // Simulate a database result
    if ($product_id === '123') {
        $reviews = [
            ['product_id' => '123', 'user_name' => 'John Doe', 'review_text' => 'Great product!', 'rating' => 5, 'timestamp' => time()],
            ['product_id' => '123', 'user_name' => 'Jane Smith', 'review_text' => 'Good value for money.', 'rating' => 4, 'timestamp' => time() - 3600], // A review from an hour ago
        ];
    } else {
        $reviews = []; // No reviews found for this product ID
    }

    return $reviews;
}


// --- Example Usage ---

// Store a review
$review_data = storeUserReview('123', 'Alice', 'This is an amazing product!', 5);
if (is_array($review_data)) {
    echo "Review stored successfully: " . json_encode($review_data) . "
";
} else {
    echo "Error storing review: " . $review_data . "
";
}


// Get reviews for product '123'
$product_reviews = getProductReviews('123');
if (is_array($product_reviews)) {
    echo "Product Reviews:
";
    foreach ($product_reviews as $review) {
        echo "  - " . json_encode($review) . "
";
    }
} else {
    echo "No reviews found for product '123'
";
}


// Store another review with a timestamp
$review_data2 = storeUserReview('456', 'Bob', 'Okay product, could be better.', 4, time() - 7200); // Review from 2 hours ago.
if (is_array($review_data2)) {
    echo "Review stored successfully: " . json_encode($review_data2) . "
";
} else {
    echo "Error storing review: " . $review_data2 . "
";
}
?>
