<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegister;
use App\Models\Group;
use App\Models\Group_Subject;
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
        $users = User::with('role', 'group')->where('organization_id', $request->user()->organization_id)->where('isArchive', false);
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
        return response()->json(['message' => Group::where('isArchive', false)->where('classroomTeacher_id', null)->get(['id','name'])]);
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
        User::find($id)->update(['isArchive' => true]);
        Invitation::where('user_id', $id)->delete();
        return response()->json(['message' => 'Успешно удалено!']);
    }

    // Группы

    function getAllGroups(Request $request) {
        $group = Group::with('classroomTeacher', 'students', 'speciality')->where('organization_id', $request->user()->organization_id)->where('isArchive', false);
        if ($request->has('search')) {
            $group->where('name','LIKE','%'. $request->search .'%');
        }
        return response()->json(['message' => $group->get()]);
    }
    function getGroupById(Request $request) {
        return response()->json(['message' =>Group::with('classroomTeacher', 'students', 'speciality')->where('id', $request->id)->first()]);
    }

    function getAllTeachers(Request $request) {
        return response()->json(['message' => User::where('role_id', 3)->where('organization_id', $request->user()->organization_id)->get()]);
    }

    function getAllStudents(Request $request) {
        return User::where('role_id',4)->where('group', null)->get();
    }

    function addGroup(Request $request) {
        Group::create(['organization_id' => $request->user()->organization_id, ...$request->all(), 'admission_date' => date('Y-m-d')]);

        return response()->json(['message' => 'Успешно добавлено!']);
    }


    function updateGroup(Request $request) {
        Group::find($request->id)->update($request->all());
        return response()->json(['message' => 'Успешно обновлено!']);
    }


    function deleteGroup(Request $request, $id) {
        Group::findOrFail($id)->update(['isArchive' => true]);
        // User::where('group_id', $id)->update('group_id', null);
        return response()->json(['message'=> 'Успешно удалено!']);
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
        Speciality::create(['name' => $request->name, 'organization_id' => $request->user()->organization_id]);
        return response()->json(['message' => 'Успешно']);
    }


    function updateSpeciality(Request $request) {
        Speciality::find( $request->id)->update($request->all());
        return response()->json(['message' => 'Успешно']);
    }


    function deleteSpeciality(Request $request, $id) {
        Speciality::find($id)->delete();
        return response()->json(['message' => 'Успешно']);
    }

    // Предметы

    function getAllSubjects(Request $request) {
        $subjects = Subject::where('organization_id', $request->user()->organization_id)->where('isArchive', false);
        if ($request->has('search')) {
            $subjects->where('name','LIKE','%'. $request->search .'%');
        }
        return response()->json(['message' =>$subjects->get()]);
    }

    function getSubjectById(Request $request, $id) {
        return response()->json(['message' =>Subject::where('id', $id)->first()]);
    }

    function addSubject(Request $request) {
        Subject::create(['name' => $request->name, 'organization_id' => $request->user()->organization_id]);
        return response()->json(['message' => 'Успешно']);
    }


    function updateSubject(Request $request) {
        Subject::find( $request->id)->update($request->all());
        return response()->json(['message' => 'Успешно']);
    }


    function deleteSubject(Request $request, $id) {
        Subject::find($id)->update(['isArchive' => true]);
        return response()->json(['message' => 'Успешно']);
    }

    // Пердметы у групп

     function getAllSubjectsForGroups(Request $request) {
        $subjects = Group_Subject::with('group', 'subject', 'teacher')->where('organization_id', $request->user()->organization_id)->where('isArchive', false);
        if ($request->has('search')) {
            $subjects->where('subject_id','LIKE','%'. Subject::where('name', $request->search)->first()->id .'%');
        }
        return response()->json(['message' =>$subjects->get()]);
    }

    function getSubjectForGroupById(Request $request, $id) {
        return response()->json(['message' =>Group_Subject::where('id', $id)->first()]);
    }

    function getAllDataForSubjects(Request $request) {
        return response()->json(['message' =>['subjects' => Subject::where('organization_id', $request->user()->organization_id)->get(), 'teachers' => User::where('organization_id', $request->user()->organization_id)->where('role_id', 3)->get(), 'groups' => Group::where('organization_id', $request->user()->organization_id)->get()]]);
    }

    function addSubjectForGroup(Request $request) {
        if (Group_Subject::where('group_id', $request->group_id)->where('subject_id', $request->subject_id)->where('isArchive', false)->get()->count() != 0) {
            return response()->json(['error'=> 'У группы уже есть данный предмет'], 400);
        }
        Group_Subject::create([...$request->all(), 'course' => Group::find($request->group_id)->course, 'organization_id' => $request->user()->organization_id]);
        return response()->json(['message' => 'Успешно']);
    }


    function updateSubjectForGroup(Request $request) {
        Group_Subject::find( $request->id)->update($request->all());
        return response()->json(['message' => 'Успешно']);
    }


    function deleteSubjectForGroup(Request $request, $id) {
        Group_Subject::find($id)->update(['isArchive' => true]);
        return response()->json(['message' => 'Успешно']);
    }
}
