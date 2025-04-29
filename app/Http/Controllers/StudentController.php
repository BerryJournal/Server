<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Group_Subject;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    function getAllSubjects(Request $request) {
        $subjects = Group_Subject::where('group_id', $request->user()->group_id)->with('subject')->get();
        return response()->json(['message' =>'']);
    }

    function getDatesForSubjects(Request $request) {

    }

}
