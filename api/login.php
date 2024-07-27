<?php
session_start();
include("connection.php");

$mobile = $_POST['mob'];
$pass = $_POST['pass'];
$role = $_POST['role'];

// Prepare and bind parameters
$stmt = $connect->prepare("SELECT * FROM user WHERE mobile = ? AND password = ? AND role = ?");
$stmt->bind_param("sss", $mobile, $pass, $role);
$stmt->execute();
$check = $stmt->get_result();

if ($check->num_rows > 0) {
    $getGroups = $connect->prepare("SELECT name, photo, votes, id FROM user WHERE role = 2");
    $getGroups->execute();
    $groupsResult = $getGroups->get_result();
    if ($groupsResult->num_rows > 0) {
        $groups = $groupsResult->fetch_all(MYSQLI_ASSOC);
        $_SESSION['groups'] = $groups;
    }
    $data = $check->fetch_array(MYSQLI_ASSOC);
    $_SESSION['id'] = $data['id'];
    $_SESSION['status'] = $data['status'];
    $_SESSION['data'] = $data;

    echo '<script>
            window.location = "../routes/dashboard.php";
          </script>';
} else {
    echo '<script>
            alert("Invalid credentials!");
            window.location = "../";
          </script>';
}

// Close the statement and connection
$stmt->close();
$connect->close();
?>
