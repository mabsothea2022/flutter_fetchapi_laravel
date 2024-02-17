<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// for Hash
use Illuminate\Support\Facades\Hash;
// for respond
use Illuminate\Http\Response;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    /**
     * Register user
     * @param RegisterRequest $request
     * @return JsonRespone
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['username'] = strtr($data['email'], '@', true);   // generate username from email
        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'User has been register successfully!');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        // Get the email and password from the request
        $email = $request->input('email');
        $password = $request->input('password');

        // Now you can pass email and password to isValidCredentail() method
        $isValid = $this->isValidCredentail($email, $password);

        if (!$isValid['success']) {
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $isValid['user'];
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 'Login successfully!');
    }

    private function isValidCredentail(string $email, string $password): array
    {
        // Validate the email and password
        $data = [
            'email' => $email,
            'password' => $password,
        ];
        $validator = validator($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        // Check if the user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }

        // Check if the password is correct
        if (!Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }

        return [
            'success' => true,
            'user' => $user,
        ];
    }


    /**
     * User login with Token
     * @return jsonRespone
     */
    public function loginWithToken(): JsonResponse
    {
        return $this->success(auth()->user(), 'Login successfully!');
    }

    /**
     * User logout
     * @param Request $request
     * @return JsonRespone
     */
    public function logout(Request $request): JsonResponse
    {
        // need delete the current tokens
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logout successfully!');
    }
}
