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
         <a href="{{route("admin.dashboard")}}" class="{{ request()->is('admin/auth/dashboard*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-house"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
        </a>
      </li>

      <li>
         <a href="{{route('admin.course')}}" class="{{ request()->is('admin/auth/course*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-book-open-reader"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Courses</span>
         </a>
      </li>

      <li>
        <a href="{{route("admin.rooms_index")}}" class="{{ request()->is('admin/auth/rooms*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-inbox"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Classes</span>
         </a>
      </li>

      <li>
        <a href="{{route("admin.timetables_index")}}" class="{{ request()->is('admin/auth/timetables*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-calendar"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Timetables</span>
         </a>
      </li>


      <li>
         <a href="{{ route('admin.requests_index') }}" class="{{ request()->is('admin/auth/requests*') ? $activeStyle : '' }} {{$style}}">
             <i class="fa-solid fa-comment-dots"></i>
             <span class="flex-1 ms-3 whitespace-nowrap">Requests</span>
         </a>
     </li>
     



      <li>
        <a href="{{route("admin.users.index")}}" class="{{ request()->is('admin/auth/user*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-user"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
         </a>
      </li>


      <li>
        <a href="{{route('admin.student-groups_index')}}" class="{{ request()->is('admin/auth/student-groups*') ? $activeStyle : '' }} {{$style}}">
            <i class="fa-solid fa-users"></i>
            <span class="flex-1 ms-2 whitespace-nowrap">Groups</span>
         </a>
      </li>
   </ul>
</div>





