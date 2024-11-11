<?php
include 'config.php';
session_start();

// Check if accountant_id is set in the session
if (!isset($_SESSION['accountant_id'])) {
   header('location:login.php');
   exit; // Add an exit to prevent further execution
}

$accountant_id = $_SESSION['accountant_id']; // Since it's ensured to be set, directly assign it here

if (isset($_POST['update_order'])) {
    // Extract form data
    $order_update_id = $_POST['order_id'];
    $update_total_price_status = $_POST['total_price_status']; // Corrected from 'total_price' to 'total_price_status'
    $date = date("d.m.Y");

    // Fetch the order details from the database
    $result = mysqli_query($conn, "SELECT * FROM `confirm_order` WHERE order_id = '$order_update_id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $fetch_book = mysqli_fetch_assoc($result);
        // Update the total_price and set it as checked or unchecked based on the value
       // if ($accountant_id == $fetch_book['accountant_id']) {
            // Update the total_price and set it as checked or unchecked based on the value
            //$total_price_status = ($update_total_price_status == 300) ? 'correct' : 'incorrect';
            // Update the confirm_order table
            mysqli_query($conn, "UPDATE `confirm_order` SET total_price_status = '$total_price_status', date = '$date' WHERE order_id = '$order_update_id'") or die('query failed');
            $message[] = 'Total price status has been updated successfully.';
        } else {
            $message[] = 'You are not authorized to update the total price status.';
        }
    } else {
        $message[] = 'Order not found.';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/hello.css">

   <style>
      .cart-btn1,
      .cart-btn2 {
         display: inline-block;
         margin-top: 0.4rem;
         padding: 0.2rem 0.8rem;
         cursor: pointer;
         color: white;
         font-size: 15px;
         border-radius: .5rem;
         text-transform: capitalize;
      }

      .cart-btn1 {
         margin-left: 40%;
         background-color: red;
      }

      .cart-btn2 {
         background-color: #ffa41c;
         color: blue;
      }

      .placed-orders .title {
         text-align: center;
         margin-bottom: 20px;
         text-transform: uppercase;
         color: black;
         font-size: 40px;
      }

      .placed-orders .box-container {
         max-width: 1200px;
         margin: 0 auto;
         display: flex;
         flex-wrap: wrap;
         align-items: center;
         gap: 20px;
      }

      .placed-orders .box-container .empty {
         flex: 1;
      }

      .placed-orders .box-container .box {
         flex: 1 1 400px;
         border-radius: .5rem;
         padding: 15px;
         border: 2px solid rgb(9, 218, 255);
         background-color: white;
         padding: 10px 20px;
      }

      .placed-orders .box-container .box p {
         padding: 10px 0 0 0;
         font-size: 20px;
         color: gray;
      }

      .placed-orders .box-container .box p span {
         color: black;
      }

      .message {
         position: sticky;
         top: 0;
         margin: 0 auto;
         width: 61%;
         background-color: #eeee;
         padding: 6px 9px;
         display: flex;
         align-items: center;
         justify-content: space-between;
         z-index: 100;
         gap: 0px;
         border: 2px solid rgb(68, 203, 236);
         border-top-right-radius: 8px;
         border-bottom-left-radius: 8px;
      }

      .message span {
         font-size: 22px;
         color: rgb(240, 18, 18);
         font-weight: 400;
      }

      .message i {
         cursor: pointer;
         color: rgb(3, 227, 235);
         font-size: 15px;
      }
   </style>

</head>

<body>

   <?php include 'accountant_header.php'; ?>
   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
        <div class="message" id= "messages"><span>' . $message . '</span>
        </div>
        ';
      }
   }
   ?>

   <section class="placed-orders">

      <h1 class="title">placed orders</h1>

      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `confirm_order`") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_book = mysqli_fetch_assoc($select_orders)) {
         ?>
               <div class="box">
                  <p> Order Date : <span><?php echo $fetch_book['order_date']; ?></span> </p>
                  <p> Order Id : <span>#<?php echo $fetch_book['order_id']; ?> </p>
                  <p> Name : <span><?php echo $fetch_book['name']; ?></span> </p>
                  <p> Number : <span><?php echo $fetch_book['number']; ?></span> </p>
                  <p> Email: <span><?php echo $fetch_book['email']; ?></span> </p>
                  <p> Address : <span><?php echo $fetch_book['address']; ?></span> </p>
                  <p> account_paid : <span><?php echo $fetch_book['account_paid']; ?></span> </p>
                  <p> adminaccount: <span><?php echo $fetch_book['admin_account']; ?></span> </p>
                  <p> Your orders : <span><?php echo $fetch_book['total_books']; ?></span> </p>
                  <p> Total price :<span><?php echo $fetch_book['total_price']; ?></span><p>
                  <form action="" method="post">
                     <input type="hidden" name="order_id" value="<?php echo $fetch_book['order_id']; ?>">
                     Payment Status :<select name="update_payment">
                        <option value="" selected disabled><?php echo $fetch_book['payment_status']; ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                     </select>
                     <br>
                     Account Paid Status :
                     <select name="account_paid_status">
                        <option value="unpaid" <?php if ($fetch_book['account_paid_status'] == 'unpaid') echo 'selected'; ?>>Unpaid</option>
                        <option value="paid" <?php if ($fetch_book['account_paid_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                     </select>
                     <br>
                     Total Price Status :
                     <select name="total_price_status">
                        <option value="correct" <?php if ($fetch_book['total_price_status'] == 'correct') echo 'selected'; ?>>Correct</option>
                        <option value="incorrect" <?php if ($fetch_book['total_price_status'] == 'incorrect') echo 'selected'; ?>>Incorrect</option>
                     </select>

                     <input type="submit" value="update" name="update_order" class="cart-btn2">
                     <a class="cart-btn1" href="acc_order.php?delete=<?php echo $fetch_book['order_id']; ?>" onclick="return confirm('delete this order?');">delete</a>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>
      </div>

   </section>
   <!-- custom admin js file link  -->
   <script src="js/admin_script.js"></script>
   <script>
      setTimeout(() => {
         const box = document.getElementById('messages');

         // 👇️ hides element (still takes up space on page)
         box.style.display = 'none';
      }, 8000);
   </script>
</body>
</html>
