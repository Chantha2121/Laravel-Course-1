<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentsController extends Controller
{

    public function index()
    {
        $students = Student::all();
        return response()->json($students, 200);
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email',
            'date_of_birth' => 'required|date|before:today',
            'is_active' => 'boolean',
        ]);

        $student = Student::create($validate);
        return response()->json($student, 201);
    }
}
