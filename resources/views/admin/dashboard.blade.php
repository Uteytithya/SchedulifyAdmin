@extends('layouts.layout')
@section('content')
<div class="flex justify-center space-x-10 mt-10">
    <!-- Room Details -->
    <div class="w-[375px] h-[140px] bg-white rounded-2xl shadow-md p-4 flex items-center justify-between">
        <i class="fa-solid fa-door-open text-[55px] ml-5"></i>
        <div>
            <h3 class="text-xl font-bold mb-1">Room Details</h3>
            <p class="text-gray-700">Available Room</p>
        </div>
        <span class="text-[35px] font-bold text-gray-700 mr-3">{{$roomCount}}</span>
    </div>

    <!-- User Details -->
    <div class="w-[375px] h-[140px] bg-white rounded-2xl shadow-md p-4 flex items-center justify-between">
        <i class="fa-solid fa-user text-[55px] ml-5"></i>
        <div>
            <h3 class="text-xl font-bold mb-1">User Details</h3>
            <p class="text-gray-700">Total Users</p>
        </div>
        <span class="text-[35px] font-bold text-gray-700 mr-3">{{$userCount}}</span>
    </div>

    <!-- Request Details -->
    <div class="w-[375px] h-[140px] bg-white rounded-2xl shadow-md p-4 flex items-center justify-between">
        <i class="fa-solid fa-code-pull-request text-[55px] ml-5"></i>
        <div>
            <h3 class="text-xl font-bold mb-1">Request Details</h3>
            <p class="text-gray-700">Total Requests</p>
        </div>
        <span class="text-[35px] font-bold text-gray-700 mr-3">{{$requestCount}}</span>
    </div>
</div>

<!-- Table Section -->
<div class="mt-10 px-10">
    <h2 class="text-2xl font-bold mb-4">Rooms</h2>
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <!-- <th class="p-3"><input type="checkbox"></th> -->
                    <th class="p-3 border-r">Room</th>
                    <th class="p-3 border-r">Floor</th>
                    <th class="p-3 border-r">Capacity</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($rooms->sortBy('name') as $room) 
                <tr class="border-b">
                    <!-- <td class="p-3"><input type="checkbox"></td> -->
                    <td class="p-3 border-r">{{$room->name}}</td>
                    <td class="p-3 border-r">{{$room->floor}}</td>
                    <td class="p-3 border-r">{{$room->capacity}}</td>
                    <td class="p-3 text-green-500 font-semibold {{ $room->is_active ? 'text-green-500' : 'text-red-500' }}">
                    {{ $room->is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
