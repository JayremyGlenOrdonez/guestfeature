<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- UPDATED: Title configuration --}}
    <title>{{ $title ?? config('app.name', 'My Online Shop') }}</title> {{-- Changed default to 'My Online Shop' --}}

    {{-- UPDATED: Favicon path. Assume 'favicon.ico' is in your public folder or 'images/favicon.png' --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> {{-- Example: public/favicon.ico --}}
    {{-- OR if you use a PNG: <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png"> --}}

    {{-- Basic CSS para sa header at body, pwede mong palitan to ng CSS framework mo (e.g., Bootstrap, Tailwind) --}}
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background-color: #f8f8f8;
            /* Light gray background */
        }

        .header {
            background-color: #ffffff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header .logo img {
            height: 40px;
            /* Adjust as needed */
        }

        .header .nav-links {
            display: flex;
            gap: 30px;
        }

        .header .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
        }

        .header .nav-links a:hover {
            color: #5b21b6;
            /* Purple hover for links */
        }

        .header .icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header .icons a {
            color: #333;
            text-decoration: none;
            font-size: 20px;
            /* Icon size */
            position: relative;
        }

        .header .icons .cart-count {
            background-color: #5b21b6;
            /* Purple background for cart count */
            color: white;
            font-size: 12px;
            border-radius: 50%;
            padding: 3px 7px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        /* --- Start of Search Bar CSS --- */
        .header .search-container {
            position: relative;
            display: flex;
            align-items: center;
            margin-right: 10px;
            /* Space between search and cart icon */
        }

        .header .search-container #search-icon-toggle {
            cursor: pointer;
            z-index: 10;
            /* Ensure icon is clickable */
        }

        .header .search-container #search-form {
            display: flex;
            overflow: hidden;
            max-width: 0;
            /* Hidden by default */
            transition: max-width 0.3s ease-out;
            /* Animation */
            margin-left: -5px;
            /* Adjust if needed to align closely with icon */
            position: absolute;
            /* Position relative to .search-container */
            right: 30px;
            /* Position to the right of the search icon */
            white-space: nowrap;
            /* Prevent wrapping of input and button */
        }

        .header .search-container #search-form.active {
            max-width: 250px;
            /* Adjust this value for desired opened width */
        }

        .header .search-container #search-form input[type="text"] {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.9em;
            outline: none;
            flex-shrink: 0;
            width: 180px;
            /* Fixed width for input field */
        }

        .header .search-container #search-form button[type="submit"] {
            background-color: #5b21b6;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
            flex-shrink: 0;
        }

        /* --- End of Search Bar CSS --- */


        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .hero-section {
            text-align: center;
            padding: 80px 20px;
            background-color: #e0e0f0;
            /* Light purple-ish background */
            margin-bottom: 40px;
        }

        .hero-section h1 {
            font-size: 3em;
            color: #5b21b6;
            /* Purple */
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2em;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .section-heading {
            text-align: center;
            font-size: 2.5em;
            color: #5b21b6;
            margin-bottom: 30px;
            margin-top: 50px;
        }

        .product-grid,
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .product-card,
        .category-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            padding: 20px;
            transition: transform 0.2s;
        }

        .product-card:hover,
        .category-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            max-width: 100%;
            height: 200px;
            /* Fixed height for product images */
            object-fit: contain;
            /* Ensures the image fits without cropping */
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 1.1em;
            color: #5b21b6;
            font-weight: bold;
        }

        .category-card h3 {
            font-size: 1.4em;
            color: #333;
        }

        .btn-more {
            display: block;
            width: fit-content;
            margin: 40px auto;
            padding: 15px 30px;
            background-color: #5b21b6;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.1em;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-more:hover {
            background-color: #4a199a;
        }

        .btn-more .arrow {
            font-size: 1.2em;
        }

        /* Footer (Basic) */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 60px;
        }
    </style>
    @stack('styles') {{-- Para sa page-specific CSS --}}
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="{{ route('clientHome') }}">
                {{-- UPDATED: Shop Logo to be dynamic from database, with fallback --}}
                <img src="{{ asset('storage/' . ($shop->logo_path ?? 'images/default-logo.png')) }}"
                    alt="{{ $shop->name_shop ?? 'Shop Logo' }}">
                {{-- Make sure 'images/default-logo.png' exists in your public folder --}}
                {{-- AND ensure 'php artisan storage:link' has been run --}}
            </a>
        </div>
        <nav class="nav-links">
            <a href="{{ route('clientHome') }}">Home</a>
            <a href="{{ route('clientProducts') }}">Products</a>
            <a href="{{ route('clientCategory') }}">Category</a>
            <a href="{{ route('clientAbout') }}">About</a>
            {{-- REMOVED: Check Order link as per your request --}}
            {{-- <a href="{{ route('clientCheckOrder') }}">Check Order</a> --}}
        </nav>
        <div class="icons">
            {{-- Search Icon and Inline Search Form --}}
            <div class="search-container">
                <a href="#" id="search-icon-toggle"><i class="fas fa-search"></i></a>
                <form action="{{ route('clientSearch') }}" method="POST" id="search-form">
                    @csrf
                    <input type="text" name="product" placeholder="Search..." required>
                    <button type="submit">Go</button>
                </form>
            </div>

            <a href="{{ route('clientCart') }}">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cart-count">
                    {{ session('cart') ? count((array) session('cart')) : 0 }}
                </span>
            </a>
        </div>
    </header>

    <main>
        @yield('content') {{-- Dito ilalagay ang content ng bawat page --}}
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} {{ $shop->name_shop ?? 'Your Shop Name' }}. All rights reserved.</p>
        <p>Contact us: {{ $shop->phone ?? 'N/A' }} | {{ $shop->address ?? 'N/A' }}</p>
    </footer>

    {{-- Optional: Import Font Awesome for icons (if you want the search and cart icons) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" integrity="sha512-..."
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Inline Search Bar Toggle Logic
        document.getElementById('search-icon-toggle').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior
            const searchForm = document.getElementById('search-form');

            // Toggle 'active' class
            searchForm.classList.toggle('active');

            // Focus on input if showing, clear if hiding
            if (searchForm.classList.contains('active')) {
                searchForm.querySelector('input[name="product"]').focus();
            } else {
                searchForm.querySelector('input[name="product"]').value = '';
            }
        });

        // Close search bar if clicked outside (optional, but good UX)
        document.addEventListener('click', function(event) {
            const searchContainer = document.querySelector('.search-container');
            const searchForm = document.getElementById('search-form');

            // If the click is outside the search container AND the form is visible
            if (!searchContainer.contains(event.target) && searchForm.classList.contains('active')) {
                searchForm.classList.remove('active');
                searchForm.querySelector('input[name="product"]').value = '';
            }
        });

        // Ensure updateCartCount is globally accessible
        function updateCartCount(count) {
            document.getElementById('cart-count').innerText = count;
        }
    </script>
    @stack('scripts') {{-- Para sa page-specific JavaScript --}}
</body>

</html>
