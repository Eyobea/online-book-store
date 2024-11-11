<!-- <?php 
// session_start();
// if(isset($_GET['logout'])){
//     header('location:login.php');
// }
// ?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin.css">
    <title>Cashier Page</title>
</head>
<body>
<header>
    <div class="mainlogo">
        <div class="logo">
            <a href="cashier.php"><span>ONLINE BOOK STORE</span></a>
        </div>
        <p>Cashier Panel</p>
    </div>
    <div class="nav">
        <a href="cashier.php">Home</a>
        <a href="tot_book.php">Total Books</a>
    </div>
    <div class="right">
        <div class="log_info">
            <p>Hello <?php echo isset($_SESSION['cashier_name']) ? $_SESSION['cashier_name'] : ''; ?></p>
        </div>
        <a class="Btn" href="logout.php?logout=<?php echo isset($_SESSION['cashier_name']) ? $_SESSION['cashier_name'] : ''; ?>">Logout</a>
    </div>
</header>

</body>
</html>