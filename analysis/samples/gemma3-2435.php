

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a given product or item.
 * It includes input validation, sanitization, and basic display functionality.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param array $reviews An array of user review objects.
 *                     Each review object should have 'user' and 'comment' properties.
 * @return string HTML formatted display of user reviews.  Returns an error message if input is invalid.
 */
function displayUserReviews(string $productName, array $reviews)
{
    // Input Validation and Sanitization (Crucial for Security!)
    if (empty($productName)) {
        return "<p style='color:red;'>Error: Product name cannot be empty.</p>";
    }

    if (!is_array($reviews)) {
        return "<p style='color:red;'>Error: Reviews must be an array.</p>";
    }

    foreach ($reviews as $review) {
        if (!is_object($review) || !isset($review->user) || !isset($review->comment)) {
            return "<p style='color:red;'>Error: Each review must be an object with 'user' and 'comment' properties.</p>";
        }
    }

    // HTML Formatting
    $html = "<div class='user-reviews'>";
    $html .= "<h2>Reviews for {$productName}</h2>";

    if (empty($reviews)) {
        $html .= "<p>No reviews yet. Be the first to review!</p>";
    } else {
        foreach ($reviews as $review) {
            $html .= "<div class='review'>";
            $html .= "<p class='review-user'><strong>User:</strong> {$review->user}</p>";
            $html .= "<p class='review-comment'><em>{$review->comment}</em></p>";
            $html .= "</div>";
        }
    }

    $html .= "</div>";

    return $html;
}


// Example Usage:

// Sample Reviews (Replace with actual data)
$reviews = [
    (object) [
        'user' => 'John Doe',
        'comment' => 'Great product!  Easy to use and works perfectly.'
    ],
    (object) [
        'user' => 'Jane Smith',
        'comment' => 'I love this!  Highly recommend.'
    ],
    (object) [
        'user' => 'Peter Jones',
        'comment' => 'Could be better, but overall a good value.'
    ]
];

// Call the function
$reviewHtml = displayUserReviews("Awesome Widget", $reviews);

// Output the HTML (for demonstration - you'd typically display this in a web page)
echo $reviewHtml;


// Example of an error case:
$errorHtml = displayUserReviews("", $reviews);
echo "<br><br>Error Example:<br>" . $errorHtml;
?>
