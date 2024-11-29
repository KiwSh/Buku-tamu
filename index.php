<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Halaman Login | Buku Tamu DISKOMINFO</title>

    <link rel="icon" href="assets/img/logo-diskominfo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Add reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
    /* Your existing CSS styles remain unchanged */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Nunito', sans-serif;
        background: rgb(0,0,0);
        background: radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(148,187,233,1) 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        width: 900px;
        height: 500px;
        background-color: white;
        display: flex;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        flex-direction: row;
    }

    .login-container::before {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #007BFF 0%, #007BFF 50%, #333 50%, #333 100%);
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
        z-index: 1;
    }

    .login-form {
        width: 50%;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.9);
    }

    .login-form h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 25px;
        text-align: center;
        border-bottom: 2px solid black;
        padding-bottom: 10px;
        border-radius: 5%;
    }

    .login-form .form-group {
        margin-bottom: 20px;
    }

    .login-form input {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .login-form button {
        padding: 12px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        margin-top: 20px;
    }

    .login-form button:hover {
        background-color: #0056b3;
    }

    .login-form p {
        font-size: 14px;
        text-align: center;
        margin-top: 15px;
    }

    .login-form p a {
        color: #007BFF;
        text-decoration: none;
    }

    .login-form p a:hover {
        text-decoration: underline;
    }

    /* Welcome Section */
    .welcome-section {
        width: 50%;
        background-image: url('assets/img/megamendung-keren.jpeg');
        background-size: cover;
        background-position: center;
        color: white;
        text-shadow: 4px 4px 4px rgba(0, 0, 0, 50.0);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px 40px;
        position: relative;
        z-index: 2;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1;
    }

    .welcome-section h2,
    .welcome-section p {
        z-index: 2;
        color: white;
    }

    .welcome-section img {
        width: 350px;
        margin-bottom: 20px;
        filter: brightness(1.5);
    }

    .welcome-text {
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-block;
        text-align: center;
        font-size: 18px;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 900px) {
        .login-container {
            flex-direction: column;
            width: 100%;
            height: auto;
        }

        .login-form,
        .welcome-section {
            width: 100%;
            padding: 40px 20px;
        }

        .login-container::before {
            clip-path: none;
            background: linear-gradient(180deg, #007BFF 0%, #007BFF 50%, #333 50%, #333 100%);
        }

        .welcome-section img {
            width: 200px;
        }

        .login-form h1 {
            font-size: 24px;
        }

        .login-form input,
        .login-form button {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .login-form h1 {
            font-size: 20px;
        }

        .login-form input,
        .login-form button {
            font-size: 12px;
        }

        .login-container {
            margin-top: 20px;
        }
    }

    </style>

</head>

<body>
    <div class="login-container">
        <!-- Login Form -->
        <div class="login-form">
            <h1>Login</h1>
            <form action="cek_login.php" method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <!-- Google reCAPTCHA -->
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LeOX4wqAAAAABFSqoDyUogCRc1sbd1LILexmrr5"></div>
                </div>
                
                <button type="submit" id="loginButton">
                    <span class="button-text">Login</span>
                    <div class="loader" style="display: none;"></div>
                </button>
                <br>
                <br>
                <p>2024 Â© BukTa Diskominfo Kab. Bogor | All Rights Reserved.</p>
            </form>
        </div>
        <!-- Welcome Section -->
        <div class="welcome-section">
            <img src="assets/img/diskominfo_kab_bogor-removebg-preview.png" alt="Logo">
            <h2>WELCOME BACK!</h2>
            <p class="welcome-text">
                Selamat datang kembali di sistem Buku Tamu DISKOMINFO. Silakan login untuk melanjutkan.
            </p>
        </div>
    </div>

    <!-- Script Loader -->
    <script>
        document.getElementById("loginButton").addEventListener("click", function(event) {
            const button = event.target;
            const loader = button.querySelector(".loader");
            const buttonText = button.querySelector(".button-text");

            // Tampilkan loader dan sembunyikan teks
            loader.style.display = "inline-block";
            buttonText.style.display = "none";
        });
    </script>

    <!-- Notifikasi SweetAlert -->
    <script>
    <?php
    session_start();
    // Tampilkan pesan jika login sukses
    if (isset($_SESSION['login_success'])) {
        echo "Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            text: '{$_SESSION['login_success']}',
            confirmButtonText: 'Lanjutkan'
        }).then(() => {
            window.location.href = 'admin.php'; // Redirect ke halaman dashboard
        });";
        unset($_SESSION['login_success']); // Hapus session setelah digunakan
    }

    // Tampilkan pesan jika login gagal
    if (isset($_SESSION['login_error'])) {
        echo "Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{$_SESSION['login_error']}',
            confirmButtonText: 'Coba Lagi'
        });";
        unset($_SESSION['login_error']); // Hapus session setelah digunakan
    }
    ?>
</script>

</body>

</html>
