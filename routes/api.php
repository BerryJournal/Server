<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Пользователь
Route::post('/login', [UserController::class,'login'])->name('login');
Route::get('/confirmationUser/{id}', [UserController::class,'confirmationUser'])->name('confirmationUser');
Route::post('/confirmationUser/{id}', [UserController::class,'confirmationUserPassword'])->name('confirmationUserPassword');
Route::middleware('auth:sanctum')->get('/getUser', [UserController::class,'getUser'])->name('getUser');
Route::middleware('auth:sanctum')->get('/logout', [UserController::class,'logout'])->name('logout');

// Студент
Route::prefix('student')->middleware(['auth:sanctum', 'role:4'])->group(function () {
    // Главная
    Route::get('/getAllSubjects', [StudentController::class,'getAllSubjects']);
    // Успеваемость
    Route::get('/getDatesForSubjects', [StudentController::class,'getDatesForSubjects']);
    // Зачетная книжка
    Route::get('/getSubjectsWithFinalMark', [StudentController::class,'getSubjectsWithFinalMark']);
});


// Преподаватель


// Администратор
Route::prefix('admin')->middleware(['auth:sanctum', 'role:1,2'])->group(function () {
    // Пользователи
    Route::get('/getAllUsers', [AdminController::class,'getAllUsers']);
    Route::get('/getUserById/{id}', [AdminController::class,'getUserById']);
    Route::get('/getAllRoles', [AdminController::class,'getAllRoles']);
    Route::get('/getAllGroupsName', [AdminController::class,'getAllGroupsName']);
    Route::get('/sendConfirmation/{id}', [AdminController::class,'sendConfirmation']);
    Route::post('/addUser', [AdminController::class,'addUser']);
    Route::put('/updateUser', [AdminController::class,'updateUser']);
    Route::delete('/deleteUser/{id}', [AdminController::class,'deleteUser']);

    // Группы
    Route::get('/getAllGroups', [AdminController::class,'getAllGroups']);
    Route::get('/getGroupById/{id}', [AdminController::class,'getGroupById']);
    Route::get('/getAllTeachers', [AdminController::class,'getAllTeachers']);
    Route::get('/getAllStudents', [AdminController::class,'getAllStudents']);
    Route::get('/getAllGroupStudents', [AdminController::class,'getAllGroupStudents']);
    Route::post('/addGroup', [AdminController::class,'addGroup']);
    Route::put('/updateGroup', [AdminController::class,'updateGroup']);
    Route::delete('/deleteGroup/{id}', [AdminController::class,'deleteGroup']);

    // Специальности
    Route::get('/getAllSpecialities', [AdminController::class,'getAllSpecialities']);
    Route::get('/getSpecialityById/{id}', [AdminController::class,'getSpecialityById']);
    Route::post('/addSpeciality', [AdminController::class,'addSpeciality']);
    Route::put('/updateSpeciality', [AdminController::class,'updateSpeciality']);
    Route::delete('/deleteSpeciality/{id}', [AdminController::class,'deleteSpeciality']);

    // Предметы у групп
    Route::get('/getAllSubjectsForGroups', [AdminController::class,'getAllSubjectsForGroups']);

    Route::post('/addSubjectForGroup', [AdminController::class,'addSubjectForGroup']);
    Route::put('/updateSubjectForGroup', [AdminController::class,'updateSubjectForGroup']);
    Route::delete('/deleteSubjectForGroup/{id}', [AdminController::class,'deleteSbjectForGroup']);

    // Список предметов
    Route::get('/getAllSubjects', [AdminController::class,'getAllSubjects']);
    Route::get('/getSpecialities', [AdminController::class,'getSpecialities']);
    Route::get('/getTeachers', [AdminController::class,'getTeachers']);

    Route::post('/addSubject', [AdminController::class,'addSubject']);
    Route::put('/updateSubject', [AdminController::class,'updateSubject']);
    Route::delete('/deleteSubject/{id}', [AdminController::class,'deleteSbject']);

});
