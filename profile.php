<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $loggedin = true;
    $email = $_SESSION['useremail'];
    require('./dbconnect.php');

    $sql = "SELECT * FROM `users` WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $loggedin = false;
        header("location: ./signin.php");
    }

    $user = $result->fetch_assoc();
    $name = $user['name'];
    $phone = $user['phone'];
    $address = $user['address'];

    $sql = "SELECT * FROM `orders` WHERE u_email='$email';";
    $order = $conn->query($sql);
    $orders = array();
    while ($item = $order->fetch_assoc()) {
        $product_id = $item['p_id'];
        $sql = "SELECT name, category, description FROM `products` WHERE id='$product_id'";
        $product = $conn->query($sql);
        array_push($orders, $product->fetch_assoc());
    }
} else {
    $loggedin = false;
    header("location: ./signin.php");
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Happy Way</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/theme.min.css">
    <link rel="stylesheet" href="assets/css/swatch-primary.css">
    <link rel="stylesheet" href="assets/css/swatch-secondary.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* Add space between columns */
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            justify-content: space-between;
        }

        .profile-container {
            padding: 20px;
            width: 45%;
        }

        .order-card {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .order-card table tr {
            margin-bottom: 10px;
        }

        @media (max-width: 767px) {
            .container {
                flex-direction: column;
            }

            .profile-container {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <?php include('./header.php'); ?>

    <?php
    if (isset($_POST['cancelOrder'])) {
        // Check if the orderId is provided in the form submission
        if (isset($_POST['orderId'])) {
            $orderId = $_POST['orderId'];

            // If the user confirms the cancellation, proceed with order cancellation
            if (isset($_POST['confirmCancel']) && $_POST['confirmCancel'] === 'on') {
                // Connect to the database
                require('./dbconnect.php');

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute the query to delete the order
                $query = "DELETE FROM orders WHERE u_email='$email' AND order_id='$orderId'";
                $result = $conn->query($query);

                // Check if the deletion was successful
                if ($result) {
                    // Order cancelled successfully
                    echo '<script>alert("Order cancelled successfully.");</script>';
                } else {
                    // Failed to cancel the order
                    echo '<script>alert("Failed to cancel the order. Please try again later.");</script>';
                }

                // Close the database connection
                $conn->close();
            } else {
                // Confirmation not checked, show an error message
                echo '<script>alert("Please confirm the order cancellation.");</script>';
            }
        } else {
            // Invalid request, orderId not provided
            echo '<script>alert("Invalid request. Please try again.");</script>';
        }
    }

    if (isset($_POST['updateUser'])) {
        // Check if the form has been submitted with the Update button

        // Retrieve updated values from the form fields
        $newUsername = $_POST['username'];
        $newUseremail = $_POST['useremail'];
        $newUserphonenumber = $_POST['userphonenumber'];
        $newAddress = $_POST['adrs'];

        // Connect to the database
        require('./dbconnect.php');

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the query to update the user's information in the `users` table
        $query = "UPDATE `users` SET `name`='$newUsername', `email`='$newUseremail', `phone`='$newUserphonenumber', `address`='$newAddress' WHERE `email`='$email'";
        $result = $conn->query($query);

        // Check if the update was successful
        if ($result) {
            // Update successful
            echo '<script>alert("User information updated successfully.");</script>';

            // Update the session variable with the new email if it was changed
            if ($email !== $newUseremail) {
                $_SESSION['useremail'] = $newUseremail;
                $email = $newUseremail;
            }
        } else {
            // Failed to update user information
            echo '<script>alert("Failed to update user information. Please try again later.");</script>';
        }

        // Close the database connection
        $conn->close();
    }
    ?>

    <section class="section swatch-secondary" id="login">
        <header id="contactId" class="section-header">
            <h1>Welcome <?php echo $name; ?></h1>
        </header>

        <div class="container">
            <div class="profile-container">
                <h2 style="text-align: center;">My Details</h2>
                <div class="row m-0">
                    <div class="col">
                        <form class="contact-form" id="contactForm" novalidate="" method="POST" action="./profile.php">
                            <div class="form-group form-icon-group">
                                <input class="form-control" id="username" name="username" style="background-color: #dedede;" value="<?php echo $name; ?>" type="text">
                                <i class="fa fa-user" style="color: black;"></i>
                            </div>
                            <div class="form-group form-icon-group">
                                <input class="form-control" id="useremail" name="useremail" style="background-color: #dedede;" value="<?php echo $email; ?>" type="text">
                                <i class="fa fa-envelope" style="color: black;"></i>
                            </div>
                            <div class="form-group form-icon-group">
                                <input class="form-control" id="userphonenumber" name="userphonenumber" style="background-color: #dedede;" value="<?php echo $phone; ?>" type="text">
                                <i class="fa fa-phone" style="color: black;"></i>
                            </div>
                            <div class="form-group form-icon-group">
                                <textarea class="form-control" id="adrs" name="adrs" style="background-color: #dedede;" rows="4" style="height: 118px;resize: none;"><?php echo $address; ?></textarea>
                                <i class="fa fa-address-book" style="color: black;"></i>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary btn-icon " type="submit" name="updateUser">
                                    Update
                                </button>
                                <a href="./logout.php">
                                    <button class="btn btn-primary btn-icon" type="button">
                                        Logout
                                    </button>
                                </a>
                            </div>
                            <div id="messages"></div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="profile-container">
                <h2 style="text-align: center;">My Orders</h2>
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    $email = $_SESSION['useremail'];

                    // Connect to the database
                    require('./dbconnect.php');

                    // Fetch the orders for the user
                    $query = "SELECT DISTINCT order_id FROM orders WHERE u_email = '$email'";
                    $orderResult = mysqli_query($conn, $query);

                    // Check if any orders are found
                    if (mysqli_num_rows($orderResult) > 0) {
                        while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                            $orderId = $orderRow['order_id'];
                            // Fetch the items in this order from the 'orders' table
                            $itemsQuery = "SELECT p.id, p.name, p.price, p.discount, p.image, o.qty FROM orders o JOIN products p ON o.p_id = p.id WHERE o.u_email = '$email' AND o.order_id = '$orderId'";
                            $itemsResult = mysqli_query($conn, $itemsQuery);

                            // Calculate the total of the order
                            $orderTotal = 0;
                ?>
                            <div class="order-card">
                                <h3>Order ID: <?php echo $orderId; ?></h3>
                                <table>
                                    <tr>
                                        <th></th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    <?php
                                    if (mysqli_num_rows($itemsResult) > 0) {
                                        while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
                                            $productId = $itemRow['id'];
                                            $productName = $itemRow['name'];
                                            $productPrice = $itemRow['price'] - ($itemRow['price'] * $itemRow['discount'] / 100);
                                            $quantity = $itemRow['qty'];
                                            $subtotal = $productPrice * $quantity;
                                            $orderTotal += $subtotal;
                                            $productImage = $itemRow['image'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <img src="./products/<?php echo $productImage; ?>" alt="<?php echo $productName; ?>" width="80" height="80" />
                                                </td>
                                                <td><?php echo $productName; ?></td>
                                                <td>₹<?php echo $productPrice; ?></td>
                                                <td><?php echo $quantity; ?></td>
                                                <td>₹<?php echo $subtotal; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        // If no items in the order, display a message
                                        echo '<tr><td colspan="5">No items found in this order.</td></tr>';
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4"><strong>Total</strong></td>
                                        <td>₹<?php echo $orderTotal; ?></td>
                                    </tr>
                                </table>
                                <form action="" method="POST">
                                    <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
                                    <div class="form-group">
                                        <label for="confirmCancel">Are you sure you want to cancel this order?</label>
                                        <input type="checkbox" name="confirmCancel" id="confirmCancel" required>
                                    </div>
                                    <button type="submit" class="" style="background-color:red; color:white; width:auto; height:auto; border-radius:5px" name="cancelOrder">Cancel Order</button>
                                </form>

                            </div>

                <?php
                        }
                    } else {
                        // If no orders found, display a message
                        echo "<p>No orders found.</p>";
                    }

                    // Close the database connection
                    $conn->close();
                } else {
                    // If the user is not logged in, redirect to the login page or handle it accordingly
                    header("Location: login.php");
                    exit();
                }
                ?>
            </div>
        </div>

    </section>

</body>

</html>