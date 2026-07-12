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

    public function trashed()
    {
        $trashedStudents = Student::onlyTrashed()->get();
        return response()->json($trashedStudents, 200);
    }

    public function destroy(Request $request)
    {
        $student = Student::find($request->id);
        $student->delete();
        return response()->json(null, 204);
    }

    public function getStudentById($id)
    {
        $student = Student::where('id', $id)
            ->withTrashed()
            ->first();
        return response()->json($student, 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'date_of_birth' => 'required|date|before:today',
            'is_active' => 'boolean',
        ]);
        $student->update($validated);
        return response()->json($student, 200);
    }

    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();
        return response()->json(['message' => 'Student restored successfully'], 200);
    }
}
