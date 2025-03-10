<div class="header flex items-center justify-between p-4 bg-white shadow-md">
    <div class="logo">
        <img src="{{ asset('logo.png') }}" alt="Logo" width="240" height="54">
    </div>
    <div class="flex gap-8 items-center">
        <a href="/messages" class="text-gray-800 hover:text-gray-800 text-2xl">
            <i class="fa-solid fa-envelope"></i>
        </a>
        <img src="{{ asset('Sad.jpg') }}" width="40" height="40" alt="Profile" class="profile-image rounded-full" />
        <a href="/logout" class="text-gray-800 hover:text-gray-800 text-2xl">
            <i class="fa-solid fa-sign-out-alt"></i>
        </a>
    </div>
</div>