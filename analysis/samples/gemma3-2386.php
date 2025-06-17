

<?php

/**
 * User Review Function - Simple Implementation
 *
 * This function allows you to create and display user reviews for a given product.
 * It handles basic data sanitization and validation.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $name The reviewer's name.
 * @param string $comment The review text.
 *
 * @return array|string An array containing the review data if successful,
 *                     or an error message string if there's an issue.
 */
function createReview(string $product_id, string $name, string $comment) {
  // Validate input - Basic sanity checks
  if (empty($product_id)) {
    return "Error: Product ID cannot be empty.";
  }
  if (empty($name)) {
    return "Error: Reviewer name cannot be empty.";
  }
  if (empty($comment)) {
    return "Error: Review comment cannot be empty.";
  }

  // Sanitize input -  Escape HTML to prevent XSS
  $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
  $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');


  //  You would normally store this in a database here.  For demonstration, 
  //  we'll just store it in an array.
  $review = [
    'product_id' => $product_id,
    'name' => $name,
    'comment' => $comment,
    'timestamp' => time() // Add a timestamp
  ];

  return $review;
}



/**
 * Function to display a single review
 * @param array $review The review data
 */
function displayReview(array $review) {
    echo "<p><strong>Product ID:</strong> " . $review['product_id'] . "</p>";
    echo "<p><strong>Reviewer:</strong> " . $review['name'] . "</p>";
    echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
    echo "<p><strong>Timestamp:</strong> " . date("Y-m-d H:i:s", strtotime($review['timestamp'])) . "</p>";
}



// --- Example Usage ---

// Create a review
$newReview = createReview("123", "John Doe", "This is a fantastic product!");

if (is_array($newReview)) {
  // Review was created successfully
  echo "<h2>New Review Created:</h2>";
  displayReview($newReview);

  // Example: Display all reviews for product 123 (Assuming you have a function to fetch them from a database)
  // You would replace this with your actual database query.
  //$reviews = fetchReviewsFromDatabase($product_id);
  //displayReviews($reviews);
} else {
  // There was an error creating the review
  echo "<p>Error: " . $newReview . "</p>";
}



/**
 * Placeholder function to simulate fetching reviews from a database.
 * Replace this with your actual database query.
 *
 * @param string $product_id
 * @return array
 */
function fetchReviewsFromDatabase(string $product_id) {
  //  In a real application, this would query your database.
  //  For demonstration, we'll just return some dummy reviews.
  if ($product_id == "123") {
    return [
      [
        'product_id' => '123',
        'name' => 'John Doe',
        'comment' => 'This is a fantastic product!',
        'timestamp' => time()
      ],
      [
        'product_id' => '123',
        'name' => 'Jane Smith',
        'comment' => 'Great value for the price.',
        'timestamp' => time() - 3600 // One hour ago
      ]
    ];
  } else {
    return []; // Return an empty array if the product ID is not found.
  }
}


/**
 * Placeholder function to display a list of reviews
 * @param array $reviews
 */
function displayReviews(array $reviews) {
    echo "<h3>All Reviews for Product ID " . $reviews[0]['product_id'] . ":</h3>";
    echo "<ul>";
    foreach ($reviews as $review) {
        displayReview($review);
    }
    echo "</ul>";
}

?>
