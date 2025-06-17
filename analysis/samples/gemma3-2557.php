

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating     The rating given by the user (e.g., 1-5).
 * @param string $db_connection A database connection object.  This is crucial!
 *
 * @return bool True on success, false on failure (e.g., database connection error).
 */
function store_user_review(string $item_id, string $user_name, string $review_text, int $rating, object $db_connection) {
    // Sanitize and validate inputs -  VERY IMPORTANT!
    $item_id = filter_var($item_id, FILTER_SANITIZE_STRING); // Remove potentially harmful characters
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);
    $rating = intval($rating); // Ensure rating is an integer.  Important for database safety.

    if (empty($item_id) || empty($user_name) || empty($review_text)) {
        error_log("Missing review data.  item_id: " . $item_id . ", user_name: " . $user_name . ", review_text: " . $review_text);
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating provided. Rating: " . $rating);
        return false;
    }


    // Prepare the SQL statement - Use prepared statements to prevent SQL injection!
    $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating)
            VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $item_id, $user_name, $review_text);

    // Execute the statement
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error inserting review: " . $stmt->error);
        return false;
    }

    // Close the statement and connection (good practice)
    $stmt->close();
    // $db_connection->close(); // Don't close the connection here.  Keep it open for other requests.
}


/**
 * Function to display all reviews for a given item
 *
 * @param string $item_id The item ID.
 * @param object $db_connection  A database connection object.
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function get_reviews(string $item_id, object $db_connection) {
    $sql = "SELECT * FROM reviews WHERE item_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("s", $item_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}



// --- Example Usage (replace with your actual database connection) ---
// Create a dummy database connection (for demonstration only)
class MockDBConnection {
    public function __construct() {}

    public function prepare(string $sql) {
        // Simulate prepare function.  In a real application, this would use PDO or MySQLi.
        // In this example, we don't actually prepare the query; just return a dummy object.
        return new MockStatement();
    }

    public function execute() {
        return new MockResult();
    }

    public function __destruct() {}
}

class MockStatement {
  public function __construct() {}
}

class MockResult {
  public function get_result() {
      return new MockRow();
  }
}

class MockRow {
    public $data = [
        'item_id' => 'item123',
        'user_name' => 'John Doe',
        'review_text' => 'Great product!',
        'rating' => 5
    ];

    public function fetch_assoc() {
        return $this;
    }
}

$db_connection = new MockDBConnection();

// Store a review
$item_id = 'item123';
$user_name = 'Jane Smith';
$review_text = 'Excellent value for money.';
$rating = 4;

if (store_user_review($item_id, $user_name, $review_text, $rating, $db_connection)) {
    echo "Review submitted successfully!
";
} else {
    echo "Failed to submit review.
";
}

// Get and display reviews
$reviews = get_reviews($item_id, $db_connection);

if (!empty($reviews)) {
    echo "Reviews for " . $item_id . ":
";
    foreach ($reviews as $review) {
        echo "  User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Review: " . $review['review_text'] . "
";
    }
} else {
    echo "No reviews found for " . $item_id . "
";
}

?>
