<?php
include 'config.php';
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <title>Existing Books</title>
    
</head>
<body>
    
    <?php
    include './cashier_header.php'
    ?>

    <section class="show-products">
        <div class="box-container">
            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` ORDER BY date DESC") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                $total_price = 0; // Initialize total price variable
                ?>
                <table>
                    <tr>
                        <th>Author</th>
                        <th>Name</th>
                        <th>Price</th>
                    </tr>
                    
                    <?php
                    while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                        ?>
                        <tr>
                            <td><?php echo $fetch_book['title']; ?></td>
                            <td><?php echo $fetch_book['name']; ?></td>
                            <td><?php echo $fetch_book['price']; ?></td>
                        </tr>
                        <?php
                        // Add the price of each book to the total price
                        $total_price += $fetch_book['price'];
                    }
                    ?>
                    <tr>
                        <td colspan="2"><strong>Total Price:</strong></td>
                        <td><strong><?php echo $total_price; ?></strong></td>
                    </tr>
                </table>
                <?php
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <script src="./js/admin.js"></script>
</body>

</html>
