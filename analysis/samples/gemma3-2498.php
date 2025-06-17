<!DOCTYPE html>
<html>
<head>
  <title>User Review Form</title>
</head>
<body>

  <h1>Submit a Review</h1>

  <form action="process_review.php" method="post">
    <label for="userId">User ID:</label>
    <input type="number" id="userId" name="userId" required><br><br>

    <label for="reviewText">Review Text:</label><br>
    <textarea id="reviewText" name="reviewText" rows="4" cols="50" required></textarea><br><br>

    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select><br><br>

    <input type="submit" value="Submit Review">
  </form>

</body>
</html>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's review comment.
 * @param string $reviewId (Optional) The ID of the review to update.  If null, a new review is created.
 *
 * @return array  An array containing:
 *                - 'success': true if the operation was successful, false otherwise.
 *                - 'message': A message describing the outcome.
 *                - 'reviewId': (Optional) The ID of the newly created or updated review.
 *
 */
function createOrUpdateReview(string $productId, string $username, string $rating, string $comment, ?string $reviewId = null): array {
  // Validate inputs (basic - can be expanded for more robust validation)
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Invalid rating. Rating must be a number between 1 and 5.'];
  }

  if (empty($username) || empty($comment)) {
    return ['success' => false, 'message' => 'Username and comment are required fields.'];
  }

  //  Simulate a database interaction (replace with your actual database code)
  //  This is just an example - you'll need to connect to your database.

  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'createdAt' => date('Y-m-d H:i:s'), // Example: timestamp
  ];

  if ($reviewId) {
    // Update existing review
    // Assume you have a way to look up the review by ID in your database
    // This is a simplified example - replace with your database query.
    // Example:
    // $existingReview = getReviewById($reviewId);
    // if ($existingReview) {
    //     // Update the existing review data
    // }
    // else {
    //   return ['success' => false, 'message' => 'Review not found.'];
    // }
    return ['success' => true, 'message' => 'Review updated successfully.', 'reviewId' => $reviewId];

  } else {
    // Create new review
    // Example:
    // $newReviewId = insertReviewIntoDatabase($review);
    // return ['success' => true, 'message' => 'Review created successfully.', 'reviewId' => $newReviewId];
    return ['success' => true, 'message' => 'Review created successfully.', 'reviewId' => uniqid()]; // Generate a unique ID
  }
}

// Example Usage:
// Create a new review
$result = createOrUpdateReview('product123', 'JohnDoe', 4, 'Great product, highly recommended!');
print_r($result);

// Update an existing review (assuming reviewId 'review456')
$result = createOrUpdateReview('product123', 'JaneSmith', 5, 'Excellent value for money', 'review456');
print_r($result);

?>
