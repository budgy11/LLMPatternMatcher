

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier for the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @param int $rating (optional) A rating from 1 to 5. Defaults to null.
 * @param string $timestamp (optional)  A timestamp for when the review was created. Defaults to current timestamp.
 *
 * @return bool True on successful submission, false on failure.
 */
function submit_review(string $product_id, string $user_name, string $review_text, int $rating = null, string $timestamp = null)
{
    // **Important Security Measures:**  Always validate and sanitize user input!
    // This is a basic example and needs significant improvement for production.

    // Sanitize input - VERY IMPORTANT
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

    // Validation - crucial to prevent abuse and errors
    if (empty($product_id) || empty($user_name) || empty($review_text)) {
        error_log("Review submission failed: Missing required fields.");
        return false;
    }

    if ($rating !== null && !is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Review submission failed: Invalid rating.");
        return false;
    }


    // **Data Storage - Replace with a database connection**
    // This is a placeholder - you'll need to connect to your database.
    $review_data = [
        'product_id' => $product_id,
        'user_name' => $user_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp ?? time(), // Use current timestamp if none provided
    ];

    // **Example:  Simulated database insertion (replace with your database code)**
    // In a real application, you would use a database query.
    // For demonstration purposes, we'll just store it in a simple array.
    $reviews = json_encode($review_data); //Encode for storage if needed
    file_put_contents('reviews.json', $reviews . "
", FILE_APPEND); // Append to a file

    //Success
    error_log("Review submitted successfully for product ID: " . $product_id);
    return true;
}



/**
 * Get Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param string $product_id The unique identifier for the product.
 * @return array|null An array of review objects, or null if no reviews are found.
 */
function get_reviews(string $product_id) {
    // **Important:  Fetch from your database here!**
    // This is just a placeholder - use your database connection.
    // Example (replace with your database query):
    $reviews = json_decode(file_get_contents('reviews.json'), true); //decode JSON

    //Filter reviews by product_id
    $filteredReviews = [];
    foreach($reviews as $review){
        if($review['product_id'] == $product_id){
            $filteredReviews[] = $review;
        }
    }

    return empty($filteredReviews) ? null : $filteredReviews;
}



/**
 * Display Reviews
 *
 * Displays reviews for a product.
 *
 * @param array $reviews An array of review objects.
 */
function display_reviews(array $reviews) {
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>User:</strong> " . htmlspecialchars($review['user_name']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</li>";
    }
    echo "</ul>";
}


// Example Usage (For testing purposes):
$product_id = "12345";

// Submit a review
if (submit_review($product_id, "John Doe", "This is a great product!", 5)) {
    echo "<p>Review submitted successfully!</p>";
} else {
    echo "<p>Review submission failed.</p>";
}

// Get and display reviews
$reviews = get_reviews($product_id);
display_reviews($reviews);

?>
