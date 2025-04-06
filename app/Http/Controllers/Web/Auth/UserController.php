<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('users.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string'],
            'courses' => ['nullable', 'array'],
            'courses.*' => ['exists:courses,id'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        foreach ($request->courses as $courseId) {
            CourseUser::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'course_id' => $courseId,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $courses = Course::all();
        $userCourses = $user->courses->pluck('id')->toArray();

        return view('users.edit', compact('user', 'courses', 'userCourses'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Validate input using the Rule class
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string'],
        ]);

        // Update user attributes
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        foreach ($request->courses as $courseId) {
            CourseUser::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'course_id' => $courseId,
            ]);
        }

        // Save user and sync courses
        $user->save();
        $user->courses()->sync($request->courses ?? []);

        // Redirect back with success message
        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
