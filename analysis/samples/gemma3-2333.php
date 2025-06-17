

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $name The name of the reviewer.
 * @param string $comment The review comment.
 * @param int    $rating   (Optional) The rating (1-5).  Defaults to 0.
 * @return array An array containing the review details and a success/failure flag.
 */
function submitReview($productId, $name, $comment, $rating = 0) {
    // Input Validation - VERY IMPORTANT!
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.'];
    }
    if (empty($name)) {
        return ['success' => false, 'message' => 'Reviewer name cannot be empty.'];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Review comment cannot be empty.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    // Store the review in a database (or other storage mechanism)
    // This is just a placeholder - replace with your actual database logic

    $review = [
        'product_id' => $productId,
        'name'        => $name,
        'comment'     => $comment,
        'rating'      => $rating,
        'timestamp'   => time()
    ];

    //  Simulate storing the review to a file.  Replace with actual database logic
    $filename = "reviews.txt";
    file_put_contents($filename, $review . PHP_EOL, FILE_APPEND);


    return ['success' => true, 'review' => $review];
}


/**
 * Display User Reviews
 *
 * Retrieves and displays reviews for a given product ID.
 *
 * @param string $productId The ID of the product.
 * @return array An array containing the reviews or an empty array if none are found.
 */
function displayReviews($productId) {
    // Retrieve reviews from a database or file.
    // This is just a placeholder - replace with your actual database logic

    $filename = "reviews.txt";
    $reviews = file($filename, FILE_IGNORE_NEW_LINES); //Reads the file line by line

    // Remove the first line (header) from the array
    array_shift($reviews);

    return $reviews;
}


// Example Usage:
// Simulate a user submitting a review
$reviewResult = submitReview("123", "John Doe", "Great product!  Works perfectly.", 5);

if ($reviewResult['success']) {
    echo "Review submitted successfully:<br>";
    echo "Product ID: " . $reviewResult['review']['product_id'] . "<br>";
    echo "Reviewer: " . $reviewResult['review']['name'] . "<br>";
    echo "Rating: " . $reviewResult['review']['rating'] . "<br>";
    echo "Comment: " . $reviewResult['review']['comment'] . "<br>";
} else {
    echo "Error submitting review: " . $reviewResult['message'] . "<br>";
}



// Display all reviews for product "123"
$allReviews = displayReviews("123");

echo "<br><h2>All Reviews for Product 123:</h2>";

if (empty($allReviews)) {
    echo "<p>No reviews found.</p>";
} else {
    foreach ($allReviews as $review) {
        echo "<p><strong>Reviewer:</strong> " . $review['name'] . "</p>";
        echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
        echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
        echo "<hr>";
    }
}

?>
