<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "donation_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize donor details
    $first_name = htmlspecialchars($_POST['first-name']);
    $last_name = htmlspecialchars($_POST['last-name']);
    $phone_number = htmlspecialchars($_POST['phone-no']);
    $email = htmlspecialchars($_POST['email']);
    $street = htmlspecialchars($_POST['street']);
    $landmark = htmlspecialchars($_POST['landmark']);
    $city = htmlspecialchars($_POST['city']);
    $state_name = htmlspecialchars($_POST['state']); // Ensure column name matches DB
    $pincode = htmlspecialchars($_POST['pincode']);

    // Insert donor details into 'donors' table
    $stmt = $conn->prepare("INSERT INTO donor(first_name, last_name, phone_number, email, street, landmark, city, state_name, pincode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first_name, $last_name, $phone_number, $email, $street, $landmark, $city, $state_name, $pincode);

    if ($stmt->execute()) {
        $donor_id = $stmt->insert_id; // Get last inserted donor ID
    } else {
        die("Error inserting donor: " . $stmt->error);
    }
    $stmt->close();

    // Ensure both donationType and donationCount exist and are arrays
    if (
        !empty($_POST['donationType']) && is_array($_POST['donationType']) &&
        !empty($_POST['donationCount']) && is_array($_POST['donationCount'])
    ) {

        $donation_types = $_POST['donationType']; // Array of donation types
        $donation_counts = $_POST['donationCount']; // Array of donation counts

        if (count($donation_types) === count($donation_counts)) {
            // Insert each donation item into 'donations' table
            $stmt = $conn->prepare("INSERT INTO donations (donor_id, donation_type, donation_count) VALUES (?, ?, ?)");

            for ($i = 0; $i < count($donation_types); $i++) {
                $donation_type = htmlspecialchars($donation_types[$i]);
                $donation_count = intval($donation_counts[$i]); // Convert count to integer

                $stmt->bind_param("isi", $donor_id, $donation_type, $donation_count);
                $stmt->execute();
            }
            $stmt->close();
        } else {
            die("Error: Mismatch in donation types and counts.");
        }
    } else {
        die("Error: No donations submitted.");
    }

    echo "<script>
    alert('Thank you for your donation! We will reach out to you soon.');
</script>";
    $conn->close();
}
?>