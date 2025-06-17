

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 * It handles validation, sanitization, and storage (simplified for demonstration).
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param string $username  The username of the user submitting the review (optional).
 * @param string $rating   The rating (1-5) submitted by the user (optional).
 * @param string $storageMethod  'file' or 'database'.  Determines how reviews are stored.
 *
 * @return array An array containing the review data (success or error messages).
 */
function submitReview(string $productId, string $reviewText, string $username = null, int $rating = null, string $storageMethod = 'file')
{
    // Validation and Sanitization
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.'];
    }
    if ($rating === null || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating.  Please enter a number between 1 and 5.'];
    }

    // Sanitize input (Basic - improve for production)
    $reviewText = htmlspecialchars(trim($reviewText));  // Sanitize for HTML output

    // Store the review
    $reviewData = [
        'productId' => $productId,
        'reviewText' => $reviewText,
        'username' => $username,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for ordering
    ];

    if ($storageMethod === 'file') {
        // Save to a file (basic example - adapt to your needs)
        $filename = "reviews_" . $productId . ".txt";
        file_put_contents($filename, json_encode($reviewData));
        return ['success' => true, 'message' => 'Review submitted successfully.'];
    } elseif ($storageMethod === 'database') {
        // Save to a database (example using a placeholder)
        // You'll need to connect to your database and perform the insert query here
        // Example:
        // $db = new DatabaseConnection();
        // $stmt = $db->prepare("INSERT INTO reviews (productId, reviewText, username, rating, timestamp) VALUES (?, ?, ?, ?, ?)");
        // $stmt->bind_param("ssisii", $productId, $reviewText, $username, $rating, $timestamp);
        // $stmt->execute();
        // $stmt->close();
        return ['success' => true, 'message' => 'Review submitted successfully (to database - placeholder).'];
    } else {
        return ['success' => false, 'message' => 'Invalid storage method.  Choose "file" or "database".'];
    }
}


/**
 * Display Reviews Function
 *
 * Displays reviews for a given product.
 *
 * @param string $productId The ID of the product.
 * @param string $storageMethod 'file' or 'database'.
 */
function displayReviews(string $productId, string $storageMethod = 'file')
{
    // Fetch reviews based on the storage method
    $reviews = [];

    if ($storageMethod === 'file') {
        $filename = "reviews_" . $productId . ".txt";
        if (file_exists($filename)) {
            $fileContent = file_get_contents($filename);
            $reviews = json_decode($fileContent, true); // Decode as an associative array
            if ($reviews === null) {
                echo "<p>Error reading reviews from file.</p>";
            }
        } else {
            echo "<p>No reviews found for this product.</p>";
        }

    } elseif ($storageMethod === 'database') {
        // Replace with your database query logic
        // Example:
        // $db = new DatabaseConnection();
        // $stmt = $db->prepare("SELECT * FROM reviews WHERE productId = ? ORDER BY timestamp DESC");
        // $stmt->bind_param("s", $productId);
        // $stmt->execute();
        // $result = $stmt->get_result();

        // while ($row = $result->fetch_assoc()) {
        //     $reviews[] = $row;
        // }
        // $stmt->close();

        echo "<p>Database review display placeholder - implement your query here.</p>";
    } else {
        echo "<p>Invalid storage method.</p>";
    }

    // Display the reviews
    echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . $review['username'] . "</strong> - " . $review['reviewText'] . " (" . $review['rating'] . "/5) - " . date("Y-m-d H:i:s", strtotime($review['timestamp']));
            echo "</li>";
        }
        echo "</ul>";
    }
}



// --- Example Usage ---
//Submit a review:
$result = submitReview('123', 'This is a great product!');
if ($result['success']) {
    echo "<p>Review submitted successfully: " . $result['message'] . "</p>";
} else {
    echo "<p>Error submitting review: " . $result['message'] . "</p>";
}

// Display the reviews
displayReviews('123');

?>
