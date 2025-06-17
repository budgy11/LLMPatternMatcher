

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product being reviewed.
 * @param string $productName (Optional) The name of the product for display.
 * @param string $reviewText The text of the review submitted by the user.
 * @param int $userId (Optional)  The ID of the user submitting the review.  Useful for moderation/tracking.
 * @return array  An array containing the review data, including the review text and user ID.
 *                Returns an empty array if the review submission fails.
 */
function submitReview(string $productId, string $productName = '', string $reviewText, int $userId = 0)
{
    // **INPUT VALIDATION - IMPORTANT!**
    if (empty($productId)) {
        error_log("Error: Product ID is required."); // Log the error - crucial for debugging
        return [];
    }
    if (empty($reviewText)) {
        error_log("Error: Review text is required.");
        return [];
    }


    // **Data Sanitization & Security - CRITICAL!**
    $reviewText = htmlspecialchars($reviewText); // Protect against XSS attacks

    // **Store the review in a database (Example - you'd replace this with your actual DB code)**
    // This is just a demonstration; adapt to your database setup.
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your database credentials

    $stmt = $db->prepare("INSERT INTO reviews (productId, productName, reviewText, userId) VALUES (?, ?, ?, ?)");
    $stmt->execute([$productId, $productName, $reviewText, $userId]);

    // **Success Response**
    return [
        'reviewId' => $db->lastInsertId(), // Get the ID of the newly created review
        'productId' => $productId,
        'productName' => $productName,
        'reviewText' => $reviewText,
        'userId' => $userId,
        'success' => true,
        'message' => "Review submitted successfully!"
    ];
}


/**
 * Display Reviews Function
 *
 * This function retrieves reviews for a given product ID and displays them.
 *
 * @param string $productId The ID of the product for which to retrieve reviews.
 * @return array  An array containing the review data. Returns an empty array if no reviews are found.
 */
function displayReviews(string $productId)
{
    // **Retrieve reviews from the database (Example - you'd replace this with your actual DB code)**
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your database credentials
    $stmt = $db->prepare("SELECT reviewId, reviewText, userId, productName FROM reviews WHERE productId = ?");
    $stmt->execute([$productId]);

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = [
            'reviewId' => $row['reviewId'],
            'reviewText' => $row['reviewText'],
            'userId' => $row['userId'],
            'productName' => $row['productName']
        ];
    }

    return $reviews;
}



// **Example Usage (Demonstration)**

// 1. Submit a review:
$reviewData = submitReview('123', 'Awesome Widget', 'This is the best widget ever!');
if ($reviewData) {
    echo "Review submitted successfully! Review ID: " . $reviewData['reviewId'] . "<br>";
} else {
    echo "Review submission failed. Check the error logs for details.<br>";
}


// 2. Display the reviews for product '123':
$reviews = displayReviews('123');
if (!empty($reviews)) {
    echo "<h3>Reviews for Product 123:</h3>";
    foreach ($reviews as $review) {
        echo "<p><strong>User:</strong> " . ($review['userId'] > 0 ? "User ID: " . $review['userId'] : "Anonymous") . "</p>";
        echo "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
    }
} else {
    echo "<p>No reviews found for product 123.</p>";
}


?>
