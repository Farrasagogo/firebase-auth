<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }
    

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            $userProperties = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            $createdUser = $this->auth->createUser($userProperties);

            return response()->json($createdUser, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
    
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idTokenString = $signInResult->idToken();
    
            // Store token in session
            session(['idToken' => $idTokenString]);
    
            // Redirect to the dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    

    public function me(Request $request)
    {
        $idTokenString = $request->bearerToken();

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $this->auth->getUser($uid);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
