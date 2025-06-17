

<?php

/**
 *  User Review Function
 *
 *  This function allows you to store and retrieve user reviews for a given item.
 *  It includes basic validation and error handling.
 *
 *  @param string $item_id  The unique identifier for the item being reviewed.
 *  @param string $user_name  The name of the user submitting the review.
 *  @param string $review_text The user's review text.
 *  @param int $rating (Optional) The rating provided by the user (1-5). Defaults to 0.
 *
 *  @return array|false  An array containing the review ID, user name, review text,
 *                       and rating if the review was successfully created.
 *                       Returns false if there was an error.
 */
function createReview(string $item_id, string $user_name, string $review_text, int $rating = 0)
{
    // **Validation:**
    if (empty($item_id)) {
        error_log("Error: Item ID cannot be empty.");
        return false;
    }
    if (empty($user_name)) {
        error_log("Error: User name cannot be empty.");
        return false;
    }
    if (empty($review_text)) {
        error_log("Error: Review text cannot be empty.");
        return false;
    }

    // Validation for rating (optional)
    if ($rating < 1 || $rating > 5) {
        error_log("Error: Rating must be between 1 and 5.");
        return false;
    }

    // **Database Interaction (Replace with your database connection logic)**
    try {
        // Assuming you have a database connection established: $db
        $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); //Example - replace with your connection details

        $stmt = $db->prepare("INSERT INTO reviews (item_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([$item_id, $user_name, $review_text, $rating]);
        $review_id = $db->lastInsertId(); // Get the ID of the newly inserted row
        return [
            'review_id' => $review_id,
            'item_id' => $item_id,
            'user_name' => $user_name,
            'review_text' => $review_text,
            'rating' => $rating
        ];
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

// Example Usage (Demonstration):
$review = createReview("item123", "John Doe", "This is a great product!");
if ($review) {
    echo "Review created successfully! Review ID: " . $review['review_id'] . "
";
} else {
    echo "Failed to create review.
";
}


$review2 = createReview("item456", "Jane Smith", "Excellent value for money.", 5);
if ($review2) {
    echo "Review created successfully! Review ID: " . $review2['review_id'] . "
";
} else {
    echo "Failed to create review.
";
}

?>
