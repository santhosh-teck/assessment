<?php
session_start();
header("Content-Type: application/json");
@include 'conn.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status"=>"error","message"=>"Invalid request method"]);
    exit;
}
if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = md5($_POST['password'] ?? '');
    if (empty($email) || empty($_POST['password'])) {
        echo json_encode(["status"=>"error","message"=>"Email and password are required"]);
        exit;
    }
    $stmt = $conn->prepare("SELECT email, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_email'] = $user['email'];
            echo json_encode(["status"=>"success","message"=>"Login successful"]);
            exit;
        }
    }
    echo json_encode(["status"=>"error","message"=>"Invalid email or password"]);
    exit;
}
if (isset($_POST['register'])) {
    $name = preg_replace('/[^a-zA-Z\s]/', '', $_POST['name'] ?? '');
    if (empty($name)) {
        echo json_encode(["status"=>"error","message"=>"Invalid name"]);
        exit;
    }
    $mobile = substr(preg_replace('/\D/', '', $_POST['mobile'] ?? ''), 0, 10);
    if (strlen($mobile) !== 10) {
        echo json_encode(["status"=>"error","message"=>"Invalid mobile number"]);
        exit;
    }
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status"=>"error","message"=>"Invalid email"]);
        exit;
    }
    $password = $_POST['password'] ?? '';
    if (strlen($password) < 6) {
        echo json_encode(["status"=>"error","message"=>"Password must be at least 6 characters"]);
        exit;
    }
    $state = preg_replace('/[^a-zA-Z\s]/', '', $_POST['state'] ?? '');
    if (empty($state)) {
        echo json_encode(["status"=>"error","message"=>"Invalid state"]);
        exit;
    }
    $city = preg_replace('/[^a-zA-Z\s]/', '', $_POST['city'] ?? '');
    if (empty($city)) {
        echo json_encode(["status"=>"error","message"=>"Invalid city"]);
        exit;
    }
    $description = trim(strip_tags($_POST['description'] ?? ''));
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo json_encode(["status"=>"error","message"=>"Email already exists"]);
        exit;
    }
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }
    $md5Password = md5($password);
    $stmt = $conn->query("INSERT INTO `users`(`id`, `name`, `email`, `mobile`, `image`, `password`, `state`, `city`, `description`) VALUES (NULL,'$name','$email','$mobile','$imageName','$md5Password','$state','$city','$description')");
    if ($stmt == true) {
        echo json_encode(["status"=>"success","message"=>"Registration successful. You can login now."]);
        exit;
    }
    echo json_encode(["status"=>"error","message"=>"Registration failed"]);
    exit;
}
echo json_encode(["status"=>"error","message"=>"Invalid request"]);
exit;
