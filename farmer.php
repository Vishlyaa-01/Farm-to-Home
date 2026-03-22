<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
        header("location: index.php");
        exit;
    }
            
    include 'db.php';

    $username = $_SESSION['username'];

    $sql = "SELECT farmer_name FROM farmer WHERE farmer_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $farmer_name = $row['farmer_name'];
    } else {
        $farmer_name = "Unknown Farmer";
    }

    $sql = "SELECT farmer_id FROM farmer WHERE farmer_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $farmer_id = $row['farmer_id'];
    } else {
        $farmer_id =0;
    }

    $sql = "SELECT COUNT(*) AS active_crops
        FROM crop c
        INNER JOIN farmer f ON c.farmer_id = f.farmer_id
        WHERE f.farmer_username = ?
        AND c.crop_status = 'available'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo 'SQL Prepare failed: (' . $conn->errno . ') ' . $conn->error;
    }
    
    if (isset($_GET['id'])) {
        $crop_id = $_GET['id'];
        $username = $_SESSION['username'];
        if ($result->num_rows > 0) {
            $delete = "DELETE FROM crop WHERE crop_id = ?";
            $stmt = $conn->prepare($delete);
            $stmt->bind_param("i", $crop_id);
            if ($stmt->execute()) {
                header("Location: farmer.php");
                echo "<script>alert('Crop Details Deleted!'); window.location.href='farmer.php';</script>";
                exit();
            } 
        } 
    }
    $order_sql = "
        SELECT o.order_id, o.order_qty, o.price_per_kg, o.total_price, 
            o.order_status, o.order_date,
            c.crop_name,
            b.buyer_name, b.buyer_contact_no
        FROM orders o
        INNER JOIN crop c ON o.crop_id = c.crop_id
        INNER JOIN buyer b ON o.buyer_id = b.buyer_id
        WHERE c.farmer_id = ?
        ORDER BY o.order_id DESC
    ";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $farmer_id);
    $order_stmt->execute();
    $orders = $order_stmt->get_result();
    $pending = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE order_status='pending'")->fetch_assoc()['c'];
    $delivered = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE order_status='delivered'")->fetch_assoc()['c'];

    $earningQuery = "
        SELECT SUM(total_price) AS total_earning 
        FROM orders o
        INNER JOIN crop c ON o.crop_id = c.crop_id
        WHERE c.farmer_id = ?
        AND o.order_status = 'delivered'
    ";

    $stmt = $conn->prepare($earningQuery);
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $earningRow = $result->fetch_assoc();

    $total_earning = $earningRow['total_earning'] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm2Home - Farmer Dashboard</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        .logo img{
            height: 60px;
            width: auto;
            margin-left: 180px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.15);
        }

        /* Table header */
        .order-table thead {
            background-color: #28a745;
            color: white;
            text-align: left;
        }

        .order-table thead th {
            padding: 12px 10px;
            font-size: 16px;
        }

        /* Table rows */
        .order-table tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        /* Hover effect */
        .order-table tbody tr:hover {
            background-color: #f4f4f4;
            cursor: pointer;
        }

        /* Status color badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            color: white;
        }

        .pending { background: orange; }
        .accepted { background: #007bff; }
        .delivered { background: #28a745; }
        .cancelled { background: red; }

        .deliver-btn {
            background: #2d6a4f;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .deliver-btn:hover {
            background: #1b4332;
        }
    </style>

</head>

<body>
    <header>
        <div class="logo"><img src="img\logo.png" alt="logo here"></div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="index.php"><i class=" fa-solid fa-circle-info"></i> About</a></li>
                <li><a href="#translate"><i class="fa-solid fa-language"></i> Translate/भाषा बदला</a></li>
                <li><a href="#"><i class="fa-solid fa-phone"></i> Contact</a></li>
                <li class="logout-link">
                    <form action="logout.php" method="POST" class="logout">
                        <i class="fa-solid fa-right-from-bracket"></i><a href="index.php">Logout</a>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <aside class="sidebar" id="sidebar">
        <div class="profile-section">
            <img src="img/user.png" alt="User" class="user-image">
            <h3 class="farmer-name"> <?php echo htmlspecialchars($farmer_name); ?></h3>
            <h3 class="farmer-name"> ID : <?php echo htmlspecialchars($farmer_id); ?></h3>
        </div>
        <ul>
            <li class="active" onclick="showSection('dashboard', event)"><i class="fa-solid fa-house"></i> Dashboard</li>
            <li onclick="showSection('crops', event)"><i class="fa-solid fa-carrot"></i> My Crops</li>
            <li onclick="showSection('orders', event)"><i class="fa-solid fa-list-check"></i>  Orders</li>
            <li onclick="showSection('profile', event)"><i class="fa-solid fa-address-card"></i> Profile</li>
        </ul>
    </aside>

    <main class="main-content">
        <?php
            include 'db.php';
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $farm_survey_number = $_POST['farm_survey_number'] ?? '';
                $farm_land_in_acers = $_POST['farm_land_in_acers'] ?? '';
                $farm_location = $_POST['farm_location'] ?? '';
                $is_organic_certified = $_POST['is_organic_certified'] ?? '';
                $water_resource = $_POST['water_resource'] ?? '';
                $farm_type = $_POST['farm_type'] ?? '';
                $farmer_id = $_POST['farmer_id'] ?? '';

                // Insert query
                $sql = "INSERT INTO `farm` (`farm_survey_number`, `farm_land_in_acers`, `farm_location`, `is_organic_certified`, `water_resource`, `farm_type`, `farmer_id`) VALUES ('$farm_survey_number', '$farm_land_in_acers', '$farm_location', '$is_organic_certified', '$water_resource', '$farm_type', '$farmer_id');";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Farm details added successfully!'); window.location.href='farmer.php';</script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }

        ?>
        <section id="dashboard" class="content-section active">
           <h2>Welcome, <?php echo htmlspecialchars($farmer_name); ?></h2>
            <div class="stats-cards">
                <div class="card">
                    <h3><i class="fa-solid fa-list"></i> Active Listings</h3>
                    <p> <?php echo  $row['active_crops'] ?></p>
                </div>
                <div class="card">
                    <h3><i class="fa-solid fa-clock"></i> Pending Orders</h3>
                    <p><?= $pending ?></p>
                </div>
                <div class="card">
                    <h3><i class="fa-solid fa-list-check"></i> Completed Orders</h3>
                    <p><?= $delivered ?></p>
                </div>
                <div class="card">
                    <h3><i class="fa-solid fa-indian-rupee-sign"></i> Total Earnings</h3>
                    <p>₹<?php echo $total_earning; ?></p>
                </div>
            </div>
            <div class="form-container">
                <h2>Add New Farm Details</h2>
                <form action="farmer.php" method="POST">
                    <label for="farm_survey_number">Farm Survey Number</label>
                    <input type="text" id="farm_survey_number" name="farm_survey_number" required>

                    <label for="farm_land_in_acers">Land Area (in acres)</label>
                    <input type="number" id="farm_land_in_acers" name="farm_land_in_acers" step="0.01" required>

                    <label for="farm_location">Location</label>
                    <input type="text" id="farm_location" name="farm_location" required>

                    <label for="is_organic_certified">Organic Certified</label>
                    <select id="is_organic_certified" name="is_organic_certified" required>
                        <option value="">--Select--</option>
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>

                    <label for="water_resource">Water Resource</label>
                    <select id="water_resource" name="water_resource" required>
                        <option value="">--Select--</option>
                        <option value="Well">Well</option>
                        <option value="Borewell">Borewell</option>
                        <option value="Canal">Canal</option>
                        <option value="River">River</option>
                        <option value="Pond">Pond</option>
                    </select>

                    <label for="farm_type">Farm Type</label>
                    <input type="text" id="farm_type" name="farm_type" placeholder="e.g. Mixed, Dairy, Crop-based" required>

                    <label for="farmer_id">Farmer Id</label>
                    <input type="number" name="farmer_id" value="farmer_id">

                    <button type="submit">Submit Farm Details</button>
                </form>
            </div>
        </section>

        <?php
            include 'db.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $crop_category = $_POST['crop_category'];
                $crop_name = $_POST['crop_name'];
                $crop_quantity_in_kg = $_POST['crop_quantity_in_kg'];
                $crop_price_per_kg = $_POST['crop_price_per_kg'];
                $crop_description = $_POST['crop_description'];
                $crop_status = $_POST['crop_status'];
                $crop_harvest_date = $_POST['crop_harvest_date'];
                $best_before_days = $_POST['best_before_days'];
                $farmer_id = $_POST['farmer_id'];

                // Insert query
                $sql = "INSERT INTO `crop` (`crop_category`, `crop_name`, `crop_quantity_in_kg`, `crop_price_per_kg`, `crop_description`, `crop_status`, `crop_harvest_date`, `best_before_days`, `farmer_id`) VALUES ('$crop_category', '$crop_name', '$crop_quantity_in_kg', '$crop_price_per_kg', '$crop_description', '$crop_status', '$crop_harvest_date', '$best_before_days', '$farmer_id');";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Crop added successfully!'); window.location.href='farmer.php';</script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        ?>
        <section id="crops" class="content-section">
            <div class="form-container">
                <h2>Add New Crop</h2>
                <form action="cropInsert.php" method="POST">
                    <label for="crop_category">Crop Category</label>
                    <input type="text" id="crop_category" name="crop_category"  placeholder="Vegetable,Fruit,,Cereal,Pulses,Oilseeds,Spices,Mixed" required>

                    
                    <label for="crop_name">Crop Name</label>
                    <input type="text" id="crop_name" name="crop_name" required>
                    <label for="crop_quantity_in_kg">Quantity (in kg)</label>
                    <input type="number" id="crop_quantity_in_kg" name="crop_quantity_in_kg" required>

                    <label for="crop_price_per_kg">Price (per kg)</label>
                    <input type="number" id="crop_price_per_kg" name="crop_price_per_kg" required>

                    <label for="crop_description">Description</label>
                    <textarea id="crop_description" name="crop_description" rows="3"></textarea>

                    <label for="crop_status">Status</label>
                    <select id="crop_status" name="crop_status">
                        <option value="Available">Available</option>
                        <option value="Sold">Sold</option>
                        <option value="Pending">Pending</option>
                    </select>

                    <label for="crop_harvest_date">Harvest Date</label>
                    <input type="date" id="crop_harvest_date" name="crop_harvest_date" required>

                    <label for="best_before_days">Best Before (in days)</label>
                    <input type="number" id="best_before_days" name="best_before_days" required>

                    <label for="farmer_id">Farmer Id</label>
                    <input type="number" id="farmer_id" name="farmer_id" required>

                    <button type="submit">Add Crop</button>
                </form>
            </div>
            <h2>My Crops</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Crop Name</th>
                        <th>Quantity (Kg)</th>
                        <th>Price / Kg</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include 'db.php';
                        $user = $_SESSION['username'];
                        $sql = "SELECT * FROM `crop` WHERE `farmer_id` = (SELECT `farmer_id` FROM `farmer` WHERE `farmer_username` = '$user')";
                        $result = mysqli_query($conn, $sql);
                        $crop_id = 0;
                        while ($row = $result->fetch_assoc()) {
                            $crop_id++;
                            echo "
                            <tr>
                                <td>{$crop_id}</td>
                                <td>" . htmlspecialchars($row['crop_name']) . "</td>
                                <td>" . htmlspecialchars($row['crop_quantity_in_kg']) . "</td>
                                <td>₹" . htmlspecialchars($row['crop_price_per_kg']) . "</td>
                                <td>" . htmlspecialchars($row['crop_status']) . "</td>
                                <td>
                                    <a href='farmer.php?id={$row['crop_id']}' class='edit-btn'>Edit</a>
                                    <a href='farmer.php?id={$row['crop_id']}' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this crop?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </section>

        <?php
            include 'db.php';
            
            $user = $_SESSION['username'];
            $sql = "SELECT `farmer_id`, `farmer_name`, `farmer_age`, `farmer_gender`, `farmer_address`, `city`, `state`, `pin_code`, `farmer_aadhar_no`, `farmer_pan_no`, `farmer_contact_no`, `farmer_mail_id` FROM `farmer` WHERE `farmer_username` = '$user'";
            $result = mysqli_query($conn, $sql);
            $farmer = mysqli_fetch_assoc($result);
            
            $farm_sql = "SELECT `farm_survey_number`, `farm_land_in_acers`, `farm_location`, `is_organic_certified`, `water_resource`, `farm_type` FROM `farm` WHERE `farmer_id` = '{$farmer['farmer_id']}'";
            $farm_result = mysqli_query($conn, $farm_sql);
            $farm = mysqli_fetch_assoc($farm_result);
        ?>
        <section id="profile" class="content-section">
            <div class="profile-info-wrapper">
                <div class="profile-card">
                    <h2 class="profile-heading">Farmer's Personal Information</h2>
                    <table class="profile-table">
                        <tr>
                            <th>Farmer ID</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_id']); ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_age']); ?></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_gender']); ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_address']); ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td><?php echo htmlspecialchars($farmer['city']); ?></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td><?php echo htmlspecialchars($farmer['state']); ?></td>
                        </tr>
                        <tr>
                            <th>Pin Code</th>
                            <td><?php echo htmlspecialchars($farmer['pin_code']); ?></td>
                        </tr>
                        <tr>
                            <th>Aadhaar No</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_aadhar_no']); ?></td>
                        </tr>
                        <tr>
                            <th>PAN No</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_pan_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Contact No</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_contact_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Email ID</th>
                            <td><?php echo htmlspecialchars($farmer['farmer_mail_id']); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="profile-card">
                    <h2 class="profile-heading">Farmer's Farm Information</h2>
                    <table class="profile-table">
                        <tr>
                            <th>Survey Number</th>
                            <td><?php echo !empty($farm['farm_survey_number']) ? htmlspecialchars($farm['farm_survey_number']) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Land Area (in acres)</th>
                            <td><?php echo !empty($farm['farm_land_in_acers']) ? htmlspecialchars($farm['farm_land_in_acers']) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><?php echo !empty($farm['farm_location']) ? htmlspecialchars($farm['farm_location']) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Organic Certified</th>
                            <td>
                                <?php
                                    if (!isset($farm['is_organic_certified']) || $farm['is_organic_certified'] === '') {
                                        echo '-';
                                    } else {
                                        echo htmlspecialchars($farm['is_organic_certified'] === 'Y' ? 'Yes' : 'No');
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Water Resource</th>
                            <td><?php echo !empty($farm['water_resource']) ? htmlspecialchars($farm['water_resource']) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Farm Type</th>
                            <td><?php echo !empty($farm['farm_type']) ? htmlspecialchars($farm['farm_type']) : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>


        <section id="orders" class="content-section">
           <h2>Placed Orders</h2>
<table class="order-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Crop Name</th>
            <th>Buyer Name</th>
            <th>Phone</th>
            <th>Price per Kg</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php
        if ($orders->num_rows > 0) {
            while ($row = $orders->fetch_assoc()) {

                // Set status color class
                $statusClass = strtolower($row['order_status']);

                // Action button / status badge
                if ($row['order_status'] == 'Pending') {
                    $statusButton = "
                        <form action='update_order.php' method='POST'>
                            <input type='hidden' name='order_id' value='{$row['order_id']}'>
                            <button type='submit' class='deliver-btn'>Accept Order</button>
                        </form>
                    ";
                } else {
                    $statusButton = "<span class='status-badge delivered'>Delivered</span>";
                }

                echo "
                <tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['crop_name']}</td>
                    <td>{$row['buyer_name']}</td>
                    <td>{$row['buyer_contact_no']}</td>
                    <td>₹{$row['price_per_kg']}</td>
                    <td>{$row['order_qty']} kg</td>
                    <td>₹{$row['total_price']}</td>
                    <td><span class='status-badge {$statusClass}'>{$row['order_status']}</span></td>
                    <td>{$row['order_date']}</td>
                    <td>$statusButton</td>
                </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='10' style='text-align:center;'>No orders found</td></tr>";
        }
        ?>
    </tbody>
</table>



        </section>

    </main>

        <?php require 'content/_translate.html' ?>

    <footer>
        <p>© 2025 Farmer Crop Selling Platform D2C | Designed By VS<sup>2</sup> </p>
    </footer>

</body>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }

    function showSection(sectionId, event) {
        const sections = document.querySelectorAll('.content-section');
        const items = document.querySelectorAll('.sidebar li');

        sections.forEach(sec => sec.classList.remove('active'));
        items.forEach(item => item.classList.remove('active'));

        document.getElementById(sectionId).classList.add('active');
        event.target.classList.add('active');

        document.getElementById('sidebar').classList.remove('show');
    }

</script>

</html>