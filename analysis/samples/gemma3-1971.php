

<?php
// ... (generateReview function from above) ...

// Get user input (e.g., from a form)
$username = $_POST['username'];
$rating = (int)$_POST['rating']; // Cast to integer
$comment = $_POST['comment'];

// Generate the review
$review = generateReview($username, $rating, $comment);

// Display the review on the page
echo "<h2>Review:</h2>";
echo $review;
?>
