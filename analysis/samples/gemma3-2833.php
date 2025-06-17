

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier of the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @param int $rating (optional) The rating given for the item (1-5). Defaults to 0.
 * @param int $user_id (optional) The unique identifier of the user. If not provided, it will be automatically generated.
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully added, false otherwise.
 *   - 'message': A message indicating the status of the review operation.
 *   - 'review_id': The ID of the newly created review (if successful).
 *
 * @throws Exception if the database connection fails.
 */
function add_user_review(string $item_id, string $user_name, string $review_text, int $rating = 0, int $user_id = 0) {

    // **IMPORTANT: Replace this with your actual database connection code**
    // This is just a placeholder for demonstration.
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()];
    }

    // Check if user_id is provided; otherwise, generate one.
    if ($user_id == 0) {
        $user_id = $pdo->lastInsertId(); // Get the last inserted ID
    }


    // Sanitize inputs - IMPORTANT for security!
    $item_id = filter_var($item_id, FILTER_SANITIZE_STRING);
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

    // Validation -  Ensure rating is within the valid range.
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be between 1 and 5.'];
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating, user_id) VALUES (:item_id, :user_name, :review_text, :rating, :user_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':user_id', $user_id);

    // Execute the statement
    try {
        $stmt->execute();
        $review_id = $pdo->lastInsertId(); // Get the ID of the newly created review
        return ['success' => true, 'message' => 'Review submitted successfully.', 'review_id' => $review_id];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error submitting review: ' . $e->getMessage()];
    }
}


// Example Usage:
// $review_result = add_user_review('product123', 'John Doe', 'Great product!', 5);
// print_r($review_result);

?>
