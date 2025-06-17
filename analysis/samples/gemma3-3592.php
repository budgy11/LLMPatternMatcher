
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
        .error-messages {
            color: red;
        }
    </style>
</head>
<body>

<h1>Registration</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
