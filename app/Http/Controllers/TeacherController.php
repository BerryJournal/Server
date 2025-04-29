<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // Мои группы

    function getMyClassroomGroups(Request $request) {
        return Group::with('subjects', 'students')->where("classroomTeacher_id", $request->user()->id)->get();
    }

    function getMyGroups(Request $request) {
        return Group::with(['subjects' => function($query) use ( $request ) {
            $query->where('teacher_id', $request->user()->id);
        }])->get();
    }

    function getSubjectJournal(Request $request, $id) {
        return ['students' => '', 'dates' => '', 'marks' => ''];
    }

    function addDate(Request $request) {
        return '';
    }
}
