

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes basic validation and data storage (using a file for simplicity).
 * 
 * @param string $productName The name of the product or service being reviewed.
 * @param string $userRating A rating from 1 to 5.
 * @param string $userComment The user's written review.
 * @param string $username The username of the reviewer.
 * 
 * @return array  An array containing the result of the review submission.
 *                 Returns an empty array on error.
 */
function submit_review(string $productName, string $userRating, string $userComment, string $username): array
{
    // Validate inputs - Basic, can be expanded for more robust validation
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (!preg_match('/^[1-5][0-9]*$/', $userRating)) {
        return ['error' => 'Invalid rating.  Please enter a number between 1 and 5.'];
    }
    if (empty($userComment)) {
        return ['error' => 'Review comment cannot be empty.'];
    }
    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }
    
    // Sanitize inputs (Important for security - add more sanitization as needed)
    $productName = trim($productName);
    $userRating = (int) $userRating; // Convert to integer for easier manipulation
    $userComment = trim($userComment);
    $username = trim($username);
    
    // Create the review data
    $reviewData = [
        'product_name' => $productName,
        'user_rating' => $userRating,
        'user_comment' => $userComment,
        'username' => $username,
        'timestamp' => time() // Add a timestamp
    ];

    // Store the review in a file (for demonstration purposes - use a database for production)
    $filepath = "reviews.txt";
    $reviewString = json_encode($reviewData) . "
"; 
    
    if (file_put_contents($filepath, $reviewString, FILE_APPEND)) {
        return ['success' => 'Review submitted successfully!'];
    } else {
        return ['error' => 'Failed to save review to file.'];
    }
}



// Example Usage:
$product = "Amazing Coffee Maker";
$rating = 5;
$comment = "This coffee maker is fantastic! It brews the perfect cup every time.";
$user = "JohnDoe";

$result = submit_review($product, $rating, $comment, $user);

if (isset($result['success'])) {
    echo "<p>Review submitted successfully: " . $result['success'] . "</p>";
} elseif (isset($result['error'])) {
    echo "<p>Error submitting review: " . $result['error'] . "</p>";
} else {
    echo "<p>Unknown error occurred.</p>";
}


//  Example to retrieve and display the reviews (Demonstration)
function display_reviews(string $filepath) {
    $reviews = file($filepath, FILE_IGNORE_NEW_LINES);
    if (count($reviews) == 0) {
        echo "<p>No reviews found.</p>";
        return;
    }
    echo "<h2>Reviews:</h2>";
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<strong>Product:</strong> " . json_decode($review, true)['product_name'] . "<br>";
        echo "<strong>Rating:</strong> " . json_decode($review, true)['user_rating'] . "<br>";
        echo "<strong>User:</strong> " . json_decode($review, true)['username'] . "<br>";
        echo "<strong>Comment:</strong> " . json_decode($review, true)['user_comment'] . "<br>";
        echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", json_decode($review, true)['timestamp']) . "<br>";
        echo "</div>";
    }
}

//  Call display_reviews() after submitting reviews.
// display_reviews("reviews.txt"); //Uncomment to see displayed reviews.  Ensure reviews.txt exists.
?>
