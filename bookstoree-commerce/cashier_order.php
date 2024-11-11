<?php
include 'config.php';
session_start();

// Check if the user is logged in and is a cashier
if (!isset($_SESSION['cashier_id'])) {
    header('location: login.php');
    exit;
}

// Get the cashier ID from the session
$cashier_id = $_SESSION['cashier_id'];

// Process form submissions
if (isset($_POST['update_account_paid'])) {
    $order_update_id = $_POST['order_id'];
    $update_account_paid_status = $_POST['account_paid_status'];

    // Fetch the order details from the database
    $result = mysqli_query($conn, "SELECT * FROM `confirm_order` WHERE order_id = '$order_update_id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $fetch_order = mysqli_fetch_assoc($result);

        // Check if the logged-in cashier's ID matches the ID associated with the order
       // if ($cashier_id == $fetch_order['cashier_id']) {
            // Update the account_paid_status
            mysqli_query($conn, "UPDATE `confirm_order` SET account_paid_status = '$update_account_paid_status' WHERE order_id = '$order_update_id'") or die('query failed');
            $message[] = 'Account paid status has been updated successfully.';
        } else {
            $message[] = 'You are not authorized to update the account paid status for this order.';
        }
    } else {
        $message[] = 'Order not found.';
    }

// Process order deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `confirm_order` WHERE order_id = '$delete_id'") or die('query failed');
    header('location: cashier_order.php');
    exit;
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
   padding:0.2rem 0.8rem;
   cursor: pointer;
   color:white;
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
   <?php include 'cashier_header.php'; ?>
   <?php
   // Display messages if any
   if (isset($message)) {
       foreach ($message as $msg) {
           echo '<div class="message"><span>' . $msg . '</span></div>';
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
                  <p> cashier account: <span><?php echo $fetch_book['cashier_account']; ?></span> </p>
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
                     <label for="account_paid_status">Account Paid Status:</label>
                            <select name="account_paid_status" id="account_paid_status">
                                <option value="unpaid" <?php if ($fetch_book['account_paid_status'] == 'unpaid') echo 'selected'; ?>>Unpaid</option>
                                <option value="paid" <?php if ($fetch_book['account_paid_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                            </select>
<br>
     Total Price Status :
<select name="total_price_status">
    <option value="correct" <?php if ($fetch_book['total_price_status'] == 'correct') echo 'selected'; ?>>Correct</option>
    <option value="incorrect" <?php if ($fetch_book['total_price_status'] == 'incorrect') echo 'selected'; ?>>Incorrect</option>
</select>
                        
                                <input type="submit" value="Update" name="update_account_paid" class="cart-btn2">
                                <a class="cart-btn1" href="cashier_order.php?delete=<?php echo $fetch_book['order_id']; ?>" onclick="return confirm('delete this order?');">delete</a>
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
