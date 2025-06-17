

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $name The name of the reviewer.
 * @param string $comment The review comment.
 * @param int    $rating   The rating given by the user (1-5).
 *
 * @return array An array containing the review data, or an error message if invalid input is provided.
 */
function create_review(string $product_id, string $name, string $comment, int $rating) {
    // Input validation
    if (empty($product_id)) {
        return ['error' => 'Product ID cannot be empty.'];
    }
    if (empty($name)) {
        return ['error' => 'Reviewer name cannot be empty.'];
    }
    if (empty($comment)) {
        return ['error' => 'Review comment cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Sanitize input (important for security) - this is a basic example, adapt for your needs
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $name       = filter_var($name, FILTER_SANITIZE_STRING);
    $comment    = filter_var($comment, FILTER_SANITIZE_STRING);


    // Store the review data (replace with database storage in a real application)
    $review = [
        'product_id' => $product_id,
        'name'       => $name,
        'comment'    => $comment,
        'rating'     => $rating,
        'date'       => date('Y-m-d H:i:s'), // Add a timestamp
    ];


    return $review;
}


/**
 * Display Reviews for a Product
 *
 * This function retrieves and displays reviews for a given product ID.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 *
 * @return void  Displays the reviews to the browser.
 */
function display_reviews(string $product_id) {
  // Placeholder for retrieving reviews from a database.
  // In a real application, you would query your database here.

  // Example:  Simulate fetching reviews from a database
  $reviews = [
      ['product_id' => '123', 'name' => 'John Doe', 'comment' => 'Great product!', 'rating' => 5, 'date' => '2023-10-26'],
      ['product_id' => '123', 'name' => 'Jane Smith', 'comment' => 'Could be better', 'rating' => 3, 'date' => '2023-10-25'],
      ['product_id' => '456', 'name' => 'Peter Jones', 'comment' => 'Excellent value', 'rating' => 4, 'date' => '2023-10-24']
  ];

  echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";

  if (empty($reviews)) {
      echo "<p>No reviews yet.</p>";
      return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
      echo "<li>";
      echo "<strong>" . $review['name'] . "</strong> - " . $review['comment'] . " (" . $review['rating'] . "/5) - " . $review['date'];
      echo "</li>";
  }
  echo "</ul>";
}

// Example Usage (Demonstration)

// Create a review
$new_review = create_review('123', 'Alice Brown', 'This is a fantastic product!', 5);

if (isset($new_review['error'])) {
    echo "<p style='color: red;'>Error creating review: " . $new_review['error'] . "</p>";
} else {
    echo "<p>Review created successfully: " . print_r($new_review, true) . "</p>";
}


// Display reviews for product ID '123'
display_reviews('123');
?>
