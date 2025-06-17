
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
    <style>
        body { font-family: sans-serif; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
