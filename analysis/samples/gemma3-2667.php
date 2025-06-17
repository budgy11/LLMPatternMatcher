

<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and view reviews for a product or service.
 * It includes basic sanitization and validation.
 * 
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's comment.
 * @param string $username The user's name.
 * 
 * @return array An array containing the review data if successful, or an error message if not.
 */
function createReview(string $product_id, string $rating, string $comment, string $username): array
{
    // Input Validation - Basic Sanity Checks
    if (empty($product_id) || empty($rating) || empty($comment) || empty($username)) {
        return ['error' => 'All fields are required.'];
    }

    if (!preg_match('/^[0-5][0-9]*$/', $rating)) { // Ensure rating is a number between 1 and 5
        return ['error' => 'Invalid rating. Please enter a number between 1 and 5.'];
    }

    if (strlen($comment) > 1000) { // Limit comment length for security and storage
        return ['error' => 'Comment exceeds the maximum length (1000 characters).'];
    }

    // Data Sanitization - Prevent XSS and SQL Injection
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING); //  Sanitize string
    $comment = filter_var($comment, FILTER_SANITIZE_STRING); // Sanitize string
    $username = filter_var($username, FILTER_SANITIZE_STRING); // Sanitize string

    // Data Preparation -  (In a real application, this would likely involve
    // connecting to a database and inserting the data).

    $review_data = [
        'product_id' => $product_id,
        'rating' => $rating,
        'comment' => $comment,
        'username' => $username,
        'timestamp' => time() // Add a timestamp for ordering and tracking
    ];

    // In a real application, you'd store this data in a database
    // e.g.,  $db->insert('reviews', $review_data);

    return $review_data; // Return the review data.  This would be saved to the database.
}


/**
 * Displays a list of reviews for a product.
 *
 * @param array $reviews An array of review data (as returned by createReview).
 */
function displayReviews(array $reviews) {
  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<h2>Reviews for Product ID: " . $reviews[0]['product_id'] . "</h2>"; // Display product ID
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['username'] . ":</strong> " . $review['comment'] . " (" . $review['rating'] . "/5)";
    echo "</li>";
  }
  echo "</ul>";
}



// Example Usage:

// Create a review
$review = createReview("123", 5, "Great product!  I highly recommend it.", "John Doe");

if (isset($review['error'])) {
    echo "<p style='color:red;'>Error: " . $review['error'] . "</p>";
} else {
    echo "<p style='color:green;'>Review created successfully!</p>";
    echo "<p>Product ID: " . $review['product_id'] . "</p>";
    echo "<p>Rating: " . $review['rating'] . "</p>";
    echo "<p>Comment: " . $review['comment'] . "</p>";
    echo "<p>Username: " . $review['username'] . "</p>";
}

// Simulate multiple reviews for display
$reviews = [
    ['product_id' => '123', 'rating' => 4, 'comment' => 'Good value for money.', 'username' => 'Jane Smith'],
    ['product_id' => '123', 'rating' => 5, 'comment' => 'Excellent!', 'username' => 'Peter Jones'],
    ['product_id' => '456', 'rating' => 3, 'comment' => 'Average', 'username' => 'Sarah Brown']
];

displayReviews($reviews);
?>
