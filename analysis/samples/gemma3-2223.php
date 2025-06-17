

<?php

/**
 * Function to handle user reviews.
 *
 * @param array $reviews An array of review objects. Each review object should have the following properties:
 *   - id: (int) - Unique identifier for the review.
 *   - user: (string) - User's name or ID.
 *   - rating: (int) - Rating from 1 to 5.
 *   - comment: (string) - User's review comment.
 *   - date: (string) - Timestamp of the review (e.g., Y-m-d H:i:s).
 *
 * @return array An array of formatted reviews.
 */
function handleUserReviews(array $reviews): array
{
    $formattedReviews = [];

    foreach ($reviews as $review) {
        $formattedReview = [
            'id' => $review['id'],
            'user' => $review['user'],
            'rating' => $review['rating'],
            'comment' => $review['comment'],
            'date' => date('Y-m-d H:i:s', strtotime($review['date'])), // Format the date
        ];
        $formattedReviews[] = $formattedReview;
    }

    return $formattedReviews;
}

// Example Usage:
// Simulate some sample reviews
$reviews = [
    [
        'id' => 1,
        'user' => 'John Doe',
        'rating' => 5,
        'comment' => 'Excellent product! Highly recommend.',
        'date' => '2023-10-26 10:00:00',
    ],
    [
        'id' => 2,
        'user' => 'Jane Smith',
        'rating' => 4,
        'comment' => 'Good product, but could be improved.',
        'date' => '2023-10-27 14:30:00',
    ],
    [
        'id' => 3,
        'user' => 'Peter Jones',
        'rating' => 3,
        'comment' => 'It\'s okay.  Not what I expected.',
        'date' => '2023-10-28 08:15:00',
    ],
];

$formattedReviews = handleUserReviews($reviews);

// Print the formatted reviews (for demonstration)
echo "<pre>";
print_r($formattedReviews);
echo "</pre>";

?>
