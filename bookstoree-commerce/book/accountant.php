<?php
include 'config.php';
session_start();
$accountant_id = $_SESSION['accountant_id'];

if (!isset($accountant_id)) {
   header('location:login.php');
}
$books_no = $conn->query("SELECT * FROM book_info ") or die('query failed');
$bookscount = mysqli_num_rows($books_no);
$orders = $conn->query("SELECT * FROM confirm_order ") or die('query failed');
$orders_count = mysqli_num_rows($orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="./css/admin.css"/>
    <title>ONLINE BOOK STORE E-COMMERCE SYSTEM</title>
</head>
<body>
<?php include 'accountant_header.php'; ?>
<br/>
<div class="main_box">
    <div class="card" style="width: 15rem">
        <?php
        $total_unchecked = 0;
        $select_unchecked = mysqli_query($conn, "SELECT total_price FROM `confirm_order` WHERE total_price = 'unchecked'") or die('query failed');
        if (mysqli_num_rows($select_unchecked) > 0) {
            while ($fetch_unchecked = mysqli_fetch_assoc($select_unchecked)) {
                $total_price = $fetch_unchecked['total_price'];
                $total_unchecked += $total_price;
            };
        };
        ?>
        <img class="card-img-top" src="./images/pen3.png" alt="Card image cap"/>
        <div class="card-body">
            <h5 class="card-title">Number Of Pending Orders in ₨</h5>
            <p class="card-text">
                <?php echo $total_unchecked ?>
            </p>
            <div class="buttons" style="display: flex;">
                <a href="acc_order.php" class="btn btn-primary">Details</a>
            </div>
        </div>
    </div>
    <div class="card" style="width: 15rem">
        <?php
        $total_checked = 0;
        $select_checked = mysqli_query($conn, "SELECT total_price FROM `confirm_order` WHERE total_price = 'checked'") or die('query failed');
        if (mysqli_num_rows($select_checked) > 0) {
            while ($fetch_checked = mysqli_fetch_assoc($select_checked)) {
                $total_price = $fetch_checked['total_price'];
                $total_checked += $total_price;
            };
        };
        ?>
        <img class="card-img-top" src="./images/compn.png" alt="Card image cap"/>
        <div class="card-body">
            <h5 class="card-title">Number Of Completed Orders in ₨</h5>
            <p class="card-text">
                <?php echo $total_checked; ?>
            </p>
            <div class="buttons" style="display: flex;">
                <a href="acc_order.php" class="btn btn-primary">Details</a>
            </div>
        </div>
    </div>
    <div class="card" style="width: 15rem">
        <img class="card-img-top" src="./images/orderpn.png" alt="Card image cap"/>
        <div class="card-body">
            <h5 class="card-title">Number Of Orders Received</h5>
            <p class="card-text">
                <?php echo $orders_count; ?>
            </p>
            <a href="acc_order.php" class="btn btn-primary">Details</a>
        </div>
    </div>
    <div class="card" style="width: 15rem">
        <img class="card-img-top" src="./images/nu. books.png" alt="Card image cap"/>
        <div class="card-body">
            <h5 class="card-title">Number Of Books Available</h5>
            <p class="card-text">
                <?php echo $bookscount; ?>
            </p>
            <div class="buttons" style="display: flex;">
                <a href="total_book.php" class="btn btn-primary">See Books</a>
            </div>
        </div>
    </div>
</div>
<?php include 'index_footer.php'; ?>
</body>
</html>
