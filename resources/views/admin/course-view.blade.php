<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Course</h1>
    <form action="{{ route('admin.course_create') }}" method="GET">
        <input type="search" placeholder="Search" name="search">
        <button>Submit</button>
    </form>
    <a href="{{ route('admin.course_create') }}"><button>Create course</button></a>
    @foreach ($courses as $course)
        <div style="background-color:gray;">
            <h3>{{$course['name']}}</h3>
            <div>
                {{$course['credit']}}
            </div>

        </div>
    @endforeach

</body>

</html>
