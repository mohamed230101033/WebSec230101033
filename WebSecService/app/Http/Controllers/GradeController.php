<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Map letter grades to numerical GPA values
    private function getGPAValue($grade)
    {
        $gradeMap = [
            'A+' => 4.3,
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'F' => 0.0,
        ];
        return $gradeMap[$grade] ?? 0; // Return 0 if grade not found
    }

    public function index()
    {
        $grades = Grade::all()->groupBy('term');

        $semesterData = [];
        $totalCreditHours = 0;
        $totalQualityPoints = 0;

        foreach ($grades as $term => $termGrades) {
            $semesterCreditHours = 0;
            $semesterQualityPoints = 0;

            foreach ($termGrades as $grade) {
                $gpaValue = $this->getGPAValue($grade->grade);
                $semesterCreditHours += $grade->credit_hours;
                $semesterQualityPoints += $gpaValue * $grade->credit_hours;
            }

            $semesterGPA = $semesterCreditHours > 0 ? $semesterQualityPoints / $semesterCreditHours : 0;

            $semesterData[$term] = [
                'grades' => $termGrades,
                'total_credit_hours' => $semesterCreditHours,
                'gpa' => round($semesterGPA, 2),
            ];

            $totalCreditHours += $semesterCreditHours;
            $totalQualityPoints += $semesterQualityPoints;
        }

        $cumulativeGPA = $totalCreditHours > 0 ? $totalQualityPoints / $totalCreditHours : 0;

        return view('grades.index', [
            'semesterData' => $semesterData,
            'totalCreditHours' => $totalCreditHours,
            'cumulativeGPA' => round($cumulativeGPA, 2),
        ]);
    }

    public function create()
    {
        return view('grades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'grade' => 'required|string|max:2',
            'credit_hours' => 'required|integer|min:1',
            'term' => 'required|string|max:255',
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'Grade added successfully.');
    }

    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'grade' => 'required|string|max:2',
            'credit_hours' => 'required|integer|min:1',
            'term' => 'required|string|max:255',
        ]);

        $grade->update($request->all());

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully.');
    }
}