<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4">
        <a class="navbar-brand" href="#">
            <img src="https://santhosh-sanapathi-0911.vercel.app/brands/Bootstrap.svg" alt="Logo" height="30">
        </a>
        <a class="nav-link" href="register.php">Register</a>
        </div>
    </nav>
    </header>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5">
            <form id="loginForm" novalidate>
                <div class="card">
                <div class="card-body p-3">
                    <h3 class="text-center">Welcome to SKS</h3>
                    <div id="alertBox" class="alert d-none mt-2"></div>
                    <div class="mt-3">
                        <label class="form-label">Enter Your Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    <div class="mt-3">
                        <input type="hidden" name="login">
                        <label class="form-label">Enter Your Password</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                    <div id="alertBox" class="alert d-none"></div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.getElementById("loginForm");
        const alertBox = document.getElementById("alertBox");
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            if (!form.checkValidity()) {
                form.classList.add("was-validated");
                return;
            }
            alertBox.classList.add("d-none");
            const formData = new FormData(form);

            try {
                const response = await fetch("auth.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                alertBox.classList.remove("d-none");

                if (result.status === "success") {
                    alertBox.className = "alert alert-success";
                    alertBox.innerText = result.message;

                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 1000);

                } else {
                    alertBox.className = "alert alert-danger";
                    alertBox.innerText = result.message;
                }

            } catch (error) {
                alertBox.className = "alert alert-danger";
                alertBox.classList.remove("d-none");
                alertBox.innerText = "Something went wrong. Please try again.";
            }
        });
    </script>
</body>
</html>
