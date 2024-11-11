<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit(); // Stop executing the script after redirection
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/hello.css">

   <style>
      body {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f5f5f5;
}

.container {
   max-width: 1200px;
   margin: 0 auto;
   padding: 20px;
}

/* Header Styles */
.header {
   background-color: #333;
   color: #fff;
   padding: 10px 0;
   text-align: center;
}

.header h1 {
   margin: 0;
}

/* Placed Orders Section */
.placed-orders {
   background-color: #fff;
   padding: 20px;
   margin-top: 20px;
}

.title {
   text-align: center;
   margin-bottom: 20px;
   text-transform: uppercase;
   color: #333;
   font-size: 40px;
}

.box-container {
   display: flex;
   flex-wrap: wrap;
   justify-content: space-between;
   gap: 20px;
}

.box {
   flex: 0 1 calc(33.33% - 20px);
   border-radius: 5px;
   border: 2px solid #ccc;
   background-color: #fff;
   padding: 20px;
}

.box p {
   margin: 0;
   padding: 5px 0;
   font-size: 18px;
   color: #666;
}

.box p span {
   font-weight: bold;
   color: #333;
}

.box form {
   margin-top: 10px;
}

.box form select,
.box form input[type="submit"] {
   margin-top: 5px;
   padding: 5px;
   font-size: 16px;
}

.box form select {
   width: 100%;
}

.box form input[type="submit"] {
   background-color: #333;
   color: #fff;
   border: none;
   cursor: pointer;
}

.box form input[type="submit"]:hover {
   background-color: #555;
}

.empty {
   flex: 0 1 100%;
   text-align: center;
   font-size: 20px;
   color: #333;
}
   </style>
</head>
<body>
   
<?php include 'index_header.php'; ?>
<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
        $select_book = mysqli_query($conn, "SELECT * FROM `confirm_order` WHERE user_id = '$user_id' ORDER BY order_date DESC") or die('query failed');
        if(mysqli_num_rows($select_book) > 0){
            while($fetch_book = mysqli_fetch_assoc($select_book)){
      ?>
      <div class="box">
         <p> Order Date : <span><?php echo $fetch_book['order_date']; ?></span> </p>
         <p> Order Id : <span># <?php echo $fetch_book['order_id']; ?> </p>
         <p> Name : <span><?php echo $fetch_book['name']; ?></span> </p>
         <p> Mobile Number : <span><?php echo $fetch_book['number']; ?></span> </p>
         <p> Email Id : <span><?php echo $fetch_book['email']; ?></span> </p>
         <p> Address : <span><?php echo $fetch_book['address']; ?></span> </p>
         <p> Account Paid : <span><?php echo $fetch_book['account_paid']; ?></span> </p>
         <p> Cashier Account: <span><?php echo $fetch_book['cashier_account']; ?></span> </p> <!-- Include Cashier Account -->
         <p> Your orders : <span><?php echo $fetch_book['total_books']; ?></span> </p>
         <p> Total price : <span>birr <?php echo $fetch_book['total_price']; ?></span> </p>
         <p> Payment status : <span style="color:<?php if($fetch_book['payment_status'] == 'pending'){ echo 'orange'; }else{ echo 'green'; } ?>;"><?php echo $fetch_book['payment_status']; ?></span> </p>
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
            <input type="submit" name="update_order" value="Update">
         </form>
         <p><a href="invoice.php?order_id=<?php echo $fetch_book['order_id']; ?>" target="_blank">Print Receipt</a></p>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">You have not placed any order yet!!!!</p>';
      }
      ?>
   </div>

</section>
<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'index_footer.php'; ?>
</body>
</html>
