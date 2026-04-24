<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feest op Tafel | Luxe Desserts & Workshops</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #ff8b6b; /* Soft Orange */
            --primary-dark: #e87a5a;
            --secondary: #ffccd5; /* Light Pink */
            --accent: #ff4d6d; /* Vibrant Pink */
            --dark: #2d3436;
            --light: #ffffff;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 139, 107, 0.2);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2)), url('/images/hero.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--accent));
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 10px 20px rgba(255, 77, 109, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            padding: 10px 28px;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .text-gradient {
            background: linear-gradient(45deg, var(--accent), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 1s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="glass-nav py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-gradient tracking-wider">FEEST OP TAFEL</a>
            <div class="flex space-x-6 items-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-outline py-2 px-6 text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-dark hover:text-accent transition-colors text-sm font-semibold">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary py-2 px-6 text-sm">Registreer</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade opacity-0 text-dark">
                Vier het leven met <br><span class="text-gradient">iets lekkers op tafel</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto text-gray-700 animate-fade delay-1 opacity-0">
                De mooiste desserts en leukste workshops voor een onvergetelijk feest.
            </p>
            <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6 animate-fade delay-2 opacity-0">
                <a href="{{ route('deserts.index') }}" class="btn-primary shadow-lg">Bekijk ons aanbod</a>
                @guest
                    <a href="{{ route('register') }}" class="btn-outline">Account aanmaken</a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-white border-t border-pink-100">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0 text-gradient font-bold text-xl tracking-wider">
                FEEST OP TAFEL
            </div>
            <div class="flex space-x-6 text-gray-500 text-sm">
                <a href="#" class="hover:text-accent transition-colors">Algemene Voorwaarden</a>
                <a href="#" class="hover:text-accent transition-colors">Privacy Beleid</a>
                <a href="#" class="hover:text-accent transition-colors">Contact</a>
            </div>
            <div class="mt-6 md:mt-0 text-gray-400 text-xs">
                &copy; {{ date('Y') }} Feest op Tafel. Alle rechten voorbehouden.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.animate-fade').forEach(el => {
                el.style.opacity = "1";
            });
        });
    </script>
</body>
</html>
