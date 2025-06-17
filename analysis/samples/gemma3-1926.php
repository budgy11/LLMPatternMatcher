

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It includes basic data validation and sanitization.
 *
 * @param string $review_text The user's review text.
 * @param int $product_id The ID of the product the review is for.
 * @param int $user_id  (Optional) The ID of the user making the review.  If not provided, a new user is created.
 * @param int $rating The rating given for the product (e.g., 1-5).
 * @return array|string Returns an array on success, or an error message string on failure.
 */
function create_user_review(string $review_text, int $product_id, int $rating, int $user_id = null)
{
    // --- Validation and Sanitization ---
    $review_text = trim($review_text); // Remove leading/trailing whitespace
    if (empty($review_text)) {
        return "Error: Review text cannot be empty.";
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Error: Rating must be a number between 1 and 5.";
    }

    // --- Database Interaction (Simulated for this example) ---
    // In a real application, you would replace this with actual database queries.

    // Simulate a user object if the user_id is not provided
    $user = $user_id ? get_user_by_id($user_id) : create_new_user();

    // Example data for the review (in a real implementation, you'd use a proper database connection)
    $review_data = [
        'user_id' => $user ? $user['id'] : null, // User ID from the user object
        'product_id' => $product_id,
        'review_text' => $review_text,
        'rating' => $rating,
        'created_at' => date('Y-m-d H:i:s') // Timestamp
    ];


    // --- Save the Review ---
    $review_id = save_review($review_data);

    if ($review_id === null) {
        return "Error: Could not save review.";
    }

    return [
        'success' => true,
        'review_id' => $review_id
    ];
}


/**
 *  Simulated Database Functions (Replace with your actual database logic)
 */

/**
 *  Simulates getting a user by ID
 */
function get_user_by_id(int $user_id)
{
    // In a real application, query the database.
    // This is just a placeholder.
    // Returns a dummy user object.
    return [
        'id' => $user_id,
        'username' => 'user' . $user_id,
        'email' => 'user' . $user_id . '@example.com'
    ];
}


/**
 * Simulates creating a new user.
 */
function create_new_user()
{
    // In a real application, you'd insert the new user into the database.
    // This returns a dummy user object.
    return [
        'id' => 123,
        'username' => 'newuser',
        'email' => 'newuser@example.com'
    ];
}


/**
 *  Simulates saving a review to a database.  Returns the review id.
 */
function save_review(array $review_data)
{
  // In a real implementation, this would execute a SQL query to insert the review.
  // This is a placeholder.
    // Simulate a successful save.
    return 42; // Return a valid review ID (could be the primary key from the database).
}


/**
 *  Example usage:
 */
// Example 1: Successful review creation
$result = create_user_review("This product is amazing!", 1, 5);
if ($result) {
    echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
    echo $result . "
";
}


// Example 2: Invalid rating
$result = create_user_review("Good product.", 2, 6);
if ($result) {
    echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
    echo $result . "
";
}

// Example 3: Empty review text
$result = create_user_review("", 3, 4);
if ($result) {
    echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
    echo $result . "
";
}
?>
