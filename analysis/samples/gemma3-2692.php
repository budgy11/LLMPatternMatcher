

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews
 * for a given item or product.  It includes basic validation and sanitization.
 *
 * @param string $item_id The ID of the item or product to review.
 * @param string $user_id The ID of the user submitting the review.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $data     (Optional) An associative array containing all review data:
 *                         ['item_id' => '...', 'user_id' => '...', 'rating' => '...', 'comment' => '...']
 *
 * @return array An array containing the result of the operation.
 *               - 'success': true if the operation was successful.
 *               - 'message':  A success or error message.
 *               - 'review_id': The ID of the newly created review (if applicable).
 *               - 'review': The review data as an associative array.
 */
function create_review(string $item_id, string $user_id, string $rating, string $comment, array $data = []) {

  // Basic input validation and sanitization
  if (empty($item_id) || empty($user_id) || empty($rating) || empty($comment)) {
    return [
      'success' => false,
      'message' => 'Item ID, User ID, Rating, and Comment are required.',
      'review_id' => null,
      'review' => null
    ];
  }

  // Ensure rating is an integer.  Handle non-integer input gracefully.
  $rating = intval($rating);
  if ($rating < 1 || $rating > 5) {
    return [
      'success' => false,
      'message' => 'Rating must be between 1 and 5.',
      'review_id' => null,
      'review' => null
    ];
  }

  // Combine provided data with defaults (optional).  This allows for partial updates.
  $review_data = [];
  $review_data = array_merge($review_data, $review_data); //Merge

  $review_data['item_id'] = $item_id;
  $review_data['user_id'] = $user_id;



  // --- Database interaction (Replace this with your database logic) ---
  // This is a placeholder.  You'll need to adapt this to your database
  // setup (MySQL, PostgreSQL, etc.) and your data model.

  // Example using a hypothetical database connection
  $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class

  try {
    $sql = "INSERT INTO reviews (item_id, user_id, rating, comment) VALUES (:item_id, :user_id, :rating, :comment)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':item_id', $review_data['item_id']);
    $stmt->bindParam(':user_id', $review_data['user_id']);
    $stmt->bindParam(':rating', $review_data['rating']);
    $stmt->bindParam(':comment', $review_data['comment']);
    $stmt->execute();

    $review_id = $db->lastInsertId();

    return [
      'success' => true,
      'message' => 'Review created successfully.',
      'review_id' => $review_id,
      'review' => $review_data
    ];

  } catch (Exception $e) {
    // Handle database errors gracefully
    return [
      'success' => false,
      'message' => 'Error creating review: ' . $e->getMessage(),
      'review_id' => null,
      'review' => null
    ];
  }
}

/**
 *  Helper class to represent a database connection (placeholder).
 */
class DatabaseConnection {
    private $connection;

    public function __construct() {
        // In a real application, you'd establish a database connection here.
        // This is just a placeholder.
        $this->connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function execute() {
        return $this->connection->execute();
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}


// --- Example Usage ---
//  (This will need to be run within a PHP environment, e.g., a web server)
$result = create_review('product_123', 'user_456', 4, 'Great product!  Highly recommended.', []);
print_r($result);

$result = create_review('product_456', 'user_789', 3, 'It\'s okay, but could be better', ['rating' => 3, 'comment' => 'Just average.']);
print_r($result);
?>
