<?php
include 'config.php';
session_start();

// Initialize arrays to store dates, balances, and colors
$dates = array();
$balances = array();
$colors = array('red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet'); // Define colors for each day

// Retrieve balance history data from the database for each day of the week
for ($i = 0; $i < 7; $i++) {
    // Get the date for the current day of the week
    $date = date('Y-m-d', strtotime("-$i days"));

    // Query to retrieve balance for the current day
    $balance_query = "SELECT balance FROM cashier_account WHERE DATE(transaction_date) = '$date'";
    $balance_result = mysqli_query($conn, $balance_query);

    // Fetch balance for the current day
    if ($balance_result && mysqli_num_rows($balance_result) > 0) {
        $row = mysqli_fetch_assoc($balance_result);
        $balance = $row['balance'] ? $row['balance'] : 0;
        $dates[] = $date;
        $balances[] = $balance;
    } else {
        // If no balance is found for the current day, assume the balance is 5000 birr
        $dates[] = $date;
        $balances[] = 5000; // Assuming the balance is 5000 birr
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Account Balance Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php
  include 'cashier_header.php';
  ?>
    <h2>Cashier Account Balance Chart</h2>
    <canvas id="balanceChart" width="800" height="400"></canvas>

    <script>
        // Retrieve data from PHP arrays
        var dates = <?php echo json_encode(array_reverse($dates)); ?>;
        var balances = <?php echo json_encode(array_reverse($balances)); ?>;
        var colors = <?php echo json_encode($colors); ?>;

        // Create Chart.js chart
        var ctx = document.getElementById('balanceChart').getContext('2d');
        var datasets = [];
        for (var i = 0; i < dates.length; i++) {
            datasets.push({
                label: 'Balance',
                data: [balances[i]],
                borderColor: colors[i],
                borderWidth: 1,
                fill: false
            });
        }
        var balanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Balance (Birr)'
                        }
                    }]
                }
            }
        });
    </script>
      <?php
  include 'index_footer.php';
  ?>
</body>
</html>
