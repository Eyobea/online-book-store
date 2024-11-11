<?php
include 'config.php';
session_start();
// Initialize arrays to store dates and book quantities
$dates = array();
$books_sold = array();

// Retrieve books sold each day from the database
for ($i = 0; $i < 7; $i++) {
    // Get the date for the current day of the week
    $date = date('Y-m-d', strtotime("-$i days"));

    // Query to retrieve books sold for the current day
    $books_query = "SELECT book_id, SUM(quantity) AS total_quantity FROM confirm_order WHERE DATE(date_time) = '$date' GROUP BY book_id";
    $books_result = mysqli_query($conn, $books_query);

    // Fetch books sold for the current day
    if ($books_result && mysqli_num_rows($books_result) > 0) {
        $books = array();
        while ($row = mysqli_fetch_assoc($books_result)) {
            $books[$row['book_id']] = $row['total_quantity'];
        }
        $dates[] = $date;
        $books_sold[] = $books;
    } else {
        // If no books sold for the current day, assume an empty array
        $dates[] = $date;
        $books_sold[] = array();
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
    <title>Daily Books Sold Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include 'accountant_header.php'; ?>
    <h2>Daily Books Sold Chart</h2>
    <canvas id="booksSoldChart" width="800" height="400"></canvas>

    <script>
        // Retrieve data from PHP arrays
        var dates = <?php echo json_encode(array_reverse($dates)); ?>;
        var booksSold = <?php echo json_encode(array_reverse($books_sold)); ?>;
        var colors = ['red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet'];

        // Create datasets for Chart.js
        var datasets = [];
        for (var i = 0; i < booksSold.length; i++) {
            var books = booksSold[i];
            var data = [];
            var labels = [];
            for (var book_id in books) {
                data.push(books[book_id]);
                labels.push('Book ' + book_id);
            }
            datasets.push({
                label: dates[i],
                data: data,
                backgroundColor: colors[i],
                borderColor: colors[i],
                borderWidth: 1
            });
        }

        // Create Chart.js chart
        var ctx = document.getElementById('booksSoldChart').getContext('2d');
        var booksSoldChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <?php include 'index_footer.php'; ?>
</body>
</html>
