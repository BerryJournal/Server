<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Final_Mark;
use App\Models\Group_Subject;
use App\Models\Mark;
use App\Models\Skip;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    function getAllSubjects(Request $request)
    {
        $subjects = Group_Subject::where('group_id', $request->user()->group_id)->with('subject')->get();
        return response()->json(['message' => $subjects]);
    }

    function getSubject(Request $request, $id)
    {
        if (Group_Subject::find($id)->count() == 0) {
            return response()->json(['error' => 'Такого предмета нет!'], 404);
        }
        return response()->json([
            'message' => [
                'date' => Date::with([
                    'marks' => function ($query) use ($request) {
                        $query->where('student_id', $request->user()->id);
                    },
                    'skips' => function ($query) use ($request) {
                        $query->where('student_id', $request->user()->id);
                    }
                ])->where('group_subject_id', $id)->orderBy('date')->get(),
                'subject' => Group_Subject::with('subject')->find($id, ['id', 'subject_id']),
                'skips_count' => Skip::where('student_id', $request->user()->id)
                    ->whereHas('date', function ($query) use ($id) {
                        $query->where('group_subject_id', $id);
                    })->count(),
                'average_mark' => Mark::where('student_id', $request->user()->id)
                    ->whereHas('date', function ($query) use ($id) {
                        $query->where('group_subject_id', $id);
                    })->whereNotIn('mark', ['нз', 'зч'])->avg('mark'),
                'final_mark' => Final_Mark::where('group_subject_id', $id)->where('student_id', $request->user()->id)->first('mark')
            ]
        ]);
    }

}
