

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It provides a basic framework and can be expanded upon to include features
 * like rating systems, authentication, and database integration.
 *
 * @param string $review_text  The review text entered by the user.
 * @param int    $user_id       The ID of the user submitting the review (optional, use session/cookie for authentication).
 * @param int    $product_id    The ID of the product the review is for.
 * @param int    $rating        The rating given for the product (e.g., 1-5).  Optional.
 *
 * @return array|string An array of review data or an error message if something went wrong.
 */
function create_review(string $review_text, int $user_id, int $product_id, int $rating = 0) {
  // Input validation - VERY IMPORTANT
  if (empty($review_text)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }

  if ($user_id <= 0) {
    return ['status' => 'error', 'message' => 'Invalid user ID. User ID must be greater than zero.'];
  }

  if ($product_id <= 0) {
    return ['status' => 'error', 'message' => 'Invalid product ID. Product ID must be greater than zero.'];
  }

  // Add more validation here, such as checking rating range if applicable.


  // In a real application, you'd connect to a database here.
  // For demonstration purposes, we'll just store the review in an array.

  // Create a review object (simulating a database record)
  $review = [
    'review_text' => $review_text,
    'user_id'     => $user_id,
    'product_id'  => $product_id,
    'rating'      => $rating,
    'created_at'  => time() // Timestamp of when the review was created
  ];

  //Return the review data
  return $review;
}



/**
 * Example Usage (for demonstration)
 */

// Example 1: Create a review
$review_data = create_review("This product is amazing!", 123, 456, 5);

if ($review_data['status'] === 'error') {
  echo "Error creating review: " . $review_data['message'] . "
";
} else {
  echo "Review created successfully:
";
  print_r($review_data); // Display the created review
}


// Example 2: Create a review with default rating
$review_data2 = create_review("Good product!", 456, 789);
if ($review_data2['status'] === 'error') {
  echo "Error creating review: " . $review_data2['message'] . "
";
} else {
  echo "Review created successfully:
";
  print_r($review_data2);
}


// Example 3: Demonstrating error handling (empty review text)
$review_data3 = create_review("", 789, 123);
if ($review_data3['status'] === 'error') {
  echo "Error creating review: " . $review_data3['message'] . "
";
}
?>
