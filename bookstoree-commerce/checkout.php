<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
}

if (isset($_POST['checkout'])) {
  // Fetching form data
  $name = mysqli_real_escape_string($conn, $_POST['firstname']);
  $number = $_POST['number'];
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $state = mysqli_real_escape_string($conn, $_POST['state']);
  $country = mysqli_real_escape_string($conn, $_POST['country']);
  $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
  $full_address = mysqli_real_escape_string($conn, $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pincode']);
  $placed_on = date('d-M-Y');

  // Fetch account details from insertuseraccount table
  $account_paid_query = mysqli_query($conn, "SELECT account_number, name, balance FROM insertuseraccount") or die('Query failed');
  $account_paid_row = mysqli_fetch_assoc($account_paid_query);
  $account_paid = $account_paid_row['account_number'];
  $account_paid_balance = $account_paid_row['balance'];

  // Fetch cashier account details from cashier_account table
  $cashier_account_query = mysqli_query($conn, "SELECT account_number, name, balance FROM cashier_account") or die('Query failed');
  $cashier_account_row = mysqli_fetch_assoc($cashier_account_query);
  $cashier_account = $cashier_account_row['account_number'];
  $cashier_account_balance = $cashier_account_row['balance'];
  mysqli_query($conn, "INSERT INTO confirm_order(user_id, name, number, email, account_paid, cashier_account, address, total_books, total_price, order_date) VALUES('$user_id','$name', '$number', '$email','$account_paid', '$cashier_account', '$full_address', '$total_books', '$cart_total', '$placed_on')") or die('query failed');
  // Initialize variables
  $conn_oid = ''; // Initialize $conn_oid
  $cart_books = ''; // Initialize $cart_books
  $payment_status = ''; // Initialize $payment_status
  $account_paid_status = ''; // Initialize $account_paid_status
  $total_price_status = ''; // Initialize $total_price_status

  // Calculating cart total
  $cart_total = 0;
  if (empty($name) || empty($email) || empty($number) || empty($address) || empty($city) || empty($state) || empty($country) || empty($pincode) || empty($account_paid) || empty($cashier_account)) {
    $message[] = 'Please fill in all the fields';
  } else {
    // Fetch cart items and calculate total
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
        $quantity = $cart_item['quantity'];
        $unit_price = $cart_item['price'];
        $sub_total = $unit_price * $quantity;
        $cart_total += $sub_total;
        mysqli_query($conn, "INSERT INTO orders(user_id,id,address,city,state,country,pincode,book,quantity,unit_price,sub_total) VALUES('$user_id','$conn_oid','$address','$city','$state','$country','$pincode','$cart_books','$quantity','$unit_price','$sub_total')") or die('query failed');      }
    }
$total_books = ''; // Initialize $total_books variable
$cart_total = 0; // Initialize $cart_total variable
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
if (mysqli_num_rows($cart_query) > 0) {
  while ($cart_item = mysqli_fetch_assoc($cart_query)) {
    $quantity = $cart_item['quantity'];
    $unit_price = $cart_item['price'];
    $sub_total = $unit_price * $quantity;
    $cart_total += $sub_total;
    mysqli_query($conn, "INSERT INTO orders(user_id,id,address,city,state,country,pincode,book,quantity,unit_price,sub_total) VALUES('$user_id','$conn_oid','$address','$city','$state','$country','$pincode','$cart_books','$quantity','$unit_price','$sub_total')") or die('query failed');
  }
}

// Fetching cart items for total_books
$cart_products = []; // Initialize $cart_products array
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
if (mysqli_num_rows($cart_query) > 0) {
  while ($cart_item = mysqli_fetch_assoc($cart_query)) {
    $cart_products[] = $cart_item['name'];
  }
  $total_books = implode(', ', $cart_products);
}

    $order_query = mysqli_query($conn, "SELECT * FROM confirm_order WHERE name = '$name' AND number = '$number' AND email = '$email' AND account_paid = '$account_paid' AND address = '$address' AND total_books = '$total_books' AND total_price = '$cart_total'") or die('query failed');

    if (mysqli_num_rows($order_query) > 0) {
      $message[] = 'order already placed!';
    } else {
      mysqli_query($conn, "INSERT INTO confirm_order(user_id, name, number, email, account_paid, address, total_books, total_price, order_date) VALUES('$user_id','$name', '$number', '$email','$account_paid', '$full_address', '$total_books', '$cart_total', '$placed_on')") or die('query failed');

      $conn_oid = $conn->insert_id;
      $_SESSION['id'] = $conn_oid;

      // Check if the paid account has enough balance
      if ($account_paid_balance < $cart_total) {
        $message[] = 'Insufficient balance in the paid account';
      } else {
        // Update balances and perform transfer
        $new_paid_balance = $account_paid_balance - $cart_total;
        $new_cashier_balance = $cashier_account_balance + $cart_total;

        // Deduct from insertuseraccount
        mysqli_query($conn, "UPDATE insertuseraccount SET balance = '$new_paid_balance' WHERE account_number = '$account_paid'") or die('Query failed');

        // Add to cashier_account
        mysqli_query($conn, "UPDATE cashier_account SET balance = '$new_cashier_balance' WHERE account_number = '$cashier_account'") or die('Query failed');

        // Rest of your code to insert order, update orders table, clear cart, etc.
        // ...

        $message[] = 'Order placed successfully! Your current balance is ' . $new_paid_balance;      }
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout</title>
  <style>
    /* Add your CSS styles here */
    /* For example: */
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    input[type=text],
    input[type=submit] {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[type=submit] {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type=submit]:hover {
      background-color: #45a049;
    }
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/493af71c35.js" crossorigin="anonymous"></script>

</head>

<body>
  <?php include 'index_header.php'; ?>

  <?php
  if (isset($message)) {
    foreach ($message as $msg) {
      echo '
        <div class="message" id= "messages"><span>' . $msg . '</span>
        </div>
        ';
    }
  }
  ?>

  <h1 style="text-align: center; margin-top:15px;  color:rgb(9, 152, 248);">Place Your Order Here</h1>
  <p style="text-align: center; ">Just One Step away from getting your books</p>
  <div class="row">
    <div class="col-75">
      <div class="container">
        <form action="" method="POST">

          <div class="row">
            <div class="col-50">
              <h3>Billing Address</h3>
              <label for="fname"><i class="fa fa-user"></i> Full Name</label>
              <input type="text" id="fname" name="firstname" placeholder="">
              <label for="email"><i class="fa fa-envelope"></i> Email</label>
              <input type="text" id="email" name="email" placeholder="">
              <label for="phone"><i class="fa fa-phone"></i> Phone Number</label>
              <input type="text" id="phone" name="number" placeholder="+">
              <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
              <input type="text" id="adr" name="address" placeholder="">
              <label for="city"><i class="fa fa-institution"></i> City</label>
              <input type="text" id="city" name="city" placeholder="">
              <label for="state"><i class="fa fa-map-marker"></i> State</label>
              <input type="text" id="state" name="state" placeholder="">

              <div style="padding: 0px;" class="row">
                <div class="col-50">
                  <label for="country">Country</label>
                  <input type="text" id="country" name="country" placeholder="">
                </div>
                <div class="col-50">
                  <label for="account_paid">Account Paid</label>
                  <input type="text" id="account_paid" name="account_paid" placeholder="">
                </div>
                <div class="col-50">
                  <label for="cashier_account">cashier Account</label>
                  <input type="text" id="cashier_account" name="cashier_account" placeholder="">
                </div>
                <div class="col-50">
                  <label for="pincode">Pincode</label>
                  <input type="text" id="pincode" name="pincode" placeholder="400060">
                </div>
              </div>
            </div>
          </div>
          <div class="col-50">
            <div class="col-25">
              <div class="container">
                <h4>Books In Cart</h4>
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                  while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                    $grand_total += $total_price;
                ?>
                    <p> <a href="book_details.php?details=<?php echo $fetch_cart['book_id']; ?>"><?php echo $fetch_cart['name']; ?></a><span class="price">(<?php echo 'birr' . $fetch_cart['price'] . '' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
                <?php
                  }
                } else {
                  echo '<p class="empty">your cart is empty</p>';
                }
                ?>
                <div>
                  <p>Grand total : <span class="price" style="color:black">birr <b><?php echo $grand_total; ?></b></span></p>
                </div>
                <label>
                  <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
                </label>
                <input type="submit" name="checkout" value="Continue to checkout" class="btn">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include 'index_footer.php'; ?>
  <script>
    setTimeout(() => {
      const box = document.getElementById('messages');

      // 👇️ hides element (still takes up space on page)
      box.style.display = 'none';
    }, 10000);
  </script>
</body>

</html>
