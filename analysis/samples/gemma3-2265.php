

<?php

/**
 * This function allows users to submit and view reviews for a product or service.
 * It includes basic data validation and display.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param array $reviews An array of review objects (see example below).
 *
 * @return void
 */
function displayReviews(string $productName, array $reviews)
{
    // Simple HTML structure for display
    echo "<h1>Reviews for " . htmlspecialchars($productName) . "</h1>";

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($review['author']) . "</strong> - " . htmlspecialchars($review['comment']) . "<br>";
            echo "Rating: " . htmlspecialchars($review['rating']) . " / 5";
            echo "</li>";
        }
        echo "</ul>";
    }
}


/**
 * Adds a new review.
 *
 * @param string $productName The name of the product or service.
 * @param string $author The name of the reviewer.
 * @param string $comment The review comment.
 * @param int $rating The rating given by the reviewer (1-5).
 *
 * @return array An updated array of reviews.
 */
function addReview(string $productName, string $author, string $comment, int $rating)
{
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return $reviews; // Or throw an exception
    }

    $review = [
        'author' => $author,
        'comment' => $comment,
        'rating' => $rating
    ];

    $reviews[] = $review;
    return $reviews;
}



// **Example Usage:**

// Initialize an empty array to store reviews
$reviews = [];


// Add some reviews (you would typically collect this data from a form)
$reviews = addReview("Awesome Widget", "John Doe", "Great product!  Works exactly as advertised.", 5);
$reviews = addReview("Amazing Gadget", "Jane Smith", "Good value for money.", 4);
$reviews = addReview("Mediocre Thing", "Peter Jones", "It's okay, but could be better.", 3);


// Display the reviews
displayReviews("My Product", $reviews);


// ---  Simplified Version without explicit array initialization ---
// function displayReviewsSimplified(string $productName, array $reviews) {
//     echo "<h1>Reviews for " . htmlspecialchars($productName) . "</h1>";
//
//     if (empty($reviews)) {
//         echo "<p>No reviews yet.</p>";
//     } else {
//         echo "<ul>";
//         foreach ($reviews as $review) {
//             echo "<li>";
//             echo "<strong>" . htmlspecialchars($review['author']) . "</strong> - " . htmlspecialchars($review['comment']) . "<br>";
//             echo "Rating: " . htmlspecialchars($review['rating']) . " / 5";
//             echo "</li>";
//         }
//         echo "</ul>";
//     }
// }

// $reviews = []; // Initialize array here, or pass an array as a parameter.
// displayReviewsSimplified("My Product", $reviews);

?>
