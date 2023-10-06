<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Details</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/fonts.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/products.css">

    <style>
        .product-container {
            max-width: 960px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-img-container {
            width: 500px;
            height: 40vh;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .product-img {
            max-width: 100%;
            height: 40vh;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-category {
            font-size: 18px;
            color: #777;
        }

        .product-description {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .product-discounted-price {
            font-size: 24px;
            color: #e74c3c;
            font-weight: bold;
            margin-right: 10px;
        }

        .original-price {
            text-decoration: line-through;
            font-size: 18px;
            color: #999;
        }

        .prev-link .next-link {
            font-weight: bold;
        }

        select {
            background-color: white;
            width: 200px;
        }

        select option {
            width: 120px;
        }

        /* Media query for mobile view */
        @media (max-width: 768px) {
            .product-container {
                box-shadow: none;
            }

            .prev-link,
            .next-link {
                position: absolute;
                display: block;
                width: 100%;
                margin: 10px 0;
                color: #294c24;
                font-size: 16px;
                font-weight: bold;
            }

            .prev-link {
                text-align: left;
                left: 20px;
                bottom: 20px;
                width: 20px;
            }

            .next-link {
                text-align: right;
                right: 20px;
                bottom: 20px;
                width: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('./header.php');
    include('add-to-cart.php'); ?>

    <div class="product-container">
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            $email = $_SESSION['useremail'];
        }

        // Retrieve the product name from the URL parameter
        if (isset($_GET['name'])) {
            $productName = $_GET['name'];

            // Connect to the database
            require('./dbconnect.php');

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the query to fetch the product details
            $query = "SELECT * FROM products WHERE name = '$productName'";
            $result = $conn->query($query);

            // Check if the query returned any result
            if ($result->num_rows > 0) {
                // Fetch the product details from the result
                $row = $result->fetch_assoc();

                // Extract the product information
                $productId = $row['id'];
                $name = $row['name'];
                $category = $row['category'];
                $description = $row['description'];
                $image = "./products/" . $row['image']; // Update the image link
                $price = $row['price'];
                $discount = $row['discount'];
                // Calculate the discounted price
                $discountedPrice = $price - ($price * $discount / 100);

                // Fetch previous and next product details based on the current product's category
                $prevProductQuery = "SELECT * FROM products WHERE category = '$category' AND id < $productId ORDER BY id DESC LIMIT 1";
                $nextProductQuery = "SELECT * FROM products WHERE category = '$category' AND id > $productId ORDER BY id ASC LIMIT 1";

                $prevProductResult = $conn->query($prevProductQuery);
                $nextProductResult = $conn->query($nextProductQuery);

                $prevProduct = $prevProductResult->fetch_assoc();
                $nextProduct = $nextProductResult->fetch_assoc();

                // Display the product details and previous/next links
                echo '
                <form action="#" method="POST">
                    <a href="./index.php#products" style="color:#294c24;">Go Back</a>
                    <section class="section swatch-secondary">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6 product-img-container">
                                    <img src="' . $image . '" alt="' . $name . '" class="img-fluid product-img">
                                </div>
                                <div class="col-md-6">
                                    <h2 class="product-name">' . $name . '</h2>
                                    <p class="product-category">Category: ' . $category . '</p>
                                    <p class="product-description">' . $description . '</p>
                                    <p class="product-price">Price: <span class="original-price">₹' . $price . '</span></p>
                                    <p>Discount: ' . $discount . '% off</p>
                                    <p class="product-discounted-price">New Price: ₹' . $discountedPrice . '</p>
                                    
                                    <div class="col-md-6">
                                    <label for="quantity">Quantity:</label>
                                        <select style="width:30%;" name="quantity" id="quantity">';

                for ($i = 1; $i <= 10; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                echo ' 
                                        </select>
                                    </div><br>
                                    <button type="submit" name="addToCart" class="btn btn-primary btn-icon">Add to Cart</button>
                                </div>
                            </div>
                        </div>';
                // Show Previous link if available
                if ($prevProduct) {
                    echo '<a class="prev-link" class="checkout-btn" style="margin-left: 5px; margin-bottom: 5px; position: absolute; left: 0; text-decoration: None; color: #294c24;" href="./product-details.php?name=' . $prevProduct['name'] . '">Previous</a>';
                }

                // Show Next link if available
                if ($nextProduct) {
                    echo '<a class="next-link" class="checkout-btn" style="margin-right: 5px; margin-bottom: 5px; position: absolute; right: 0; text-decoration: None; color: #294c24" href="./product-details.php?name=' . $nextProduct['name'] . '">Next</a>';
                }
                echo '</form></section>';
            } else {
                // If no product found, display an error message
                echo 'Product not found.';
            }

            // Close the database connection
            $conn->close();
        } else {
            // If product name is not provided, redirect back to the main page
            header("Location: index.php");
            exit();
        }
        ?>
    </div>
</body>

<script src="./assets/js/main.js"></script>
<script src="./assets/js/packages.js"></script>
<script src="./assets/js/theme.js"></script>
<script src="./assets/js/products.js"></script>

</html>