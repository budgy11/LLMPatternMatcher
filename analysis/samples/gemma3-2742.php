

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It includes basic data validation and error handling.
 *
 * @param string $review_text The review text submitted by the user.
 * @param int    $product_id  The ID of the product the review is for.
 * @param int    $user_id     The ID of the user submitting the review (optional, for authentication).
 * @param int    $rating      The rating given by the user (e.g., 1-5).
 *
 * @return array An array containing the result of the operation:
 *              - 'success' => true if the operation was successful, false otherwise.
 *              - 'message' => A message describing the result (e.g., "Review created successfully", "Error saving review").
 *              - 'review_id' => The ID of the newly created review (if successful).
 *              - 'review' => The review object (if successful).
 */
function create_review(string $review_text, int $product_id, int $user_id = null, int $rating = null)
{
  // **Input Validation**
  if (empty($review_text)) {
    return [
      'success' => false,
      'message' => 'Review text cannot be empty.',
      'review_id' => null,
      'review' => null
    ];
  }

  if (!is_numeric($product_id)) {
    return [
      'success' => false,
      'message' => 'Invalid product ID.',
      'review_id' => null,
      'review' => null
    ];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return [
      'success' => false,
      'message' => 'Invalid rating. Rating must be between 1 and 5.',
      'review_id' => null,
      'review' => null
    ];
  }


  // **Database Interaction (Replace with your actual database connection)**
  // This is a simplified example using a placeholder database connection.
  // In a real application, you'd use PDO, MySQLi, or a similar mechanism.
  $db_host = 'localhost';
  $db_name = 'your_database_name';
  $db_user = 'your_username';
  $db_password = 'your_password';

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
  } catch (PDOException $e) {
    return [
      'success' => false,
      'message' => 'Database connection error: ' . $e->getMessage(),
      'review_id' => null,
      'review' => null
    ];
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (:product_id, :user_id, :rating, :review_text)";

  // Prepare the statement
  $stmt = $pdo->prepare($sql);

  // Bind the parameters
  $stmt->bindParam(':product_id', $product_id);
  if ($user_id) {
    $stmt->bindParam(':user_id', $user_id);
  }
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':review_text', $review_text);

  // Execute the statement
  $stmt->execute();

  // Get the last inserted ID
  $review_id = $pdo->lastInsertId();

  // Close the statement and connection
  $stmt = null;
  $pdo = null;


  return [
    'success' => true,
    'message' => 'Review created successfully.',
    'review_id' => $review_id,
    'review' => [
      'product_id' => $product_id,
      'user_id' => $user_id,
      'rating' => $rating,
      'review_text' => $review_text,
      'id' => $review_id
    ]
  ];
}


// **Example Usage**
// Simulate a review submission

// Create a review
$review_result = create_review('This product is amazing!', 123, 12, 5);

if ($review_result['success']) {
  echo "Review created successfully. Review ID: " . $review_result['review_id'] . "<br>";
  print_r($review_result['review']);  // Prints the review details
} else {
  echo "Error creating review: " . $review_result['message'] . "<br>";
}


//  Example demonstrating error handling
$review_result = create_review('', 456, 78, 3);
if ($review_result['success']) {
    echo "Review created successfully. Review ID: " . $review_result['review_id'] . "<br>";
} else {
    echo "Error creating review: " . $review_result['message'] . "<br>";
}
?>
