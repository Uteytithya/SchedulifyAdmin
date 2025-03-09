@extends('layouts.layout')
@section('content')
    <div class="container mx-6 px-4 py-6 mt-8 bg-white flex-col">
        <h2 class="text-2xl font-extrabold mb-6">Create Course</h2>
        <form action="{{ route('admin.course_create_post') }}" method="POST">
            @csrf
            <label class="font-bold block">Name</label>
            <input class="border-solid border-2 py-2 pl-1 rounded-sm mb-4" name="name" placeholder="Name"><br>
            <label class="font-bold block">Credit</label>
            <input class="border-solid border-2 py-2 pl-1 rounded-sm mb-4 block" name="credit" placeholder="Credit">
            <button class="bg-[#284BAD] text-white py-1 px-3 rounded-lg">Create</button>
        </form>
    </div>
@endsection
