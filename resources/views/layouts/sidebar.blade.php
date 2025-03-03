@php
    $style = "flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-100 group text-lg";
    $activeStyle = "bg-blue-200";
@endphp

<div class="h-full px-3 py-4 overflow-y-auto text-black bg-white shadow-md">
   <h1  class="flex items-center ps-2.5 mb-5 text-2xl font-bold">
        Schedulify Admin
   </h1>

   <ul class="space-y-5 font-medium p-2">
      <li>
         <a href="/" class="{{ request()->is('/') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-house"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
        </a>
      </li>

      <li>
         <a href="/course" class="{{ request()->is('course') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-book-open-reader"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Courses</span>
         </a>
      </li>

      <li>
        <a href="/classes" class="{{ request()->is('classes') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-inbox"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Classes</span>
         </a>
      </li>

      <li>
        <a href="/timetables" class="{{ request()->is('timetables') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-calendar"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Timetables</span>
         </a>
      </li>


      <li>
        <a href="/requests" class="{{ request()->is('requests') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-comment-dots"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Requests</span>
         </a>
      </li>



      <li>
        <a href="/users" class="{{ request()->is('users') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-user"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
         </a>
      </li>


      <li>
        <a href="/groups" class="{{ request()->is('groups') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-users"></i>
            <span class="flex-1 ms-2 whitespace-nowrap">Groups</span>
         </a>
      </li>


      <li>
        <a href="/settings" class="{{ request()->is('settings') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-gear"></i>
            <span class="flex-1 ms-2 whitespace-nowrap">Settings</span>
         </a>
      </li>
   </ul>
</div>





