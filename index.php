<?php
@include 'conn.php';
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}else{
    $email = $_SESSION['user_email'];
    $sql = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
    if($sql->num_rows > 0){
        $user = $sql->fetch_assoc();
        $result = $conn->query("SELECT id, name, email, mobile, image FROM users ORDER BY id DESC");
    }else{
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4">
            <a class="navbar-brand" href="#">
            <img src="https://santhosh-sanapathi-0911.vercel.app/brands/Bootstrap.svg" alt="Logo" height="30">
            </a>
            <h2>Welcome <?php echo $user["name"]?></h2>
            <a class="nav-link" href="?logout">Logout</a>
        </div>
        </nav>
    </header>
    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>List in Admin Panel</h4>
            <button onclick="downloadPDF()" class="btn btn-outline-primary btn-sm">
                Download (PDF)
            </button>
        </div>
        <table id="userTable" class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>S.no</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td>
                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>">
                            <?= htmlspecialchars($row['email']) ?>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <img src="uploads/<?= $row['image'] ?>" width="50">
                        <?php } else { ?>
                            
                        <?php } ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $row['id'] ?>"
                        onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('userTable');
            const options = {
                margin: 0.5,
                filename: 'users-list.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(options).from(element).save();
        }
    </script>
</body>
</html>
