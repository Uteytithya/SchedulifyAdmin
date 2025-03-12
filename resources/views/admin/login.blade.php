<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-lg">
        <div class="flex justify-center">
            <img class="h-auto w-3/4" src="{{ asset('idt_logo.png') }}" alt="image description">
        </div>

        <div class="mt-10 text-center">
            <h1 class="font-bold text-xl">Login as Admin</h1>
        </div>

        <form method="POST" action="{{ route('admin.login_post') }}" class="mt-5">
            @csrf
            <div class="relative z-0 w-full mb-5 group">
                <input type="email" name="email" id="floating_email" 
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 
                    appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 
                    focus:border-blue-600 peer" 
                    placeholder=" " required />
                <label for="floating_email" 
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 
                    transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 
                    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 
                    peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Email address
                </label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="password" name="password" id="floating_password" 
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 
                    appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 
                    focus:border-blue-600 peer" 
                    placeholder=" " required />
                <label for="floating_password" 
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 
                    transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 
                    peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 
                    peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Password
                </label>
            </div>
            <div class="flex justify-center">
                <button type="submit" 
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 
                    font-medium rounded-lg text-sm w-1/3 px-5 py-2.5 text-center">
                    Submit
                </button>
            </div>
        </form>

        @if ($errors->any())
            <p class="text-red-500 text-center mt-2">{{ $errors->first() }}</p>
        @endif
    </div>
</div>

</body>
</html>
