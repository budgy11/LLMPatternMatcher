

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 * It includes basic validation, sanitization, and storage.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $product_id The ID of the product being reviewed.  (Optional, but recommended for relational data)
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $username, string $rating, string $comment, string $product_id = "") {
    // --- Validation ---
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($rating)) {
        $errors[] = "Rating cannot be empty.";
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = "Rating must be a number between 1 and 5.";
    }
    if (empty($comment)) {
        $errors[] = "Comment cannot be empty.";
    }

    if (empty($errors)) {
        // --- Sanitize ---
        $username = trim($username);
        $rating = (int) $rating; // Convert to integer
        $comment = trim($comment);

        // --- Store Review (Simulated Database) ---
        //  Replace this with your actual database storage logic.
        //  This example uses a simple array.

        $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : []; // Load from session
        $review_id = count($reviews) + 1;

        $review = [
            'review_id' => $review_id,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => date('Y-m-d H:i:s'), // Add timestamp
            'product_id' => $product_id // Include product ID
        ];

        $reviews[] = $review;
        $_SESSION['reviews'] = $reviews; // Store in session


        return true;
    } else {
        // --- Handle Errors ---
        return false;
    }
}



/**
 * Display User Reviews (Example)
 */
function displayUserReviews() {
    global $reviews;  // Access the global $reviews array

    if (!empty($reviews)) {
        echo "<h2>User Reviews</h2>";
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>";
            echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
            echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
            echo "<strong>Timestamp:</strong> " . $review['timestamp'] . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No reviews available.</p>";
    }
}

// --- Example Usage ---

// Initialize the reviews array (use session for persistence)
if (!isset($_SESSION['reviews'])) {
    $_SESSION['reviews'] = [];
}


// Example 1: Store a review
$success = storeUserReview("John Doe", 4, "Great product!", "123");
if ($success) {
    echo "<p>Review stored successfully!</p>";
} else {
    echo "<p>Error storing review.</p>";
}


// Example 2: Display the reviews
displayUserReviews();


?>
