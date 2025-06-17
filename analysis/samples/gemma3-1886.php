

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews for a product or item.
 * It takes user input and stores it in a database (or another storage mechanism).
 *
 * @param string $productId The unique identifier of the product/item being reviewed.
 * @param string $username The username of the user submitting the review.
 * @param string $rating A numeric rating from 1 to 5 (or a similar range).
 * @param string $comment The user's written review.
 * @param string $storageMethod  Method to store the review: 'db', 'file', 'memory' (default)
 * @return bool True if the review was successfully stored, false otherwise.
 */
function storeUserReview(string $productId, string $username, string $rating, string $comment, string $storageMethod = 'memory') {
    // Input validation (basic) - Enhance this for production
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Invalid review data. Missing required fields.");
        return false;
    }

    if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
        error_log("Invalid rating. Rating must be a number between 1 and 5.");
        return false;
    }

    // Data sanitization (Very important to prevent XSS and SQL Injection)
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);


    // ----- Storage Logic -----
    switch ($storageMethod) {
        case 'db':
            // Replace with your database connection and query logic
            // Example (Conceptual - Adapt to your DB setup)
            $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
            $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->execute([$productId, $username, $rating, $comment]);
            return true;

        case 'file':
            // Write the review to a file.  Requires careful security considerations.
            $filename = "reviews_" . $productId . ".txt";
            $reviewData = "Product ID: " . $productId . "
Username: " . $username . "
Rating: " . $rating . "
Comment: " . $comment . "
---
";
            file_put_contents($filename, $reviewData, FILE_APPEND); // Append to file
            return true;

        case 'memory':
            // Store the review in a simple array (for demonstration only).
            // In a real application, you'd likely store this in a session or cache.
            $reviews = [];
            $reviews[$productId][] = [
                'productId' => $productId,
                'username' => $username,
                'rating' => $rating,
                'comment' => $comment,
            ];
            return true;

        default:
            error_log("Unsupported storage method: " . $storageMethod);
            return false;
    }
}


// ----- Example Usage -----

// Store a review in memory
if (storeUserReview('product123', 'john_doe', 4, 'Great product, highly recommend!', 'memory')) {
    echo "Review stored in memory successfully!
";
} else {
    echo "Failed to store review in memory.
";
}

// Store a review in a file
if (storeUserReview('product456', 'jane_smith', 5, 'Excellent service!', 'file')) {
    echo "Review stored in file successfully!
";
} else {
    echo "Failed to store review in file.
";
}


//  Example using the database -  (Remember to adapt to your database)
// storeUserReview('product789', 'peter_jones', 3, 'It was okay.', 'db');
?>
