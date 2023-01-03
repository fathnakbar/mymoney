<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthenticatedSessionAPIController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json([
                'message' => 'Invalid login details',
                'request' => $request
            ], 401);
        }
        
        $user = User::firstWhere("email", $request->email);

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 200);

    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function check_token(Request $request){
        $user = auth('sanctum')->user();
        return [
            "data" => $user
        ];
    }
}
