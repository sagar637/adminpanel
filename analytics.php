<?php
include 'Function/connection.php';

if (empty($_SESSION["admin_id"])) {
    header('Location:index.php');
}

$admin_id = $_SESSION['admin_id'];

// get admin info
$admin = "SELECT * FROM `admins` WHERE `admin_id` = '$admin_id';";
$adminResult = $mysqli->query($admin);

// get reviews
$orders = "SELECT * FROM `order` WHERE `status` = 'pending' GROUP BY(order_set);";
$ordersResult = $mysqli->query($orders);
$orderNo = mysqli_num_rows($ordersResult);

$products = "SELECT * FROM `product`;";
$productsResult = $mysqli->query($products);
$productNo = mysqli_num_rows($productsResult);

$sales = "SELECT * FROM `product`;";
$salesResult = $mysqli->query($sales);

$salesNo = 0;
while ($rows = $salesResult->fetch_assoc()) {
    $salesNo += $rows['sales'];
}

$customer = "SELECT * FROM `users`;";
$customerResult = $mysqli->query($customer);
$customerNo = mysqli_num_rows($customerResult);

$revenue = "SELECT * FROM `product`;";
$revenueResult = $mysqli->query($revenue);

$revenueNo = 0;
while ($rows = $revenueResult->fetch_assoc()) {
    $revenueNo += ($rows['sales'] * $rows['discount']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="lib/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* CRM-style layout */
        .analytics {
            margin: 20px;
            padding: 20px;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .data {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-basis: calc(25% - 20px);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data h3 {
            margin: 0;
        }

        .data i {
            font-size: 36px;
            color: #4e73df;
        }

        .profit {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .profit img {
            width: 80px;
            height: 80px;
        }

        .profit h3 {
            margin: 0;
        }

        .charts {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .chart-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        canvas {
            max-width: 100%;
            height: 300px;
        }
    </style>
</head>

<body>
    <section class="body">
        <section class="navbar">
            <h2 class="logo">
                <img src="images/logo.png">
                Online Grocery Store
            </h2>
            <div class="navigation">
                <div class="nav-heading">
                    <h3><i class="fa-solid fa-chalkboard-user"></i> dashboard</h3>
                    <a href="analytics.php" class="nav-active">• sales analytics</a>
                    <a href="sales.php">• seller profits</a>
                </div>
                <div class="nav-heading">
                    <h3><i class="fa-solid fa-boxes-stacked"></i> products</h3>
                    <a href="product.php">• product grid</a>
                    <a href="product-manage.php">• manage products</a>
                    <a href="product-add.php">• add new product</a>
                </div>
                <div class="nav-heading">
                    <h3><i class="fa-solid fa-cart-shopping"></i> orders</h3>
                    <a href="orders.php">• orders</a>
                </div>
                <div class="nav-heading">
                    <h3><i class="fa-solid fa-users"></i> customer</h3>
                    <a href="customers.php">• customers</a>
                    <a href="reviews.php">• reviews</a>
                </div>
                <div class="nav-heading">
                    <h3><i class="fa-solid fa-server"></i> about</h3>
                    <a href="gallery.php">• gallery</a>
                    <a href="blogs.php">• blog</a>
                </div>
            </div>
        </section>
        <section class="main">
            <header>
                <h2 class="page-heading">sales analytics</h2>

                <div class="admin-display">
                    <img src="images/default.png">
                    <div class="admin-info">
                        <?php
                        while ($rows = $adminResult->fetch_assoc()) {
                            echo "<h3>" . $rows['username'] . "</h3>";
                            echo "<p>" . $rows['email'] . "</p>";
                        }
                        ?>
                    </div>
                    <a href="function/logout.php" class="btn">logout</a>
                </div>
            </header>

            <div class="analytics">
                <!-- Stats dashboard -->
                <div class="dashboard">
                    <div class="data">
                        <div>
                            <h3><?php echo $orderNo ?></h3>
                            <span>New Order<?php if ($orderNo != 1) {
                                echo "s";
                            } ?></span>
                        </div>
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="data">
                        <div>
                            <h3><?php echo $productNo ?></h3>
                            <span>Products</span>
                        </div>
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <div class="data">
                        <div>
                            <h3><?php echo $salesNo ?></h3>
                            <span>Sales Made</span>
                        </div>
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="data">
                        <div>
                            <h3><?php echo $customerNo ?></h3>
                            <span>Customers</span>
                        </div>
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>

                <!-- Total revenue row -->
                <div class="profit">
                    <img src="images/sales.png">
                    <h3>
                        <p>Rs. <?php echo $revenueNo ?></p>
                        <span>Total Revenue</span>
                    </h3>
                </div>

                <!-- Charts section -->

            </div>
            <div class="charts">
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </section>
    </section>

    <script>
        // Data for charts
        const orderData = <?php echo $orderNo; ?>;
        const productData = <?php echo $productNo; ?>;
        const salesData = <?php echo $salesNo; ?>;
        const customerData = <?php echo $customerNo; ?>;
        const revenueData = <?php echo $revenueNo; ?>;

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Orders', 'Products', 'Sales', 'Customers'],
                datasets: [{
                    label: 'Overview',
                    data: [orderData, productData, salesData, customerData],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],  // Example months
                datasets: [{
                    label: 'Revenue Over Time',
                    data: [12000, 19000, 3000, 5000, 20000, revenueData],  // Example data, replace with actual
                    fill: false,
                    borderColor: '#4e73df',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Products', 'Sales'],
                datasets: [{
                    data: [productData, salesData],
                    backgroundColor: ['#1cc88a', '#36b9cc']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>

</html>