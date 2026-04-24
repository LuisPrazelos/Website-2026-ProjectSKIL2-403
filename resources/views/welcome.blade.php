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
            --primary: #c5a059;
            --primary-dark: #a68545;
            --dark: #0f1115;
            --darker: #08090c;
            --light: #f8f9fa;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--darker);
            color: var(--light);
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('/images/hero.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-nav {
            background: rgba(15, 17, 21, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--darker);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 0 20px rgba(197, 160, 89, 0.4);
            transform: scale(1.05);
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
            color: var(--darker);
        }

        .text-gradient {
            background: linear-gradient(45deg, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-padding {
            padding: 100px 0;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
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
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="glass-nav py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-gradient tracking-wider">FEEST OP TAFEL</a>
            <div class="hidden md:flex space-x-8 items-center">
                <a href="#about" class="hover:text-primary transition-colors">Over Ons</a>
                <a href="{{ route('deserts.index') }}" class="hover:text-primary transition-colors">Desserts</a>
                <a href="{{ route('workshops.index') }}" class="hover:text-primary transition-colors">Workshops</a>
                <a href="{{ route('event.request') }}" class="hover:text-primary transition-colors">Evenementen</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-outline py-2 px-6 text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary py-2 px-6 text-sm">Registreer</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade opacity-0">
                Maak van elke maaltijd <br><span class="text-gradient">een onvergetelijk feest</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto text-gray-300 animate-fade delay-1 opacity-0">
                Ontdek onze ambachtelijke desserts, creatieve workshops en complete arrangementen voor uw speciale gelegenheid.
            </p>
            <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6 animate-fade delay-2 opacity-0">
                <a href="{{ route('deserts.index') }}" class="btn-primary">Bekijk ons aanbod</a>
                <a href="{{ route('event.request') }}" class="btn-outline">Aanvraag indienen</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding bg-dark">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Onze Diensten</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Desserts -->
                <div class="glass-card p-8 text-center animate-fade opacity-0">
                    <div class="feature-icon">🍰</div>
                    <h3 class="text-2xl font-bold mb-4">Luxe Desserts</h3>
                    <p class="text-gray-400 mb-6">Handgemaakte creaties van de hoogste kwaliteit, perfect als afsluiting van uw diner.</p>
                    <a href="{{ route('deserts.index') }}" class="text-primary font-semibold hover:underline">Ontdek meer &rarr;</a>
                </div>

                <!-- Workshops -->
                <div class="glass-card p-8 text-center animate-fade delay-1 opacity-0">
                    <div class="feature-icon">👨‍🍳</div>
                    <h3 class="text-2xl font-bold mb-4">Creatieve Workshops</h3>
                    <p class="text-gray-400 mb-6">Leer zelf de fijne kneepjes van het vak tijdens onze gezellige en leerzame sessies.</p>
                    <a href="{{ route('workshops.index') }}" class="text-primary font-semibold hover:underline">Bekijk datums &rarr;</a>
                </div>

                <!-- Events -->
                <div class="glass-card p-8 text-center animate-fade delay-2 opacity-0">
                    <div class="feature-icon">✨</div>
                    <h3 class="text-2xl font-bold mb-4">Evenementen</h3>
                    <p class="text-gray-400 mb-6">Volledige ontzorging voor uw feest of evenement. Wij regelen de culinaire magie.</p>
                    <a href="{{ route('event.request') }}" class="text-primary font-semibold hover:underline">Vraag offerte aan &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-darker relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary opacity-5 rounded-full -ml-32 -mb-32"></div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl font-bold mb-8 italic">"Wij brengen passie en vakmanschap naar uw tafel."</h2>
            <p class="text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
                Of u nu op zoek bent naar een klein verwenmomentje of een grootschalig evenement wilt organiseren, Feest op Tafel staat voor u klaar.
            </p>
            <a href="{{ route('register') }}" class="btn-primary text-lg px-12">Word lid van onze community</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-glass-border">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0 text-gradient font-bold text-xl tracking-wider">
                FEEST OP TAFEL
            </div>
            <div class="flex space-x-6 text-gray-400">
                <a href="#" class="hover:text-primary transition-colors">Algemene Voorwaarden</a>
                <a href="#" class="hover:text-primary transition-colors">Privacy Beleid</a>
                <a href="#" class="hover:text-primary transition-colors">Contact</a>
            </div>
            <div class="mt-6 md:mt-0 text-gray-500 text-sm">
                &copy; {{ date('Y') }} Feest op Tafel. Alle rechten voorbehouden.
            </div>
        </div>
    </footer>

    <script>
        // Simple scroll animation trigger
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade');
                        entry.target.style.opacity = "1";
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-fade').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
