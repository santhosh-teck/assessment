<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-light bg-light">
            <div class="container px-4">
                <a class="navbar-brand" href="#">
                    <img src="https://santhosh-sanapathi-0911.vercel.app/brands/Bootstrap.svg" height="30">
                </a>
                <a class="nav-link" href="login.php">Login</a>
            </div>
        </nav>
    </header>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-12">
                <form id="registerForm" enctype="multipart/form-data" novalidate>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center mb-3">Registration Form</h3>
                            <input type="hidden" name="register">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="mobile" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" minlength="6" required>
                            </div>
                            <div class="mb-3">
                                <label>State</label>
                                <select class="form-select" name="state" id="state" required>
                                    <option value="">Select State</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Gujarat">Gujarat</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>City</label>
                                <select class="form-select" name="city" id="city" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                            <div id="alertBox" class="alert d-none"></div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.getElementById("registerForm");
        const alertBox = document.getElementById("alertBox");
        const cities = {
            Maharashtra: ["Mumbai", "Pune", "Nagpur"],
            Gujarat: ["Ahmedabad", "Surat", "Vadodara"]
        };
        document.getElementById("state").addEventListener("change", function () {
            const citySelect = document.getElementById("city");
            citySelect.innerHTML = '<option value="">Select City</option>';
            if (cities[this.value]) {
                cities[this.value].forEach(city => {
                citySelect.innerHTML += `<option value="${city}">${city}</option>`;
                });
            }
        });
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
