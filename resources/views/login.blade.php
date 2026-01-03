<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Secure Access</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0b6c5f; /* Teal green from image background */
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            border-bottom: 6px solid #c62828; /* Crimson red accent */
            text-align: center;
        }

        .login-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #0b6c5f;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .login-card h2 {
            color: #0b6c5f;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #0b6c5f;
            box-shadow: 0 0 0 0.25 margin rgba(11, 108, 95, 0.25);
        }

        .btn-login {
            background-color: #0b6c5f;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #085248;
            color: white;
            transform: translateY(-2px);
        }

        .footer-text {
            font-size: 0.85rem;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="YOUR_IMAGE_PATH_HERE" alt="Admin Avatar">
    
    <h2>Admin Login</h2>
    
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-login">Login to Dashboard</button>
    </form>

    <div class="footer-text">
        &copy; 2025 All Rights Reserved.
    </div>
</div>

</body>
</html>