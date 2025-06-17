

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given item.
 *
 * @param string $item_name The name of the item being reviewed.
 * @param string $review_text The review text submitted by the user.
 * @param int $user_id (Optional) The ID of the user submitting the review.  Defaults to 0 (anonymous).
 * @return array An array containing:
 *   - 'success': True if the review was submitted successfully, False otherwise.
 *   - 'message': A message describing the result of the submission.
 *   - 'review': The submitted review text.
 *   - 'user_id': The ID of the user who submitted the review.
 */
function submit_review(string $item_name, string $review_text, int $user_id = 0) {
  // Input Validation (Basic - you should expand this for production)
  if (empty($item_name) || empty($review_text)) {
    return [
      'success' => false,
      'message' => 'Item name and review text cannot be empty.',
      'review' => $review_text,
      'user_id' => $user_id
    ];
  }

  //  You could add validation here to check review length, etc.
  //  Consider sanitizing the $review_text to prevent XSS attacks.

  // Store the review (Replace this with your database interaction)
  // This is a placeholder for storing the review in a database.
  // In a real application, you would connect to your database here.

  $review_id = generate_unique_id(); // Simple ID generation - replace with your actual ID logic.

  $review_data = [
    'item_name' => $item_name,
    'review_text' => $review_text,
    'user_id' => $user_id,
    'submission_date' => date('Y-m-d H:i:s'),
    'review_id' => $review_id
  ];

  // Simulate database insertion
  // In a real application, use a database query.
  // Example:  $result = mysqli_query($connection, "INSERT ...");

  //  For demonstration purposes, we'll just print the review data.
  echo "Review submitted!
";
  echo "Review ID: " . $review_id . "
";
  echo "Item Name: " . $item_name . "
";
  echo "Review Text: " . $review_text . "
";
  echo "User ID: " . $user_id . "
";

  return [
    'success' => true,
    'message' => 'Review submitted successfully.',
    'review' => $review_text,
    'user_id' => $user_id
  ];
}


/**
 *  Placeholder for generating a unique ID.  Replace with your actual logic.
 */
function generate_unique_id() {
  return rand(100000, 999999); // Simple random ID
}


// --- Example Usage ---

// Submit a review from a user (user_id 123)
$review_result = submit_review('Awesome Product', 'This product is amazing!', 123);
if ($review_result['success']) {
  echo "Review submitted successfully (User 123).
";
} else {
  echo "Error submitting review (User 123): " . $review_result['message'] . "
";
}

// Submit an anonymous review
$review_result = submit_review('Mediocre Item', 'It was okay, I guess.');
if ($review_result['success']) {
  echo "Review submitted successfully (Anonymous).
";
} else {
  echo "Error submitting review (Anonymous): " . $review_result['message'] . "
";
}

// Try submitting an empty review
$review_result = submit_review('', 'This is a test.');
if ($review_result['success']) {
  echo "Review submitted successfully.
";
} else {
  echo "Error submitting review: " . $review_result['message'] . "
";
}


?>
