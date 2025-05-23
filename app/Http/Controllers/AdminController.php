<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegister;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\Speciality;
use App\Models\Subject;
use App\Models\User;
use Date;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Str;

class AdminController extends Controller
{
    // Пользователи
    function getAllUsers(Request $request) {
        $users = User::with('role', 'group')->where('organization_id', $request->user()->organization_id);
        if ($request->has('search')) {
            $users->where('name','LIKE','%'. $request->search .'%');
        }
        if ($request->has('isreg')) {
            if ($request->isreg == 0) {
                $users->where('isRegister', 0);
            } else {
                $users->where('isRegister', 1);
            }
        }
        if ($request->has('role')) {
                $users->where('role_id', $request->role);
        }
        return response()->json(['message' => $users->where('role_id', '!=', 1)->get()]);
    }
    function getUserById(Request $request, $id) {
        return response()->json(['message' => User::with('role','group')->where('id', $id)->first(['id', 'name', 'surname', 'patronymic', 'birthday', 'role_id', 'group_id', 'isRegister'])]);
    }
    function getAllRoles() {
        return response()->json(['message' => Role::where('id', '!=', 1)->get()]);
    }

    function getAllGroupsName() {
        return response()->json(['message' => Group::all('id','name')]);
    }

    function sendConfirmation(Request $request, $id) {
        $user = User::findOrFail($id);
        $invitation = Invitation::where('user_id', $user->id)->first();
        // Mail::to($user->email)->send(new ConfirmRegister($invitation, $user->name));
        $user->update(['password' => Hash::make('123'), 'isRegister' => 1]);
        if ($invitation) {
            $invitation->delete();
        }
        return response()->json(['message'=> 'Успешно отправлено!']);

    }

    function addUser(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'role_id' => 'required',
            'email' => 'required|string|email',
        ]);

        if (User::where('email', $request->email)->first()) {
            return response()->json(['error' => 'Пользователь с таким email есть!'], 400);
        }
        $userRequest = $request->all();
        if ($userRequest['role_id'] != 4) {
            $userRequest['group_id'] = null;
        }
        $user = User::create(['organization_id' => $request->user()->organization_id, ...$userRequest]);
        $invitation = Invitation::create(["user_id"=> $user->id]);
        // Mail::to($user->email)->send(new ConfirmRegister($invitation, $user->name));
        return response()->json(['message' => 'Успешно добавлено!']);
    }


    function updateUser(Request $request) {
        User::find($request->id)->update($request->all());
        return response()->json(['message' => 'Успешно обновлено!']);
    }


    function deleteUser(Request $request, $id) {
        User::find($id)->delete();
        Invitation::where('user_id', $id)->delete();
        return response()->json(['message' => 'Успешно удалено!']);
    }

    // Группы

    function getAllGroups(Request $request) {
        $group = Group::with('classroomTeacher')->where('organization_id', $request->user()->organization_id);
        if ($request->has('search')) {
            $group->where('name','LIKE','%'. $request->search .'%');
        }
        return response()->json(['message' => $group->get()]);
    }
    function getGroupById(Request $request) {
        return Group::with('classroomTeacher', 'students')->where('id', $request->id)->first();
    }

    function getAllTeachers(Request $request) {
        return response()->json(['message' => User::where('role_id', 3)->where('organization_id', $request->user()->organization_id)->get()]);
    }

    function getAllStudents(Request $request) {
        return User::where('role_id',4)->where('group', null)->get();
    }

    // function getAllGroupStudents(Request $request) {
    //     return User::where('role',4)->where('group_id', $request->id)->get();
    // }

    function addGroup(Request $request) {
        Group::create(['organization_id' => $request->user()->organization_id, ...$request->all(), 'admission_date' => date('Y-m-d')]);

        return response()->json(['message' => 'Успешно добавлено!']);
    }


    function updateGroup(Request $request) {

    }


    function deleteGroup(Request $request, $id) {

    }

    // Специальности

    function getAllSpecialities(Request $request) {
        $speciality = Speciality::where('organization_id', $request->user()->organization_id);
        if ($request->has('search')) {
            $speciality->where('name','LIKE','%'. $request->search .'%');
        }
        return response()->json(['message' =>$speciality->get()]);
    }

    function getSpecialityById(Request $request, $id) {
        return response()->json(['message' =>Speciality::where('id', $id)->first()]);
    }

    function addSpeciality(Request $request) {
        Speciality::create(['id' => Str::uuid(), 'name' => $request->name, 'organization_id' => $request->user()->organization_id]);
        return response()->json(['message' => 'Успешно']);
    }


    function updateSpeciality(Request $request) {
        Speciality::where('id', $request->id)->update($request->all());
        return response()->json(['message' => 'Успешно']);
    }


    function deleteSpeciality(Request $request, $id) {
        Speciality::find($id)->delete();
        return response()->json(['message' => 'Успешно']);
    }

    // Предметы

    function getAllSubjects(Request $request) {
        $subject = Subject::all();
        if ($request->has('search')) {
            $subject->where('name','LIKE','%'. $request->search .'%');
        }
        if ($request->has('teacher_id')) {
            $subject->where('teacher_id','LIKE','%'. $request->search .'%');
        }
        return $subject;
    }

    // Пердметы у групп

}
