<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Account</title>
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h3 {
            color: #333;
        }

        form {
        max-width: 400px;
        margin: 0 auto;
        border: 1px solid #ccc; /* Add border */
        padding: 20px; /* Add padding */
        border-radius: 40px; /* Add border radius */
        background-color:  #e66565; /* Add background color */
    }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
    <form action="" method="post">
        <div class="mohaa">
        <h3>Update User Account</h3>
            <label for="Id">USER ID:</label>
            <input type="text" name="Id" id="Id" required><br>
            <label for="name">User Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="account_number">User Account Number (CBE Birr restrictions):</label>
            <input type="text" name="account_number" id="account_number" required pattern="^1000\d{10}$" title="Please enter a 13-digit number starting with '1000'"><br>
            <label for="balance">Balance:</label>
            <input type="text" name="balance" id="balance" required><br>
            <input type="submit" name="update" value="Update">
        </div>
    </form>

    <?php
    // Include your configuration file
    include 'config.php';
    // Check if the form is submitted
    if (isset($_POST['update'])) {
        // Extract form data
        $Id = $_POST['Id'];
        $name = $_POST['name'];
        $account_number = $_POST['account_number'];
        $balance = $_POST['balance'];

        // Check if all fields are filled out
        if (!empty($Id) && !empty($name) && !empty($account_number) && !empty($balance)) {
            // Check if the ID, name, and account number exist in the insertuseraccount table
            $check_query = "SELECT * FROM insertuseraccount WHERE Id = '$Id' AND name = '$name' AND account_number = '$account_number'";
            $result = mysqli_query($conn, $check_query);
            if (mysqli_num_rows($result) > 0) {
                // ID, name, and account number exist, update the balance
                $update_query = "UPDATE insertuseraccount SET balance = '$balance' WHERE Id = '$Id' AND name = '$name' AND account_number = '$account_number'";
                if (mysqli_query($conn, $update_query)) {
                    echo "Successfully updated.";
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                // ID, name, or account number does not exist
                echo "Error: ID, name, or account number does not exist in the insertuseraccount table.";
            }
        } else {
            echo "Please fill out all fields.";
        }

        // Close the database connection
        mysqli_close($conn);
    }
    ?>
<?php include 'index_footer.php'; ?>
</body>
</html>
s