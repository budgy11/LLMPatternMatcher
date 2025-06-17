

<?php

/**
 *  User Review Function
 *
 *  This function allows you to create, read, update, and delete user reviews.
 *
 *  @param string $review_text The text of the review.
 *  @param int $product_id The ID of the product the review is for.
 *  @param int $user_id The ID of the user submitting the review. (Optional - defaults to the logged-in user)
 *  @param int $rating The rating given for the review (e.g., 1-5).
 *  @return bool|string True on success, error message on failure.
 */
function create_user_review(string $review_text, int $product_id, int $user_id = null, int $rating = null) {
  // --- Input Validation & Sanitization ---
  $review_text = trim($review_text); // Remove leading/trailing whitespace
  if (empty($review_text)) {
    return "Error: Review text cannot be empty.";
  }

  if ($rating === null || $rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  //  You might want to add more robust input validation and sanitization here,
  //  e.g., using a library like Sanitize or filtering out potentially malicious characters.

  // --- Database Interaction (Example using a hypothetical database connection) ---
  // Replace this with your actual database connection and query logic.
  $db = new DatabaseConnection(); // Assume this class handles database connection

  $query = "INSERT INTO reviews (product_id, user_id, review_text, rating) 
            VALUES (" . $product_id . ", " . ($user_id ?? 0) . ", '" . $review_text . "', " . $rating . ")";

  try {
    $result = $db->execute_query($query);
    if ($result) {
      return true; // Success
    } else {
      return "Error: Failed to insert review into database.";
    }
  } catch (Exception $e) {
    return "Error: Database error: " . $e->getMessage();
  }
}


/**
 *  Example of a simplified DatabaseConnection class (replace with your actual implementation)
 */
class DatabaseConnection {
  private $host = "localhost";
  private $username = "your_username";
  private $password = "your_password";
  private $database = "your_database";

  public function execute_query($query) {
    // Replace this with your database connection and query execution logic.
    // This is just a placeholder to demonstrate the workflow.
    try {
        // In a real implementation, you'd use a PDO or MySQLi connection here.
        // Example (using placeholder):
        // $conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // return $stmt; 
        echo "Executing query: " . $query;
        return true; // Simulate success
    } catch (Exception $e) {
        throw new Exception("Database query error: " . $e->getMessage());
    }
  }
}



// --- Example Usage ---
$product_id = 123;
$review_text = "This product is amazing!  I highly recommend it.";
$user_id = 1; // Assuming user with ID 1 is logged in

$result = create_user_review($review_text, $product_id, $user_id, 5);

if ($result === true) {
  echo "Review created successfully! " . $result . "<br>";
} else {
  echo "Error creating review: " . $result . "<br>";
}


// Example without logged in user (uses the default user - typically the current user).
$result2 = create_user_review("Another good product.", $product_id, null, 4);
if($result2 === true) {
  echo "Review created (anonymous) successfully! " . $result2 . "<br>";
} else {
  echo "Error creating review: " . $result2 . "<br>";
}
?>
