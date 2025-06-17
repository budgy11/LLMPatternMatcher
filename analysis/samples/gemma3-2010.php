

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It includes basic validation and data sanitization.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $author The name of the reviewer.
 * @param string $comment The review text.
 * @param int $rating  (Optional) The rating given by the user (1-5).  Defaults to 0 if missing.
 * @return array|string  Returns an array on success (with review ID) or a string on failure.
 */
function storeUserReview(string $productId, string $author, string $comment, int $rating = 0) {
  // **Validation**
  if (empty($productId)) {
    return "Error: Product ID is required.";
  }
  if (empty($author)) {
    return "Error: Author name is required.";
  }
  if (empty($comment)) {
    return "Error: Review comment is required.";
  }
  if ($rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  // **Sanitization (Basic - Enhance for Production)**
  $author = htmlspecialchars(trim($author)); // Protect against XSS
  $comment = htmlspecialchars(trim($comment));

  // **Data Handling -  Replace with Database Interaction in Production**
  // For this example, we'll use an array to simulate a database.
  $reviews = [
    "product123" => [
      "author" => "John Doe",
      "comment" => "Great product!",
      "rating" => 5,
      "review_id" => uniqid()  // Generate a unique review ID
    ],
    "product456" => [
      "author" => "Jane Smith",
      "comment" => "Could be better.",
      "rating" => 3,
      "review_id" => uniqid()
    ]
  ];

  // **Check if product exists in reviews array.**
  if (!isset($reviews[$productId])) {
    return "Error: Product not found or review already exists.";
  }


  // **Store the review (simulated database insertion)**
  $reviews[$productId]["author"] = $author;
  $reviews[$productId]["comment"] = $comment;
  $reviews[$productId]["rating"] = $rating;
  $reviews[$productId]["review_id"] = uniqid(); // Regenerate to avoid collisions

  return $reviews[$productId]["review_id"];
}

// **Example Usage**
$reviewId1 = storeUserReview("product123", "Alice Brown", "This is amazing!", 5);
echo "Review ID 1: " . $reviewId1 . "<br>";  // Output: Review ID 1: abcdefg...

$reviewId2 = storeUserReview("product456", "Bob Miller", "It's okay.", 3);
echo "Review ID 2: " . $reviewId2 . "<br>";

$reviewId3 = storeUserReview("product123", "Charlie Green", "Very good.", 4);
echo "Review ID 3: " . $reviewId3 . "<br>";

// Example of an error
$errorResult = storeUserReview("", "David Lee", "Invalid review", 2);
echo $errorResult . "<br>"; // Output: Error: Product ID is required.

$errorResult2 = storeUserReview("product123", "", "Invalid review", 2);
echo $errorResult2 . "<br>"; // Output: Error: Author name is required.
?>
