@extends('layouts.layout')
@section('content')
    <div class="container mx-6 px-4 py-6 mt-8 bg-white">
        <div class="flex items-center mb-6 ">
            <h1 class="text-2xl font-extrabold">Course</h1>

            <div class="flex items-center ml-auto space-x-4">
                <form class="relative" action="{{ route('admin.course_create') }}" method="GET">
                    <i class="fa-solid fa-magnifying-glass absolute left-2 top-3 text-gray-400 "></i>
                    <input class="pl-10 bg-[#E9F5FE] p-2 rounded-lg w-64 mr-6" placeholder="Search" name="search">
                </form>
                <a class="bg-[#284BAD] text-white p-2 rounded-lg " href="{{ route('admin.course_create') }}">
                    <button>Create course</button>
                </a>
            </div>
        </div>


        @foreach ($courses as $course)
            <div style="background-color:gray;">
                <h3>{{ $course['name'] }}</h3>
                <div>
                    {{ $course['credit'] }}
                </div>
                <a href="{{ route('admin.course_edit_post', ['course' => $course->id]) }}"><button
                        class="border-solid border-1 ">Edit</button></a>

                <form action="{{route('admin.course_delete_post',['course'=>$course->id])}}" method="POST">
                    @csrf
                    @method('DELETE')

                    <a href="{{ route('admin.course_delete_post', ['course' => $course->id]) }}"><button
                            class="border-solid border-1 ">delete</button></a>

                </form>


            </div><br>
        @endforeach
    </div>
@endsection
