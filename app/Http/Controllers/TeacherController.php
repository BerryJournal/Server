<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Final_Mark;
use App\Models\Group;
use App\Models\Group_Subject;
use App\Models\Mark;
use App\Models\Skip;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // Мои группы
    function getMyClassroomGroups(Request $request)
    {
        return response()->json(['message' => Group::with('subjects', 'students')->where("classroomTeacher_id", $request->user()->id)->where('isArchive', false)->get()]);
    }

    function getMyGroups(Request $request)
    {
        return response()->json([
            'message' => Group::with([
                'subjects' => function ($query) use ($request) {
                    $query->where('teacher_id', $request->user()->id);
                }
            ])->whereHas('subjects', function ($query) use ($request) {
                $query->where('teacher_id', $request->user()->id)
                    ->where('isArchive', false);
            })->where('isArchive', false)->withCount('students')->get()
        ]);
    }

    function getJournal(Request $request, $id)
    {
        if (Group_Subject::find($id)->where('teacher_id', $request->user()->id)->count() == 0) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        $dates = Date::with('marks', 'skips')->where('group_subject_id', $id)->orderBy('date')->get();
        $groupedDates = [];
        $monthOrder = [
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь',
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август'
        ];
        foreach ($dates as $date) {
            $month = date('F', strtotime($date->date));
            $monthRu = [
                'January' => 'Январь',
                'February' => 'Февраль',
                'March' => 'Март',
                'April' => 'Апрель',
                'May' => 'Май',
                'June' => 'Июнь',
                'July' => 'Июль',
                'August' => 'Август',
                'September' => 'Сентябрь',
                'October' => 'Октябрь',
                'November' => 'Ноябрь',
                'December' => 'Декабрь'
            ][$month];

            $day = [
                'id' => $date->id,
                'date' => $date->date,
                'theme' => $date->theme,
                'marks' => $date->marks,
                'skips' => $date->skips
            ];
            if (!isset($groupedDates[$monthRu])) {
                $groupedDates[$monthRu] = ['month' => $monthRu, 'days' => []];
            }
            $groupedDates[$monthRu]['days'][] = $day;
        }

        uksort($groupedDates, function ($a, $b) use ($monthOrder) {
            return array_search($a, $monthOrder) - array_search($b, $monthOrder);
        });

        return [
            'message' => [
                'subject' => Group_Subject::with('group', 'subject')->find($id),
                'students' => User::where('group_id', Group_Subject::find($id)->group_id)->where('isArchive', false)->orderBy('surname')->orderBy('name')->get(['id', 'name', 'surname', 'patronymic']),
                'dates' => array_values($groupedDates),
                'final_marks' => Final_Mark::where('group_subject_id', $id)->get(),
                'average_marks' => Mark::selectRaw('student_id, AVG(mark) as average_mark')->join('dates', 'marks.date_id', '=', 'dates.id')->whereNotIn('mark', ['НЗ', 'ЗЧ'])->where('dates.group_subject_id', '=', $id)->groupBy('student_id')->groupBy('dates.group_subject_id')->get()->map(function ($item) {
                    return ['student_id' => $item->student_id, 'average_mark' => (float) $item->average_mark];
                })->toArray()
            ]
        ];
    }

    function addDate(Request $request)
    {
        Date::create($request->all());
        return response()->json(['message' => 'Успешно добавлено!']);
    }

    function deleteDate(Request $request, $id)
    {
        Date::find($id)->delete();
        return response()->json(['message' => 'Успешно удалено!']);
    }

    function updateDate(Request $request, $id)
    {
        Date::find($id)->update($request->all());
        return response()->json(['message' => 'Успешно изменено!']);
    }

    function addSkip(Request $request)
    {
        Skip::create(['student_id' => $request->student_id, 'date_id' => $request->date_id]);
        return response()->json(['message' => 'Успешно добавлено!']);
    }

    function removeSkip(Request $request)
    {
        Skip::where('student_id', $request->student_id)->where('date_id', $request->date_id)->first()->delete();
        return response()->json(['message' => 'Успешно удалено!']);
    }

    function updateMark(Request $request)
    {
        $mark = Mark::where('date_id', $request->date_id)->where('student_id', $request->student_id)->first();
        if ($mark == null) {
            Mark::create($request->all());
            return response()->json(['message' => 'Успешно добавлена оценка!']);
        }
        if ($request->mark == '') {
            $mark->delete();
            return response()->json(['message' => 'Успешно удалено!']);
        }
        $mark->update(['mark' => $request->mark, 'comment' => $request->comment]);
        return response()->json(['message' => 'Успешно изменено!']);
    }

    function updateFinalMark(Request $request)
    {
        $finalMark = Final_Mark::where('group_subject_id', $request->group_subject_id)->where('student_id', $request->student_id)->first();
        if ($finalMark == null) {
            Final_Mark::create($request->all());
            return response()->json(['message' => 'Успешно добавлена итоговая оценка!']);
        }
        if ($request->mark == '') {
            $finalMark->delete();
            return response()->json(['message' => 'Успешно удалено!']);
        }
        $finalMark->update(['mark' => $request->mark]);
        return response()->json(['message' => 'Успешно изменено!']);
    }
}
