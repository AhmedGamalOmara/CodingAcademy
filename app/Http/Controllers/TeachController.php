<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Teach;
use Illuminate\Http\Request;

class TeachController extends Controller
{
    // إضافة محاضر لكورس
    public function addLecturerToCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturer,id',
        ]);

        $teach = Teach::create([
            'courses_id' => $request->course_id,
            'lecturer_id' => $request->lecturer_id,
        ]);

        return response()->json(['message' => 'Lecturer added to course successfully!', 'data' => $teach]);
    }

    // تحديث محاضر الكورس
    public function updateLecturerInCourse(Request $request, $teachId)
    {
        $request->validate([
            'lecturer_id' => 'required|exists:lecturer,id',
        ]);

        $teach = Teach::findOrFail($teachId);
        $teach->update(['lecturer_id' => $request->lecturer_id]);

        return response()->json(['message' => 'Lecturer updated successfully!', 'data' => $teach]);
    }

    // عرض المحاضرين لكورس معين
    public function getLecturersForCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        $lecturers = $course->teach()->with('lecturer')->get();

        return response()->json(['course' => $course->name, 'lecturers' => $lecturers]);
    }

    // حذف محاضر من الكورس
    public function removeLecturerFromCourse($teachId)
    {
        $teach = Teach::findOrFail($teachId);
        $teach->delete();

        return response()->json(['message' => 'Lecturer removed from course successfully!']);
    }

    // حذف كورس مع محاضريه
    public function deleteCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        $course->delete();

        return response()->json(['message' => 'Course and its associated lecturers deleted successfully!']);
    }
}
