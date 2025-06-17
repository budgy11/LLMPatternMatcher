

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or item.
 * It includes input validation, sanitization, and storage of the review.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $rating     The rating given (e.g., 1-5).
 * @param string $comment    The user's comment about the product.
 * @param string $user_id    (Optional) The ID of the user submitting the review.  If not provided, a default ID is generated.
 * @return bool|string  Returns true on success, or an error message string on failure.
 */
function submit_review(string $product_id, string $rating, string $comment, string $user_id = null): bool|string
{
  // --- Input Validation & Sanitization ---

  // Check if rating is a valid number between 1 and 5
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return "Error: Rating must be a number between 1 and 5.";
  }

  // Trim whitespace from the comment
  $comment = trim($comment);

  // Sanitize the comment (prevent SQL injection) - IMPORTANT!
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);  //Remove HTML tags and potentially other unwanted characters
  // Consider using prepared statements for more robust security.


  // Check if user ID is provided
  if ($user_id === null) {
    $user_id = generate_unique_user_id();  //Implement this function - see example below
  }

  // --- Database Interaction (Example using a simple array - replace with your database logic) ---

  // In a real application, you'd connect to your database here.
  $reviews = load_reviews($product_id);  // Implement this function - see example below
  
  //Create a review object, you can define more properties as needed
  $review = [
      'product_id' => $product_id,
      'rating' => $rating,
      'comment' => $comment,
      'user_id' => $user_id,
      'timestamp' => time()  // Add a timestamp
  ];
  
  // Add the review to the reviews array.
  $reviews[] = $review;
    
  if (count($reviews) > 0) {
        return true;
  } else {
        return "Error: Failed to save review.";
  }
  
}


// --- Helper Functions (Implement these based on your needs) ---

/**
 * Generates a unique user ID (Replace with a more robust solution if needed)
 *
 * @return string A unique user ID.
 */
function generate_unique_user_id(): string
{
    return uniqid();  //A simple and effective way to generate unique IDs.
}


/**
 * Loads reviews for a given product ID from a storage (e.g., database, array).
 *
 * @param string $product_id The ID of the product.
 * @return array An array of reviews for the product.
 */
function load_reviews(string $product_id): array
{
    // --- Replace this with your database query ---
    // Example:
    // $conn = new mysqli("localhost", "username", "password", "database");
    // $result = $conn->query("SELECT * FROM reviews WHERE product_id = '$product_id'");
    // $reviews = $result->fetch_all(MYSQLI_ASSOC);
    // $conn->close();

    // Simple example using a static array:
    static $reviews = [];
    return $reviews;
}


// --- Example Usage ---
$product = "electronics_123";

// Successful submission
$result = submit_review($product, 4, "Great product!  Works as expected.");
if ($result) {
  echo "Review submitted successfully: " . $result . "
";
} else {
  echo "Error submitting review.
";
}

// Submission with error (invalid rating)
$result = submit_review($product, 6, "Too expensive!");
if ($result) {
  echo "Review submitted successfully: " . $result . "
";
} else {
  echo "Error submitting review: " . $result . "
";
}

?>
