# Methodology for Dangerous Variables

Dangerous variables were taken from the samples with a basic grep command for user input seen below

```bash
grep -iE '^\s*\$.*\s*=\s*\$_(POST|GET|REQUEST|SERVER|COOKIE)' samples/* | tr -d '\/' | cut -d ':' -f2 |  awk '{print $1}' | sort -u  > dangerous_vars.txt#
```

The set of dangerous variables was then checked against the samples to see how many false positives were reduced within the sample using the two grep statements shown below and comparing the numbers. We saw the the output of 8646 results were lowered to 3690 results. For this reason, it was decided to use `dangerous_vars.txt` but default to have it match all with other files available based on use case. This is one of the reasons that this tool does not claim to replace having a good code audit pipeline.

```bash
grep -P 'echo\s+.*(?<!htmlspecialchars\()\s*\$.+$' samples/*
```

8646 results from above

```bash
grep -P 'echo\s+.*(?<!htmlspecialchars\()\s*(\$_POST|\$_GET|\$_REQUEST|\$_COOKIE|\$_SERVER|\$action|\$address|\$cart|\$cart_id|\$cartId|\$cart_items|\$cartItems|\$cart_items_json|\$cart_json|\$comment|\$confirm_email|\$confirm_password|\$confirmPassword|\$customerAddress|\$customer_email|\$customerEmail|\$customer_id|\$customer_name|\$customerName|\$email|\$email_to_reset|\$firstname|\$input_value|\$item|\$item_id|\$itemId|\$item_name|\$items|\$itemsString|\$lastname|\$login_password|\$login_username|\$name|\$new_password|\$newPassword|\$new_quantity|\$newQuantity|\$newReview|\$orderData|\$order_id|\$password|\$payment_amount|\$paymentAmount|\$payment_method|\$paymentMethod|\$price|\$product|\$product_id|\$productId|\$product_ids|\$product_id_to_remove|\$productIdToRemove|\$product_name|\$productName|\$quantities|\$quantity|\$rating|\$remove_id|\$remove_product_id|\$reset_code|\$resetHash|\$reset_link|\$resetLink|\$reset_token|\$resetToken|\$reviewerName|\$review_text|\$reviewText|\$search_query|\$search_term|\$searchTerm|\$shipping_address|\$shippingAddress|\$stars|\$token|\$total_amount|\$totalAmount|\$user_id|\$userId|\$username|\$userName|\$user_password|\$user_username)' analysis/samples/*
```
