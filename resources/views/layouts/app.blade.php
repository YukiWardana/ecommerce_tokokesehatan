<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medical Tools Shop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 4px;
            padding: 8px 16px !important;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 8px;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            margin-top: auto;
        }
        main {
            min-height: calc(100vh - 200px);
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-heart-pulse"></i> Medical Tools Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guestbook.index') }}">Guestbook</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Admin
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isSeller())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('seller.dashboard') }}">
                                    <i class="bi bi-shop"></i> My Shop
                                </a>
                            </li>
                        @endif
                        @if(!auth()->user()->isAdmin() && !auth()->user()->isSeller())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart"></i> Cart
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('orders.index') }}">Orders</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(auth()->user()->isSeller())
                                    <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}"><i class="bi bi-shop"></i> My Shop</a></li>
                                    <li><a class="dropdown-item" href="{{ route('seller.products') }}"><i class="bi bi-box-seam"></i> My Products</a></li>
                                    <li><a class="dropdown-item" href="{{ route('seller.orders') }}"><i class="bi bi-receipt"></i> My Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('seller.settings') }}"><i class="bi bi-gear"></i> Shop Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Medical Tools Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @auth
    <script>
        // Auto-logout after 30 seconds of inactivity
        let inactivityTime = function () {
            let time;
            let timeout = 30000; // 30 seconds in milliseconds
            let warningTime = 20000; // Show warning at 20 seconds
            
            // Reset timer on user activity
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onscroll = resetTimer;
            document.onclick = resetTimer;
            document.ontouchstart = resetTimer;

            function showWarning() {
                // Show warning modal
                let warningDiv = document.createElement('div');
                warningDiv.id = 'inactivity-warning';
                warningDiv.className = 'alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3';
                warningDiv.style.zIndex = '9999';
                warningDiv.innerHTML = `
                    <strong>⚠️ Inactivity Warning!</strong><br>
                    You will be logged out in 10 seconds due to inactivity.
                    <button class="btn btn-sm btn-primary ms-2" onclick="location.reload()">Stay Logged In</button>
                `;
                document.body.appendChild(warningDiv);
            }

            function logout() {
                // Remove warning if exists
                let warning = document.getElementById('inactivity-warning');
                if (warning) warning.remove();

                // Show logout message
                let logoutDiv = document.createElement('div');
                logoutDiv.className = 'alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3';
                logoutDiv.style.zIndex = '9999';
                logoutDiv.innerHTML = '<strong>🔒 Session Expired!</strong><br>You have been logged out due to inactivity.';
                document.body.appendChild(logoutDiv);

                // Submit logout form
                setTimeout(function() {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("logout") }}';
                    
                    let csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }, 1000);
            }

            function resetTimer() {
                clearTimeout(time);
                
                // Remove warning if exists
                let warning = document.getElementById('inactivity-warning');
                if (warning) warning.remove();
                
                // Set warning timer (20 seconds)
                setTimeout(showWarning, warningTime);
                
                // Set logout timer (30 seconds)
                time = setTimeout(logout, timeout);
            }
        };

        // Initialize inactivity timer
        inactivityTime();
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>
