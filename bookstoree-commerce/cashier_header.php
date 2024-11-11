<?php 
//session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin.css">
    <title>Cashier Page</title>
    <style>
    .sub-menu-wrap{
position: fixed;
top: 9%;
right: -1%;
width: 320px;
max-height: 0px;
overflow: hidden;
transition: max-height 0.5s;
z-index: 100;

}
.sub-menu-wrap.open-menu{ max-height: 400px;
}
.sub-menu{
background: rgb(68, 109, 245);
padding: 20px;
margin: 10px;
border-bottom-right-radius: 16px;
border-bottom-left-radius: 16px;
}
.user-info{
display: flex;
align-items: center;
}
.user-info h3{
font-weight: 500;
}
.user-info img{
width: 60px;
border-radius: 50%;
margin-right: 15px;
}
.sub-menu hr{
border: 0;
height: 1px;
width: 100%;
background: #f017a8;
margin: 15px 10px;
}
.sub-menu-link{
display: flex;
align-items: center;
text-decoration: none;
color: #525252;
margin: 12px e;
}
.sub-menu-link p{
width: 100%;
}
.sub-menu-link img{
width: 40px;
background: #e5e5e5;
border-radius: 50%;
padding: 8px;
margin-right: 15px;
}
.sub-menu-link span{
font-size: 22px;
transition: transform 0.5s;
}
.sub-menu-link:hover span{
transform: translateX(5px);
}
.sub-menu-link:hover p{
font-weight: 600;
}
.link_btn{
  background-color: brown;
    padding: 6px;
    border-radius: 10px;
    margin-left: 10px;
    color: #4444;
    font-weight: 500;
}
  </style>
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
        <a href="cash_balance.php">Cash balance</a>
        <a href="contact cashier.php">Contact</a>
 
    </div>
    <div class="user-box" style="display: flex; align-items:center;">
   <!-- <a class="Btn" href="search_books.php"><img style="height:30px;" src="./images/cashier.jpg" alt=""></a>-->
    <?php if(isset($_SESSION['cashier_name'])){echo' <img style="height:40px; margin-left:10px ;" src="images/cashier.jpg" class="user-pic" onclick="toggleMenu()" />';}
      else{
        echo'<div class="use_links"><a class="link_Btn" style="background-color: rgb(0, 167, 245);
        padding: 6px;
        border-radius: 10px;
        margin-left: 10px;
        color: white;
        font-weight: 500;" href="login.php">Login</a><a class="link_Btn" style="background-color: rgb(0, 167, 245);
        padding: 6px;
        border-radius: 10px;
        margin-left: 10px;
        color: white;
        font-weight: 500;" href="register.php">Register</a></div>';

    }?>
     </div> 
</header>
<div class="sub-menu-wrap" id="subMenu">
  <div class="sub-menu">
    <div class="user-info">
      <img src="images/cashier.jpg" />
      <div class="user-info" style="display: block;">
      <h3>Hello, <?php echo isset($_SESSION['cashier_name']) ? $_SESSION['cashier_name'] : ''; ?></h3>
      <h6><?php echo isset($_SESSION['cashier_email']) ? $_SESSION['cashier_email'] : ''; ?></h6>
      </div>
    </div>
    <hr />
    <a href="contact cashier.php" class="sub-menu-link">
      <p>Contact</p>
      <span>></span>
    </a>
    <a href="cash_balance.php" class="sub-menu-link">
      <p>Cash Balance</p>
      <span>></span>
    </a>
    <a href="tot_book.php" class="sub-menu-link">
      <p>Total Book</p>
      <span>></span>
    </a>
    <a href="cashier_account.php" class="sub-menu-link">
      <p>Cashier Account</p>
      <span>></span>
    </a>
    <a href="cash_display.php" class="sub-menu-link">
      <p>Cash display</p>
      <span>></span>
    </a>
    <a href="logout.php" class="sub-menu-link">
      <p style="background-color: red; border-radius:8px; text-align:center; color:white; font-weight:600; margin-top:5px; padding:5px;">Logout</p>
    </a>
  </div>
</div>
<script>
  let subMenu = document.getElementById("subMenu");
  function toggleMenu(){ subMenu.classList.toggle("open-menu");
  }
</script>
</body>
</html>
