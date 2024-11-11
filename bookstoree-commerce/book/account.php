<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Account</title>
    
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
        <h3>User Account</h3>
            <label for="Id">USER ID:</label>
            <input type="text" name="Id" id="Id" required><br>
            <label for="name">User Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="account_number">User Account Number (CBE Birr restrictions):</label>
            <input type="text" name="account_number" id="account_number" required pattern="^1000\d{10}$" title="Please enter a 13-digit number starting with '1000'"><br>
            <label for="balance">Balance:</label>
            <input type="text" name="balance" id="balance" required><br>
            <input type="submit" name="insert" value="insert">
        </div>
    </form>

    <?php
include 'config.php';

if (isset($_POST['insert'])) {
    // Extract form data
    $Id = $_POST['Id'];
    $name = $_POST['name'];
    $account_number = $_POST['account_number'];
    $balance = $_POST['balance'];

    // Check if the ID and name exist in the users_info table
    $check_user_query = "SELECT * FROM users_info WHERE Id = '$Id' AND name = '$name'";
    $user_result = mysqli_query($conn, $check_user_query);
    if (mysqli_num_rows($user_result) > 0) {
        // ID and name exist in users_info table, proceed with insertion into insertuseraccount
        // Prepare and execute the SQL statement to insert values into the table
        $sql = "INSERT INTO insertuseraccount (Id, name, account_number, balance) VALUES ('$Id', '$name', '$account_number', '$balance')";

        if (mysqli_query($conn, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // ID or name does not exist in users_info table
        echo "Error: ID or name does not exist in users_info table.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

   <?php include 'index_footer.php'; ?>
</body>
</html>
