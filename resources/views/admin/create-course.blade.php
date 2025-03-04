<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Create Course</h2>
    <form action="{{ route('admin.course_create_post')}}" method="POST">
        @csrf
        <label>Name</label>
        <input type="text" name="name" placeholder="Name"><br>
        <label >Credit</label>
        <input type="text" name="credit" placeholder="Credit">
        <button type="submit">Create</button>
    </form>
    
    
</body>

</html>
