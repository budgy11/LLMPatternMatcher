

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It uses a simple in-memory array to store reviews.  For production, 
 * you'd typically use a database.
 *
 * @param string $productName The name of the product the review is for.
 * @param string $user_id The ID of the user writing the review.
 * @param string $rating  The rating (e.g., 1-5 stars).
 * @param string $comment The user's review text.
 * 
 * @return array An array of reviews for the given product, or an empty array if no reviews exist.
 */
function create_user_review(string $productName, string $user_id, string $rating, string $comment) {
  // Sanitize input - VERY IMPORTANT for security!
  $productName = trim($productName);
  $user_id = trim($user_id);
  $rating = trim($rating);
  $comment = trim($comment);

  // Basic validation - Add more robust validation as needed
  if (empty($productName) || empty($user_id) || empty($rating) || empty($comment)) {
    return []; // Return empty array for invalid input
  }
  
  //  You'd typically validate $rating to ensure it's a valid number or a supported range
  //  For this simple example, we'll allow any string, but consider using a regex or numeric check
  
  // Create the review object
  $review = [
    'product_name' => $productName,
    'user_id' => $user_id,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => time() // Add a timestamp for sorting/filtering
  ];

  // Store the review -  In a real application, this would be a database insertion
  // For this example, we're using a simple array.
  $reviews[$productName][$user_id] = $review; 

  return $reviews;
}


/**
 * Retrieves all reviews for a given product.
 *
 * @param string $productName The name of the product.
 *
 * @return array An array of reviews for the product, or an empty array if no reviews exist.
 */
function get_reviews(string $productName) {
  $reviews = []; // Initialize an empty array

  // Get all reviews for the given product
  if (isset($reviews[$productName])) {
    $reviews[$productName] = array_map('unserialize', array_values($reviews[$productName]));
    return $reviews[$productName];
  } else {
    return [];
  }
}

/**
 * Updates an existing review
 *
 * @param string $productName The name of the product.
 * @param string $user_id The ID of the user.
 * @param string $rating  The new rating.
 * @param string $comment The new comment.
 *
 * @return bool True if the review was updated, false otherwise.
 */
function update_user_review(string $productName, string $user_id, string $rating, string $comment) {
    $reviews = get_reviews($productName); // Get the reviews
    if (empty($reviews)) {
        return false;
    }

    $review_to_update = null;
    foreach ($reviews as $key => $review) {
        if ($key == $user_id) {
            $review_to_update = $review;
            break;
        }
    }

    if ($review_to_update) {
        $review_to_update['rating'] = $rating;
        $review_to_update['comment'] = $comment;

        // Update in the in-memory array.  Again, use a database in a real application.
        $reviews[$productName][$user_id] = $review_to_update;

        return true;
    }
    return false;
}


/**
 * Deletes a user review.
 *
 * @param string $productName The name of the product.
 * @param string $user_id The ID of the user.
 *
 * @return bool True if the review was deleted, false otherwise.
 */
function delete_user_review(string $productName, string $user_id) {
    $reviews = get_reviews($productName);
    if (isset($reviews[$productName][$user_id])) {
        unset($reviews[$productName][$user_id]);
        return true;
    }
    return false;
}


// Example Usage:
// Initialize the reviews array
$reviews = [];

// Create a review
$new_review = create_user_review("Laptop X1", "user123", 5, "Great laptop! Fast and reliable.");
print_r($new_review);

// Get all reviews for "Laptop X1"
$all_reviews = get_reviews("Laptop X1");
print_r($all_reviews);

// Update the review
$update_success = update_user_review("Laptop X1", "user123", 4, "Still good, but battery could be better.");
if ($update_success) {
  echo "Review updated successfully.
";
} else {
  echo "Failed to update review.
";
}

// Get the updated reviews
$updated_reviews = get_reviews("Laptop X1");
print_r($updated_reviews);

// Delete the review
$delete_success = delete_user_review("Laptop X1", "user123");
if ($delete_success) {
  echo "Review deleted successfully.
";
} else {
  echo "Failed to delete review.
";
}

// Get the reviews after deletion
$reviews_after_delete = get_reviews("Laptop X1");
print_r($reviews_after_delete);
?>
