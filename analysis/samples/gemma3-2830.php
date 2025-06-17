

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $reviewText   The review text submitted by the user.
 * @param string $username     The username of the reviewer.
 * @param int    $rating       The rating given by the user (1-5).
 *
 * @return array  An array containing the review data and a success/error message.
 */
function submitReview(string $productName, string $reviewText, string $username, int $rating)
{
    // Validate input (basic example - improve as needed)
    if (empty($productName)) {
        return ['success' => false, 'message' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    // Sanitize input - Important for security.  Consider more comprehensive sanitization.
    $productName = htmlspecialchars($productName);
    $reviewText = htmlspecialchars($reviewText);
    $username = htmlspecialchars($username);


    //  Store the review in a database (replace with your database logic)
    $reviewId = saveReviewToDatabase($productName, $reviewText, $username, $rating); // Replace with your DB call.

    if ($reviewId === false) {
        return ['success' => false, 'message' => 'Failed to save review to database.'];
    }


    return ['success' => true, 'reviewId' => $reviewId, 'message' => 'Review submitted successfully!'];
}


/**
 * Placeholder function to simulate saving the review to a database.
 *  Replace this with your actual database code.
 *
 * @param string $productName
 * @param string $reviewText
 * @param string $username
 * @param int $rating
 *
 * @return int|false  The ID of the newly created review, or false on error.
 */
function saveReviewToDatabase(string $productName, string $reviewText, string $username, int $rating)
{
    // **Replace this with your database interaction code**
    // This is just a placeholder
    echo "Simulating saving review to database...
";

    // Example: Generate a fake review ID
    $reviewId = hash('md5', $productName . $reviewText . $username . $rating);  // Create a hash to simulate an ID

    return $reviewId; // Return the ID (or false if the database operation fails)
}



// Example Usage:
$product = "Awesome Widget";
$review = "This widget is amazing!  I highly recommend it.";
$user = "JohnDoe";
$rating = 4;


$result = submitReview($product, $review, $user, $rating);

if ($result['success']) {
    echo "Review submitted successfully! Review ID: " . $result['reviewId'] . "<br>";
} else {
    echo "Error submitting review: " . $result['message'] . "<br>";
}



//  Example of retrieving reviews (for demonstration - replace with your retrieval logic)
function getReviewsForProduct(string $productName)
{
    // Simulate fetching reviews from a database
    $reviews = [
        ['reviewId' => 1, 'productName' => 'Awesome Widget', 'reviewText' => 'Great product!', 'username' => 'JaneSmith', 'rating' => 5],
        ['reviewId' => 2, 'productName' => 'Awesome Widget', 'reviewText' => 'Could be better.', 'username' => 'PeterJones', 'rating' => 3],
    ];

    $filteredReviews = [];

    foreach ($reviews as $review)
    {
        if ($review['productName'] === $productName)
        {
            $filteredReviews[] = $review;
        }
    }

    return $filteredReviews;

}

$awesomeWidgetReviews = getReviewsForProduct("Awesome Widget");

echo "<br><h2>Reviews for {$awesomeWidgetReviews[0]['productName']}</h2>";
if (!empty($awesomeWidgetReviews)) {
    echo "<ul>";
    foreach ($awesomeWidgetReviews as $review) {
        echo "<li><strong>{$review['username']}</strong> - Rating: {$review['rating']} - " . htmlspecialchars($review['reviewText']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews found for this product.</p>";
}
?>
