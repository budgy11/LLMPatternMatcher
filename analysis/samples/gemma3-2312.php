

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["product_name"]; //You need to ensure this is actually used to identify the review
    $reviewText = $_POST["review_text"];
    $rating = isset($_POST["rating"]) ? $_POST["rating"] : null;

    // Sanitize and store the review (replace with your database logic)
    if (isset($_SESSION['reviews'][$productName])) {
        //Update existing review
    } else {
        $_SESSION['reviews'][$productName] = $reviewText;
    }

    // Redirect to a confirmation page
    header("Location: confirmation.php");
    exit();
}
?>
