<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Account Balance</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; /* Change font family to Times New Roman */
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: red;
        }
        .container {
            width: 80%;
            margin: 10px auto;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include 'cashier_header.php'; ?> 
<br>
<h1>Cashier Account Balance</h1>
    <div class="container">
        <?php
        // Include the database connection file
        include 'config.php';

        // Query to fetch balance from the cashier_account table
        $query = "SELECT id, name, account_number, balance, transaction_date FROM cashier_account";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
            // Check if there are any rows returned
            if (mysqli_num_rows($result) > 0) {
                // Start the table
                echo "<table>";
                echo "<thead>";
                echo "<tr><th>ID</th><th>Name</th><th>Account Number</th><th>Date</th><th>Balance</th></tr>";
                echo "</thead>";
                echo "<tbody>";

                // Loop through each row of the result set
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display data in each row
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['account_number'] . "</td>";
                    echo "<td>" . $row['transaction_date'] . "</td>";
                    echo "<td>$" . $row['balance'] . "</td>"; // Assuming balance is in currency format
                    echo "</tr>";
                }

                // Close the table
                echo "</tbody>";
                echo "</table>";
            } else {
                // If no rows were returned, display a message
                echo "No records found";
            }
        } else {
            // If the query failed, display an error message
            echo "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
    <div class="footer">
        <?php include 'index_footer.php'; ?>
    </div>
</body>
</html>
