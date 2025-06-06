<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Invitation;
use App\Models\News;
use App\Models\Role;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->password == '' || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Неправильный логин или пароль'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    function getUser(Request $request)
    {
        return response()->json(['message' => $request->user()]);
    }

    function confirmationUser(Request $request, $id)
    {
        $invitation = Invitation::find($id);

        if ($invitation) {
            return response()->json(['message' => User::with('organization')->find($invitation->user_id)]);
        }
        return response()->json(['message' => 'Приглашение не найдено'], 404);
    }

    function confirmationUserPassword(Request $request, $id)
    {
        $invitation = Invitation::find($id);

        if ($invitation) {
            User::find($invitation->user_id)->update(['password' => Hash::make($request->password), 'isRegister' => 1]);
            $invitation->delete();
            return response()->json(['message' => 'Успешно'], 200);
        }
        return response()->json(['message' => 'Приглашение не найдено'], 404);
    }

    function getNews(Request $request)
    {
        $news = News::where('organization_id', $request->user()->organization_id);
        if ($request->has('search')) {
            $news->where('tittle', 'LIKE', '%' . $request->search . '%');
        }
        return response()->json(['message' => $news->get()]);
    }

    function getNewsById(Request $request, $id)
    {
        return response()->json(['message' => News::where('organization_id', $request->user()->organization_id)->find($id)]);
    }

}
