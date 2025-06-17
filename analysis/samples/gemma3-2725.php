

<?php

/**
 * Reviews Class - Allows users to submit and view reviews.
 */
class Reviews {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $name The name of the reviewer.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $name, string $comment) {
        if (empty($name) || empty($comment)) {
            return false; // Invalid input
        }

        $this->reviews[] = [
            'name' => $name,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Timestamp of review
        ];
        return true;
    }

    /**
     * Gets all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Gets reviews based on a search term.
     *
     * @param string $searchTerm The term to search for in the review comment.
     * @return array An array of review objects matching the search term.
     */
    public function searchReviews(string $searchTerm) {
        $results = [];
        foreach ($this->getAllReviews() as $review) {
            if (strpos($review['comment'], $searchTerm) !== false) {
                $results[] = $review;
            }
        }
        return $results;
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return string HTML markup to display the reviews.
     */
    public function displayReviews() {
        $reviews = $this->getAllReviews();
        $html = '<table border="1">';
        $html .= '<thead><tr><th>Name</th><th>Comment</th><th>Date</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($reviews as $review) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($review['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($review['comment']) . '</td>';
            $html .= '<td>' . $review['date'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }
}

// Example Usage:
// Instantiate the Reviews class
$reviews = new Reviews();

// Add some reviews
$reviews->addReview('John Doe', 'Great product!  I highly recommend it.');
$reviews->addReview('Jane Smith', 'The service was excellent.  Fast and friendly.');
$reviews->addReview('Peter Jones', 'Could be better, but overall okay.');

// Search for reviews containing "excellent"
$searchResults = $reviews->searchReviews('excellent');

// Display all reviews
echo $reviews->displayReviews();

// Display search results
echo "<h2>Search Results for 'excellent':</h2>";
echo $reviews->displayReviews($searchResults);  // Function overload to display search results


/**
 * Overloaded displayReviews function to handle search results.  This allows you
 * to customize the display of search results if needed.
 *
 * @param array $reviews  The array of reviews to display.
 * @return string HTML markup to display the reviews.
 */
function displayReviews(array $reviews) {
    $html = '<table border="1">';
    $html .= '<thead><tr><th>Name</th><th>Comment</th><th>Date</th></tr></thead>';
    $html .= '<tbody>';
    foreach ($reviews as $review) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($review['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($review['comment']) . '</td>';
        $html .= '<td>' . $review['date'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
    return $html;
}

?>
