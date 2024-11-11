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
    <title>Accountant Page</title>
</head>
<body>
<header><div class="mainlogo">
        <div class="logo">
            <a href="accountanat_index.php"><span>ONLINE BOOK STORE </span>
           ></a>
        </div><p>Accountant Pannel</p></div>
        <div class="nav">
            <a href="accountant.php">Home</a>
            <a href="tot_book.php">total_book</a>
        </div>
        <div class="right">
            <div class="log_info">
                <p>Hello <?php echo $_SESSION['accountant_name'];?></p> 
            </div>
            <a class="Btn" href="logout.php?logout=<?php echo $_SESSION['accountant_name'];?>">logout</a>
        </div>
    </header>
</body>
</html>