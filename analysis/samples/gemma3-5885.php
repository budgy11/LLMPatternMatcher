
<!DOCTYPE html>
<html>
<head>
    <title>Simple Search Bar</title>
    <style>
        /* Basic styling - improve as needed */
        input[type="text"] {
            width: 300px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h1>Search</h1>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
