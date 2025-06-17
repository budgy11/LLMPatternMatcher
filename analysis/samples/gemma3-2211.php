

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product.
 * It includes functionality to add new reviews, display existing reviews,
 * and potentially handle pagination for large numbers of reviews.
 *
 * @param string $productId The ID of the product the reviews are for.
 * @param array $reviews An array of review objects.  Each review object should have at least 'user', 'rating', and 'comment' properties.
 * @param string $view  The view to display the reviews in.  Options: 'list', 'grid'
 * @param int $pageSize The number of reviews to display per page. Defaults to 10.
 * @return string The HTML output of the reviews.
 */
function displayReviews($productId, $reviews, $view = 'list', $pageSize = 10)
{

    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    if ($view === 'list') {
        return displayReviewsAsList($reviews, $pageSize);
    } elseif ($view === 'grid') {
        return displayReviewsAsGrid($reviews, $pageSize);
    } else {
        return "<p>Invalid view.  Supported views are 'list' and 'grid'.</p>";
    }
}


/**
 * Displays reviews in a list format.
 *
 * @param array $reviews The reviews to display.
 * @param int $pageSize The number of reviews to display per page.
 * @return string The HTML output of the reviews.
 */
function displayReviewsAsList($reviews, $pageSize)
{
    $totalReviews = count($reviews);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  //Get the page number from the URL

    if ($page < 1) { $page = 1; } //Ensure page is at least 1

    $start = ($page - 1) * $pageSize;
    $end = $start + $pageSize;

    $paginatedReviews = array_slice($reviews, $end - $start, true); //Slice for pagination

    $html = "<div class='reviews-container'>";
    foreach ($paginatedReviews as $review) {
        $html .= "<div class='review'>";
        $html .= "<p class='review-user'><strong>" . htmlspecialchars($review['user']) . "</strong></p>";
        $html .= "<p class='review-rating'>Rating: " . $review['rating'] . "</p>";
        $html .= "<p class='review-comment'>" . htmlspecialchars($review['comment']) . "</p>";
        $html .= "</div>";
    }

    //Pagination links
    $totalPages = (int)ceil($totalReviews / $pageSize);
    $html .= "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $html .= "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
    $html .= "</div>";

    $html .= "</div>";
    return $html;
}



/**
 * Displays reviews in a grid format.
 *
 * @param array $reviews The reviews to display.
 * @param int $pageSize The number of reviews to display per page.
 * @return string The HTML output of the reviews.
 */
function displayReviewsAsGrid($reviews, $pageSize)
{
    $html = "<div class='reviews-grid'>";
    for ($i = 0; $i < count($reviews); $i++) {
        $review = $reviews[$i];
        $html .= "<div class='review-item'>";
        $html .= "<p class='review-user'><strong>" . htmlspecialchars($review['user']) . "</strong></p>";
        $html .= "<p class='review-rating'>Rating: " . $review['rating'] . "</p>";
        $html .= "<p class='review-comment'>" . htmlspecialchars($review['comment']) . "</p>";
        $html .= "</div>";
    }
    $html .= "</div>";
    return $html;
}



// Example usage:
// Assuming you have an array of reviews called $myReviews
// And you've created a product object with $productId

// $myReviews = [
//     ['user' => 'John Doe', 'rating' => 5, 'comment' => 'Great product!'],
//     ['user' => 'Jane Smith', 'rating' => 4, 'comment' => 'Good value for money.'],
//     ['user' => 'Peter Jones', 'rating' => 3, 'comment' => 'It was okay.'],
// ];

// $productId = '123'; // Replace with the actual product ID

// $reviewsOutput = displayReviews($productId, $myReviews, 'list'); // or 'grid'
// echo $reviewsOutput;
?>
