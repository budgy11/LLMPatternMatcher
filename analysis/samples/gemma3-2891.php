

<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and display user reviews for a given item.
 * 
 * @param string $itemName The name of the item being reviewed.
 * @param string $itemDescription  A brief description of the item (optional).
 * @param array $reviews An array of existing reviews (optional).
 * @return array An array containing the updated reviews, including the new review if submitted.
 */
function handleUserReviews(string $itemName, string $itemDescription = '', array $reviews = []) {
    // Simulate database interaction for demonstration purposes.  Replace this with 
    // actual database queries in a real application.
    $newReview = "";
    if (isset($_POST['review_text'])) {
        $newReview = $_POST['review_text'];
    }


    $reviews = [...reviews, ['text' => $newReview, 'date' => date('Y-m-d H:i:s')]]; // Add new review to the array.  Includes date.

    // Sort reviews by date (most recent first) - optional but recommended
    usort($reviews, function($a, $b) {
        return $b['date'] <=> $a['date'];
    });


    return $reviews;
}



// Example Usage (Demonstration)

// Initialize an empty reviews array
$reviews = [];

// Simulate a form submission
if (isset($_POST['submit_review'])) {
    $reviews = handleUserReviews('My Awesome Product', 'A great product!', $reviews);
}

// Display the reviews
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>User Reviews</title>";
echo "</head>";
echo "<body>";

echo "<h1>User Reviews for " . $itemName = 'My Awesome Product' . "</h1>";

if (count($reviews) > 0) {
    echo "<p><strong>Reviews:</strong></p>";
    echo "<table border='1'>";
    echo "<tr><th>Date</th><th>Review</th></tr>";
    foreach ($reviews as $review) {
        echo "<tr>";
        echo "<td>" . $review['date'] . "</td>";
        echo "<td>" . $review['text'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No reviews yet!</p>";
}


echo "<form method='post'>";
echo "<h2>Submit a Review</h2>";
echo "<label for='review_text'>Your Review:</label><br>";
echo "<textarea id='review_text' name='review_text' rows='4' cols='50'></textarea><br><br>";
echo "<input type='submit' value='Submit Review'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>
