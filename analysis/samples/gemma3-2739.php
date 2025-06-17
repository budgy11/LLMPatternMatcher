

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating    (Optional) The rating given by the user (1-5). Defaults to 0.
 * @param string $timestamp (Optional)  Timestamp for the review. Defaults to current time.
 *
 * @return bool True on successful review creation, false on failure.
 */
function create_review(string $item_id, string $user_name, string $review_text, int $rating = 0, string $timestamp = null)
{
  // Validate inputs (important for security and data integrity)
  if (empty($item_id) || empty($user_name) || empty($review_text)) {
    error_log("Missing required fields in review creation.");
    return false;
  }

  if ($rating < 1 || $rating > 5) {
    error_log("Invalid rating value.  Rating must be between 1 and 5.");
    return false;
  }

  // --- Data Validation ---
  // Add more robust validation here if needed.  Examples:
  // - Sanitize $review_text to prevent XSS attacks.
  // - Validate the $timestamp format if you're using it.

  // --- Database Interaction (Example - adjust to your database setup) ---
  try {
    // Assume you have a database connection established (e.g., $db)
    // and a 'reviews' table with columns: item_id, user_name, review_text, rating, timestamp

    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your credentials
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For better error handling

    $stmt = $db->prepare("INSERT INTO reviews (item_id, user_name, review_text, rating, timestamp) 
                         VALUES (:item_id, :user_name, :review_text, :rating, :timestamp)");

    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);
    if($timestamp === null){
        $stmt->bindParam(':timestamp', time()); // Use current timestamp
    } else {
        $stmt->bindParam(':timestamp', $timestamp);
    }


    $result = $stmt->execute();

    if ($result) {
      return true;
    } else {
      error_log("Failed to create review: " . print_r($stmt->errorInfo(), true)); // Log the error
      return false;
    }

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// --- Example Usage ---

// Create a review
if (create_review("product123", "John Doe", "Great product!  Highly recommended.", 5)) {
  echo "Review created successfully!";
} else {
  echo "Failed to create review.";
}

// Create a review with a timestamp
$timestamp = time() - 3600; // One hour ago
if (create_review("product456", "Jane Smith", "Okay, but could be better", 3, $timestamp)) {
    echo "
Review with timestamp created successfully!";
} else {
    echo "
Failed to create review with timestamp.";
}


// Display Reviews (Example - adjust to your display logic)
function display_reviews(string $item_id) {
    // Example - Fetch reviews from the database based on $item_id
    // This is just a placeholder - you'll need to adapt this to your database query
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your credentials
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT item_id, user_name, review_text, rating, timestamp FROM reviews WHERE item_id = :item_id");
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($reviews) > 0) {
        echo "<h2>Reviews for " . $item_id . "</h2>";
        foreach ($reviews as $review) {
            echo "<p><strong>User:</strong> " . $review['user_name'] . "</p>";
            echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
            echo "<p>" . $review['review_text'] . "</p>";
            echo "<p><strong>Timestamp:</strong> " . date("Y-m-d H:i:s", strtotime($review['timestamp'])) . "</p>";
            echo "<br>";
        }
    } else {
        echo "<p>No reviews found for this item.</p>";
    }
}

// Example of displaying reviews:
display_reviews("product123");
?>
