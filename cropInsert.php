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