

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the reviewer (e.g., 1-5 stars).
 * @param string $comment The user's written review.
 * @return array|string Returns an array with the review details on success, or an error message on failure.
 */
function submit_review(string $product_id, string $username, string $rating, string $comment)
{
  // **Security Considerations (Important!)**

  // 1. Input Validation and Sanitization:  This is crucial to prevent SQL injection and XSS attacks.
  //    This example provides basic validation, but you *MUST* expand upon it for a real application.
  $rating = filter_var($rating, FILTER_VALIDATE_INT, array("min" => 1, "max" => 5)); // Ensure rating is an integer between 1 and 5
  if ($rating === false) {
    return "Error: Invalid rating.  Please enter a number between 1 and 5.";
  }

  $comment = trim($comment);  // Remove leading/trailing whitespace

  // 2.  Escaping for Output (XSS protection):  Use htmlspecialchars() to escape the comment
  $escaped_comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

  // **Database Interaction (Replace with your database logic)**
  // This is a simplified example using a mock database.
  // In a real application, use a proper database connection and query.

  // Mock Database (Replace with your database connection and query logic)
  $db = new MockDatabase();

  // Add the review to the database
  try {
    $db->addReview($product_id, $username, $rating, $escaped_comment);
    return ['success' => true, 'message' => "Review submitted successfully!"];

  } catch (Exception $e) {
    return ['success' => false, 'message' => "Error submitting review: " . $e->getMessage()];
  }


}

/**
 * Mock Database Class (For demonstration purposes only)
 */
class MockDatabase
{
    private $reviews = [];

    public function addReview(string $product_id, string $username, string $rating, string $comment)
    {
        // In a real database, you'd build and execute an SQL query here.
        // This is just a placeholder for demonstration.

        $this->reviews[$product_id][] = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => time()
        ];
    }
}
?>
