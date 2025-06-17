

<?php

/**
 * User Review Function - Handles user reviews for a product or item.
 *
 * This function allows you to:
 *  - Store user reviews in a database (optional).
 *  - Display reviews on a product page.
 *  - Implement a basic rating system.
 *
 * @param string $product_id The ID of the product or item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating     The rating given by the user (1-5).
 *
 * @return array An array containing the review data (stored in the database)
 *               and the display data for the review.
 */
function submit_review(string $product_id, string $user_name, string $review_text, int $rating)
{
    // --- Data Validation and Sanitization ---
    $user_name = trim($user_name); // Remove whitespace
    $review_text = trim($review_text);
    $rating = intval($rating); // Ensure rating is an integer

    if (empty($user_name) || empty($review_text)) {
        return ['success' => false, 'message' => 'User name and review text are required.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    // --- Database Interaction (Example - Adapt to your DB) ---
    // You'll need to replace this with your actual database connection and queries.

    //  Assuming you have a database table called 'reviews' with columns:
    //  - id (INT, PRIMARY KEY, AUTO_INCREMENT)
    //  - product_id (INT)
    //  - user_name (VARCHAR)
    //  - review_text (TEXT)
    //  - rating (INT)
    //  - created_at (TIMESTAMP)

    // This is just a placeholder; replace with your actual database code.
    try {
        // Connect to your database here (e.g., using PDO)
        // ...

        $db_connection = // your database connection object

        $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating, created_at)
                VALUES (:product_id, :user_name, :review_text, :rating, NOW())";

        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);
        $stmt->execute();

        $review_id = $db_connection->lastInsertId(); // Get the ID of the inserted row

        return ['success' => true, 'review_id' => $review_id, 'message' => 'Review submitted successfully!'];

    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error submitting review: ' . $e->getMessage()];
    }
}

/**
 * Function to display reviews for a product.
 *
 * @param string $product_id The ID of the product to display reviews for.
 */
function display_reviews(string $product_id)
{
    // --- Database Interaction (Example - Adapt to your DB) ---
    // This assumes you have a table called 'reviews' with the same columns as above.

    try {
        // Connect to your database here (e.g., using PDO)
        // ...

        $db_connection = // your database connection object

        $sql = "SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results

        if (empty($reviews)) {
            echo "<p>No reviews yet.</p>";
        } else {
            echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
            echo "<ul>";
            foreach ($reviews as $review) {
                echo "<li>";
                echo "<p><strong>User:</strong> " . htmlspecialchars($review['user_name']) . "</p>";
                echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
                echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
                echo "<p><em>Created at:</em> " . date('Y-m-d H:i:s', strtotime($review['created_at'])) . "</p>";
                echo "</li>";
            }
            echo "</ul>";
        }

    } catch (Exception $e) {
        echo "<p>Error fetching reviews: " . $e->getMessage() . "</p>";
    }
}


// --- Example Usage ---

// Submit a review
$result = submit_review('123', 'John Doe', 'This is a great product!', 5);
if ($result['success']) {
    echo "<p>Review submitted successfully!</p>";
} else {
    echo "<p>Error: " . $result['message'] . "</p>";
}


// Display reviews for product ID '123'
display_reviews('123');
?>
