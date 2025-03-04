<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
         integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous"
         referrerpolicy="no-referrer" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="bg-gray-100 font-sans antialiased">
        <!-- Header Section -->
         <header class="bg-white shadow-md">
            @include('layouts.header')
         </header>

        <!-- Main Layout Section -->
        <div class="flex h-screen">
            <!-- Sidebar Section -->
            <aside class="w-64  text-white space-y-4">
                @include('layouts.sidebar')
            </aside>

            <!-- Main Content Section -->
            <main class="p-6">
                <div class="bg-white rounded-lg shadow-md">
                    @yield('content')
                </div>
            </main>
        </div>

    </body>
</html>
