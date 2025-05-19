<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | Tortha POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
        }
        .hero p {
            font-size: 1.25rem;
            color: #6c757d;
        }
        .btn-primary {
            padding: 12px 30px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container hero">
        <h1 class="mb-4">Welcome to Tortha Universal Retail POS</h1>
        <p class="mb-5">Streamline your retail operations with our modern, secure, and scalable Point of Sale system.</p>
        <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        &copy; {{ date('Y') }} Tortha POS. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
