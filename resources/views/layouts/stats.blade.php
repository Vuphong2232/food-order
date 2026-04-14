<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Món Ngon')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50:'#FFF8F0',100:'#F5E6D3',200:'#E8CBA7',300:'#D4A574',
                            400:'#C08B52',500:'#A0714A',600:'#8B5E3C',700:'#6B4423',
                            800:'#4A2E17',900:'#3B2313',950:'#2A1A0E'
                        },
                        cream: '#FFFCF7'
                    },
                    fontFamily: {
                        sans: ['Inter','sans-serif'],
                        serif: ['Playfair Display','serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-screen overflow-hidden text-brown-950 antialiased bg-cream">

    @include('shared.sidebar')

    <div class="ml-64 flex h-screen flex-col overflow-hidden relative z-10">

        <main class="flex-1 overflow-y-auto bg-cream/50">
            @yield('content')
        </main>

        @yield('footer')
    </div>

    @stack('scripts')
</body>
</html>