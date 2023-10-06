<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>My Cart</title>
  <style>
    /* CSS styles for the page */
    .cart-container {
      display: flex;
      justify-content: space-between;
      width: 65%;
      margin: 0 auto;
      margin-top: 20px;
    }

    .cart-table {
      width: 60%;
      padding: 20px;
      background-color: #f7f7f7;
      border-radius: 10px;
      box-shadow: 0 2px jp95px rgba(0, 0, 0, 0.1);
      margin-right: 40px;
    }

    .cart-details {
      width: 40%;
      padding: 20px;
      background-color: #f7f7f7;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-left: 40px;
    }

    h1 {
      margin-top: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
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

    .total-section {
      text-align: right;
    }

    .total-label {
      font-weight: bold;
    }

    .checkout-btn {
      display: block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #294c24;
      color: #fff;
      text-align: center;
      text-decoration: none;
      border-radius: 4px;
    }

    .remove-button {
      background-color: #ff0000;
      color: white;
      border: none;
      padding: 5px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .remove-button:hover {
      background-color: #cc0000;
    }

    .fa-trash-alt {
      font-size: 16px;
    }

    @media screen and (max-width: 600px) {
      .cart-container {
        flex-direction: column;
        width: 100%;
      }

      .cart-table,
      .cart-details {
        width: 100%;
        margin: 0;
        margin-top: 20px;
      }

      .cart-table {
        margin-bottom: 20px;
      }
    }
  </style>
</head>

<body>

  <?php

  function generateOrderId($email)
  {
    $timestamp = time(); // Current timestamp
    $orderId = md5($email . $timestamp); // Create an MD5 hash of the email and timestamp
    return $orderId;
  }
  ?>

  <?php
  include('header.php');

  // Handle removing items from the cart
  if (isset($_POST['removeFromCart'])) {
    $email = $_SESSION['useremail'];
    $productId = $_POST['productId'];

    // Connect to the database
    require('./dbconnect.php');

    // Prepare and execute the query to remove the item from the cart
    $removeQuery = "DELETE FROM cart WHERE u_email = '$email' AND p_id = '$productId'";
    if (mysqli_query($conn, $removeQuery)) {
      // Reload the page after removing the item from the cart
      echo "<script>window.location.href = 'my-cart.php';</script>";
      exit;
    } else {
      echo "Error removing item from cart: " . mysqli_error($conn);
    }

    // Close the database connection
    $conn->close();
  }

  // Handle the checkout process
  if (isset($_POST['checkout'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
      $email = $_SESSION['useremail'];

      require('./dbconnect.php');

      $query = "SELECT c.p_id, c.qty, p.name, p.price, p.discount, p.image FROM cart c JOIN products p ON c.p_id = p.id WHERE c.u_email = '$email'";
      $result = mysqli_query($conn, $query);

      $order_id = generateOrderId($email);

      $total = 0;
      $quantity = 1;
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $productId = $row['p_id'];
          $productPrice = $row['price'] - ($row['price'] * $row['discount'] / 100);
          $quantity = $row['qty'];
          $subtotal = $productPrice * $quantity;
          $total += $subtotal;
        }
      }


      // Prepare and execute the query to insert the order into the orders table
      $orderQuery = "INSERT INTO `orders` (`order_id`, `u_email`, `p_id`, `qty`, `total`) SELECT '$order_id', '$email', p_id, '$quantity', '$total' FROM cart c JOIN products p ON c.p_id = p.id WHERE c.u_email = '$email' GROUP BY u_email, p_id";
      if (mysqli_query($conn, $orderQuery)) {
        // Clear the cart after successful order placement
        $clearCartQuery = "DELETE FROM cart WHERE u_email = '$email'";
        mysqli_query($conn, $clearCartQuery);

        // Redirect to a success page or order confirmation page
        echo '<script>alert("Order placed successfully!");</script>';
      } else {
        echo "Error placing the order: " . mysqli_error($conn);
      }

      // Close the database connection
      $conn->close();
    } else {
      // If the user is not logged in, redirect to the login page or handle it accordingly
      header("Location: login.php");
      exit();
    }
  }
  ?>

  <div class="cart-container">
    <div class="cart-table">
      <?php
      // Your PHP code to fetch cart items and details here...
      ?>
      <h1>My Cart</h1>
      <table>
        <thead>
          <tr>
            <th></th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            $email = $_SESSION['useremail'];

            // Connect to the database
            require('./dbconnect.php');

            // Fetch the cart items and their details from the database
            $query = "SELECT c.p_id, c.qty, p.name, p.price, p.discount, p.image FROM cart c JOIN products p ON c.p_id = p.id WHERE c.u_email = '$email'";
            $result = mysqli_query($conn, $query);

            // Calculate the total of the cart
            $total = 0;
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $productId = $row['p_id'];
                $productImage = $row['image'];
                $productName = $row['name'];
                $productPrice = $row['price'] - ($row['price'] * $row['discount'] / 100);
                $quantity = $row['qty'];
                $subtotal = $productPrice * $quantity;
                $total += $subtotal;
          ?>
                <tr>
                  <td>
                    <img src="./products/<?php echo $productImage; ?>" alt="<?php echo $productName; ?>" width="80" height="80" />
                  </td>
                  <td><?php echo $productName ?></td>
                  <td>₹<?php echo $productPrice; ?></td>
                  <td><?php echo $quantity; ?></td>
                  <td>₹<?php echo $subtotal; ?></td>
                  <td>
                    <!-- The form to trigger the remove action -->
                    <form action="#" method="POST">
                      <input type="hidden" name="productId" value="<?php echo $productId; ?>">
                      <button type="submit" name="removeFromCart" class="remove-button">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  </td>
                </tr>
          <?php
              }
            } else {
              // If no cart items, display a message
              echo '<tr><td colspan="5">Your cart is empty.</td></tr>';
            }
            // Close the database connection
            $conn->close();
          } else {
            // If the user is not logged in, redirect to the login page or handle it accordingly
            header("Location: login.php");
            exit();
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="cart-details">
      <?php
      // Re-establish the database connection
      require('./dbconnect.php');
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Fetch the address for the current user from the users table
      $addressQuery = "SELECT address FROM users WHERE email = '$email'";
      $addressResult = mysqli_query($conn, $addressQuery);
      $userAddress = mysqli_fetch_assoc($addressResult)['address'];

      // Calculate the total of the cart (if not already calculated)
      if (!isset($total)) {
        $total = 0;
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $productPrice = $row['price'] - ($row['price'] * $row['discount'] / 100);
            $quantity = $row['qty'];
            $subtotal = $productPrice * $quantity;
            $total += $subtotal;
          }
        }
      }
      ?>
      <h1>Order Details</h1>
      <table>
        <tr>
          <th colspan="2">Shipping Address</th>
          <td colspan="2"><?php echo $userAddress; ?></td>
        </tr>
        <tr>
          <th colspan="2">Total</th>
          <td colspan="2">₹<?php echo $total; ?></td>
        </tr>
      </table>
      <form action="#" method="POST">
        <input type="hidden" name="checkout" value="1">
        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
      </form>
    </div>
  </div>

</body>

</html>