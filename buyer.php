<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
        header("location: index.php");
        exit;
    }
            
    include 'db.php';
    $username = $_SESSION['username'];
    $sql = "SELECT buyer_name FROM buyer WHERE buyer_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $buyer_name = $row['buyer_name'];
    } else {
        $buyer_name = "Unknown buyer";
    }

    $sql = "SELECT buyer_id FROM buyer WHERE buyer_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $buyer_id = $row['buyer_id'];
    } else {
        $buyer_id =0;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['multi_order'])) {

        if (!isset($_POST['selected_crops'])) {
            echo "<script>alert('Please select at least one crop!');window.location.href='buyer.php'</script>";
            return;
        }

        $selected = $_POST['selected_crops'];  
        $quantities = $_POST['qty'];            

        foreach ($selected as $crop_id) {

            if (!isset($quantities[$crop_id]) || floatval($quantities[$crop_id]) <= 0) {
                continue;
            }

            $qty = floatval($quantities[$crop_id]);

            $sql = "SELECT crop_price_per_kg, crop_quantity_in_kg 
                    FROM crop WHERE crop_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $crop_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $crop = $res->fetch_assoc();

            if (!$crop) continue;

            $price = $crop['crop_price_per_kg'];
            $available_qty = $crop['crop_quantity_in_kg'];

            if ($qty > $available_qty) {
                echo "<script>alert('Not enough stock for crop ID: $crop_id');</script>";
                continue;
            }

            $total_price = $qty * $price;

            $orderQuery = "INSERT INTO orders 
                (buyer_id, crop_id, order_qty, price_per_kg, total_price, order_status, order_date)
                VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";

            $stmt2 = $conn->prepare($orderQuery);
            $stmt2->bind_param("iiddd", $buyer_id, $crop_id, $qty, $price, $total_price);


            if ($stmt2->execute()) {

                $new_qty = $available_qty - $qty;
                $updateStock = "UPDATE crop SET crop_quantity_in_kg=? WHERE crop_id=?";
                $s2 = $conn->prepare($updateStock);
                $s2->bind_param("di", $new_qty, $crop_id);
                $s2->execute();
            }
        }

        echo "<script>alert('Orders Placed Successfully!');</script>";
    }
    $sql_orders = "SELECT COUNT(*) AS total_orders
                FROM orders 
                WHERE buyer_id = ?";

    $stmt = $conn->prepare($sql_orders);
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $total_orders = $row['total_orders'] ?? 0;

    $sql_pending = "SELECT COUNT(*) AS pending_count
                    FROM orders
                    WHERE buyer_id = ? 
                    AND order_status = 'Pending'";

    $stmt = $conn->prepare($sql_pending);
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    $pending_deliveries = $row['pending_count'] ?? 0;

    $sql_spent = "SELECT SUM(total_price) AS spent 
                FROM orders 
                WHERE buyer_id = ?";

    $stmt = $conn->prepare($sql_spent);
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $total_spent = $row['spent'] ?? 0;

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
        .crop-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .crop-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        .crop-card img{
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
        }
        .order-input{
            width: 100%;
            padding: 7px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .order-btn{
            width: 100%;
            background:#2d6a4f;
            color:white;
            padding:10px;
            border-radius:6px;
            border:none;
            cursor:pointer;
        }
        .order-btn:hover{
            background:#1b4332;
        }
        .popup-container {
            display:none;
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center; 
            align-items: center;
            z-index: 9999;
        }

        .popup {
            width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .pay-btn {
            padding: 10px 15px;
            background: green; 
            color: white; border: none; 
            border-radius: 5px; cursor: pointer;
        }

        .cancel-btn {
            padding: 10px 15px;
            background: red; 
            color: white; border: none; 
            border-radius: 5px; cursor: pointer;
        }

        #orderSummary table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        #orderSummary th, #orderSummary td {
            border: 1px solid #ddd;
            padding: 8px;
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
            <h3 class="farmer-name"> <?php echo htmlspecialchars($buyer_name); ?></h3>
            <h3 class="farmer-name"> ID : <?php echo htmlspecialchars($buyer_id); ?></h3>
        </div>
        <ul>
            <li class="active" onclick="showSection('dashboard', event)"><i class="fa-solid fa-house"></i> Dashboard</li>
            <li onclick="showSection('orders', event)"><i class="fa-solid fa-list-check"></i> Orders</li>
            <li onclick="showSection('profile', event)"><i class="fa-solid fa-address-card"></i> Profile</li>
        </ul>
    </aside>

    <main class="main-content">


        <section id="dashboard" class="content-section active">
           <h2>Welcome, <?php echo htmlspecialchars($buyer_name); ?></h2>
            <div class="stats-cards">
                <div class="card">
                    <h3><i class="fa-solid fa-cart-arrow-down"></i> Orders Placed</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="card">
                    <h3><i class="fa-solid fa-indian-rupee-sign"></i> Total Spent</h3>
                    <p>₹ <?php echo number_format($total_spent,2); ?></p>
                </div>
                <div class="card">
                    <h3><i class="fa-solid fa-clock"></i> Pending Deliveries</h3>
                    <p><?php echo $pending_deliveries; ?></p>
                </div>
            </div>
            <h2>Select crops to place order</h2>
            <form method="POST" id="orderForm">
                <input type="hidden" name="multi_order" value="1">

                <div class="crop-grid">

                    <?php
                    $sql = "SELECT crop.crop_id, crop.crop_name, crop.crop_price_per_kg, crop.crop_quantity_in_kg, farmer.farmer_name 
                            FROM crop
                            INNER JOIN farmer ON crop.farmer_id = farmer.farmer_id
                            WHERE crop.crop_status='available'";

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        
                        <div class="crop-card">
                            <h3><?php echo $row['crop_name']; ?></h3>
                            <p><strong>Price:</strong> ₹<?php echo $row['crop_price_per_kg']; ?>/KG</p>
                            <p><strong>Available:</strong> <?php echo $row['crop_quantity_in_kg']; ?> KG</p>
                            <p><strong>Farmer:</strong> <?php echo $row['farmer_name']; ?></p>

                            <!-- Checkbox to select this crop -->
                            <input type="checkbox" 
                                name="selected_crops[]" 
                                value="<?php echo $row['crop_id']; ?>">
                            <input type="hidden" id="price_<?= $row['crop_id'] ?>" value="<?= $row['crop_price_per_kg'] ?>">
                            <input type="hidden" id="name_<?= $row['crop_id'] ?>" value="<?= $row['crop_name'] ?>">

                            <!-- Quantity input -->
                            <input type="number"
                                name="qty[<?php echo $row['crop_id']; ?>]"
                                class="order-input"
                                min="1"
                                max="<?php echo $row['crop_quantity_in_kg']; ?>"
                                step="0.5"
                                placeholder="Enter KG">
                        </div>

                    <?php } ?>
                </div>

                <button type="button" onclick="showPaymentWindow()">Place Order</button>

            </form>

        </section>

        <section id="orders" class="content-section">
            <h2>Your Orders</h2>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Crop</th>
                        <th>Qty (KG)</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sql = "SELECT orders.*, crop.crop_name 
                            FROM orders 
                            JOIN crop ON orders.crop_id = crop.crop_id
                            WHERE orders.buyer_id = $buyer_id
                            ORDER BY order_date DESC";

                    $result = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['crop_name']}</td>
                                <td>{$row['order_qty']}</td>
                                <td>₹{$row['total_price']}</td>
                                <td>{$row['order_status']}</td>
                                <td>{$row['order_date']}</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <?php
            include 'db.php';
            
            $user = $_SESSION['username'];
            $sql = "SELECT `buyer_id`, `buyer_name`, `buyer_age`, `buyer_gender`, `buyer_address`, `city`, `state`, `pin_code`, `buyer_aadhar_no`, `buyer_pan_no`, `buyer_contact_no`, `buyer_mail_id` FROM `buyer` WHERE `buyer_username` = '$user'";
            $result = mysqli_query($conn, $sql);
            $farmer = mysqli_fetch_assoc($result);
        ?>
        <section id="profile" class="content-section">
            <div class="profile-info-wrapper">
                <div class="profile-card">
                    <h2 class="profile-heading">Buyer's Personal Information</h2>
                    <table class="profile-table">
                        <tr>
                            <th>Buyer ID</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_id']); ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_age']); ?></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_gender']); ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_address']); ?></td>
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
                            <td><?php echo htmlspecialchars($farmer['buyer_aadhar_no']); ?></td>
                        </tr>
                        <tr>
                            <th>PAN No</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_pan_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Contact No</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_contact_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Email ID</th>
                            <td><?php echo htmlspecialchars($farmer['buyer_mail_id']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <?php require 'content/_translate.html' ?>

    <footer>
        <p>© 2025 Farmer Crop Selling Platform D2C | Designed By VS<sup>2</sup> </p>
    </footer>

    <div id="paymentPopup" class="popup-container">
        <div class="popup">
            <h2>Order Payment</h2>
            <div id="orderSummary"></div>

            <h3 id="grandTotal"></h3>

            <button onclick="completePayment()" class="pay-btn">Make Payment</button>
            <button onclick="closePopup()" class="cancel-btn">Cancel</button>
        </div>
    </div>
</body>

<script>
    function showSection(sectionId, event) {
        const sections = document.querySelectorAll('.content-section');
        const items = document.querySelectorAll('.sidebar li');

        sections.forEach(sec => sec.classList.remove('active'));
        items.forEach(item => item.classList.remove('active'));

        document.getElementById(sectionId).classList.add('active');

        // FIX: event.currentTarget is always the <li>
        if(event && event.currentTarget){
            event.currentTarget.classList.add('active');
        }

        document.getElementById('sidebar').classList.remove('show');
    }
</script>


<script>
    function showPaymentWindow() {
        let selected = document.querySelectorAll("input[name='selected_crops[]']:checked");

        if (selected.length === 0) {
            alert("Please select at least one crop!");
            return;
        }

        let summaryHTML = `
            <table>
                <tr>
                    <th>Crop</th>
                    <th>Qty (kg)</th>
                    <th>Price/kg</th>
                    <th>Total</th>
                </tr>
        `;

        let grandTotal = 0;

        selected.forEach(chk => {
            let cid = chk.value;
            let qty = document.querySelector(`input[name='qty[${cid}]']`).value;
            let price = document.querySelector(`#price_${cid}`).value;
            let cropName = document.querySelector(`#name_${cid}`).value;

            if (qty <= 0) return;

            let total = qty * price;
            grandTotal += total;

            summaryHTML += `
                <tr>
                    <td>${cropName}</td>
                    <td>${qty}</td>
                    <td>₹${price}</td>
                    <td>₹${total}</td>
                </tr>
            `;
        });

        summaryHTML += `</table>`;

        document.getElementById("orderSummary").innerHTML = summaryHTML;
        document.getElementById("grandTotal").innerHTML = "Grand Total: ₹" + grandTotal;

        document.getElementById("paymentPopup").style.display = "flex";
    }
    function completePayment() {
        document.getElementById("paymentPopup").style.display = "none";
        document.getElementById("orderForm").submit();
    }

    function closePopup() {
        document.getElementById("paymentPopup").style.display = "none";
    }
</script>

</html>

<!-- CREATE TABLE orders ( order_id INT AUTO_INCREMENT PRIMARY KEY, buyer_id INT, crop_id INT, order_qty FLOAT, price_per_kg FLOAT, total_price FLOAT, order_status VARCHAR(20) DEFAULT 'pending', order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ); -->