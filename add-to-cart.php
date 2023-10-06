<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['addToCart'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $email = $_SESSION['useremail'];

        if (isset($_GET['name'])) {
            $productName = $_GET['name'];

            // Check if the quantity field is set and not empty
            if (isset($_POST['quantity']) && !empty($_POST['quantity'])) {
                // Get the selected quantity
                $quantity = intval($_POST['quantity']);

                // Connect to the database
                require('./dbconnect.php');

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute the query to fetch the product details
                $productName = $conn->real_escape_string($productName);
                $query = "SELECT * FROM products WHERE name = '$productName'";
                $result = $conn->query($query);

                // Check if the query returned any result
                if ($result->num_rows > 0) {
                    // Fetch the product details from the result
                    $row = $result->fetch_assoc();

                    // Extract the product information
                    $productId = $row['id'];

                    // Prepare and execute the query to insert the item into the cart with the selected quantity
                    $insertQuery = "INSERT INTO `cart` (`u_email`, `p_id`, `qty`) VALUES ('$email', '$productId', '$quantity')";
                    if ($conn->query($insertQuery) === TRUE) {
                        // Item successfully added to the cart
                        echo '<script>alert("Item added to cart successfully.");</script>';
                    } else {
                        // Error occurred while adding the item to the cart
                        echo '<script>alert("Error: Unable to add item to cart.");</script>';
                    }
                } else {
                    // If no product found, display an error message
                    echo '<script>alert("Product not found.");</script>';
                }

                // Close the database connection
                $conn->close();
            } else {
                // If the quantity field is not set or empty, display an error message
                echo '<script>alert("Please select a quantity before adding to cart.");</script>';
            }
        }
    } else {
        // If the user is not logged in, prompt them to log in or redirect to the login page
        echo '<script>alert("Please log in to add items to your cart.");</script>';
        // You can redirect the user to the login page if desired
        // header("Location: login.php");
        // exit();
    }
}
