
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>User Registration</h2>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
