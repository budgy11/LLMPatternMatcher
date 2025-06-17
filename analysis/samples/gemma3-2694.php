

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and potentially manage user reviews.
 * For simplicity, this example focuses on creating and displaying reviews.
 * 
 * @param string $productName The name of the product the review is for.
 * @param string $reviewText The text of the review.
 * @param string $reviewerName The name of the reviewer.
 * @param int $rating (Optional) -  Rating from 1 to 5. Defaults to 0 if not provided.
 * 
 * @return array An array containing the review ID, or an error message if creation fails.
 */
function createReview(string $productName, string $reviewText, string $reviewerName, int $rating = 0) {
  // Basic input validation
  if (empty($productName)) {
    return ["error" => "Product name cannot be empty."];
  }
  if (empty($reviewText)) {
    return ["error" => "Review text cannot be empty."];
  }
  if (empty($reviewerName)) {
    return ["error" => "Reviewer name cannot be empty."];
  }
  if ($rating < 1 || $rating > 5) {
    return ["error" => "Rating must be between 1 and 5."];
  }


  // In a real application, you would store this in a database.
  // For this example, we'll store it in an array.
  $reviewId = generateUniqueId(); //  Simulate generating a unique ID
  $review = [
    "review_id" => $reviewId,
    "product_name" => $productName,
    "review_text" => $reviewText,
    "reviewer_name" => $reviewerName,
    "rating" => $rating,
    "timestamp" => time() // Add timestamp for ordering
  ];

  // Store the review (simulated)
  storeReview($review);  // Function to save to a database in a real application

  return $review;
}


/**
 * Simulate generating a unique ID.
 * In a real application, use a database sequence or UUID.
 *
 * @return string A unique ID.
 */
function generateUniqueId() {
  return uniqid();
}


/**
 * Simulate storing the review in a database.
 * In a real application, this would be your database insertion logic.
 *
 * @param array $review The review data to store.
 */
function storeReview(array $review) {
  // This is a placeholder. Replace with your database logic.
  echo "Storing review: " . json_encode($review) . "
";
  // Example using mysqli (needs a database connection)
  // $db = new mysqli("localhost", "username", "password", "database");
  // if ($db->connect_error) {
  //   die("Connection failed: " . $db->connect_error);
  // }
  // $sql = "INSERT INTO reviews (product_name, review_text, reviewer_name, rating, timestamp)
  //         VALUES ('" . $review['product_name'] . "', '" . $review['review_text'] . "', '" . $review['reviewer_name'] . "', " . $review['rating'] . ", '" . $review['timestamp'] . "')";
  // if ($db->query($sql) === TRUE) {
  //   echo "New record created successfully";
  // } else {
  //   echo "Error: " . $db->error;
  // }
  // $db->close();
}

// --- Example Usage ---

// Create a review
$newReview = createReview("Awesome Product", "This is an amazing product!", "John Doe", 5);

if (isset($newReview["error"])) {
  echo "Error creating review: " . $newReview["error"] . "
";
} else {
  echo "Review created successfully with ID: " . $newReview["review_id"] . "
";
}

// Create another review
$anotherReview = createReview("Great Service", "The service was excellent!", "Jane Smith"); // Uses default rating of 0

if (isset($anotherReview["error"])) {
  echo "Error creating review: " . $anotherReview["error"] . "
";
} else {
  echo "Review created successfully with ID: " . $anotherReview["review_id"] . "
";
}

// Attempt to create a review with invalid input
$invalidReview = createReview("", "This is bad", "Bad Reviewer");
if (isset($invalidReview["error"])) {
  echo "Error creating review: " . $invalidReview["error"] . "
";
}
?>
