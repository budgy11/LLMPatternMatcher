

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews
 * for a given product or item.
 *
 * @param string $product_id The ID of the product or item the review is for.
 * @param string $user_id    The ID of the user submitting the review.
 * @param string $rating    The rating (e.g., 1-5 stars).
 * @param string $comment   The review comment.
 * @param int    $review_id (Optional) The ID of the review to update or delete.
 * 
 * @return array An array containing the result of the operation (success/failure, message, and data).
 */
function create_user_review(string $product_id, string $user_id, string $rating, string $comment, int $review_id = 0) {
  // Input validation - crucial for security and data integrity
  if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
    return ['success' => false, 'message' => 'All fields are required.', 'data' => []];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'data' => []];
  }

  // **Replace this with your database interaction logic**
  // This is a placeholder - you'll need to adapt this to your database setup.
  // This example demonstrates how to create a new review.

  // Example using a hypothetical database connection ($db)
  // $db->query("INSERT INTO reviews (product_id, user_id, rating, comment)
  //            VALUES ('$product_id', '$user_id', '$rating', '$comment')");
  // $last_review_id = $db->lastInsertId();


  // Simulate a review insertion and return a review ID
  $last_review_id = 99; // Dummy ID for this example


  if($last_review_id == 0){
      return ['success' => false, 'message' => 'Error creating review.', 'data' => []];
  }

  return ['success' => true, 'message' => 'Review created successfully.', 'data' => ['review_id' => $last_review_id]];
}


// Example Usage:

// Create a new review
$result = create_user_review('product123', 'user456', '4', 'Great product, highly recommended!');
print_r($result); // Output: (checks if it's an array, shows the result)

// Update an existing review (assuming you have an update function)
//  function update_user_review(int $review_id, string $rating, string $comment) {
//    // ... database update logic ...
//  }

// Delete a review (assuming you have a delete function)
// function delete_user_review(int $review_id) {
//   // ... database delete logic ...
// }

?>
