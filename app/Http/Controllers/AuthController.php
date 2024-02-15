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



class AuthController extends Controller
{
    /**
     * Register user
     * @param RegisterRequest $request
     * @return JsonRespone
    */
    public function register(RegisterRequest $request):JsonResponse{
        $data=$request->validate();
        $data['password']=Hash::make($data['password']);
        $data['username']=strtr($data['email'],'@',true);   // generate username from email
        $user = User::create($data);
        $token=$user->createToken(User::USER_TOKEN);

        return $this->success([
            'user'=>$user,
            'token'=>$token->plainTextToken,
        ],'User has been register successfully!');
    }

    public function login(LoginRequest $request):JsonResponse{
        $isValid = $this->isValidCredentail($request);
        if (!$isValid['success']) {
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user=$isValid['user'];
        $token=$user->createToken(User::USER_TOKEN);

        return $this->success([
            'user'=>$user,
            'token'=>$token->plainTextToken
        ],'Login successfully!');
    }

    private function isValidCredentail(LoginRequest $request):array{
        // check user exit or not in over db
        $data=$request->validate();
        $user=User::where('email',$data['email'])->first();
        // if null user
        if($user===null){
            return [
                'success'=>false,
                'message'=>'Invalid Credentail!',
            ];
        }
        // if exit user
        if(Hash::check($data['password'],$user->password)){
            return [
                'success'=>true,
                'user'=>$user,
            ];
        }else{
            return [
                'success'=>false,
                'user'=>'Password is not matched',
            ];
        }
    }
    /**
     * User login with Token
     * @return jsonRespone
     */
    public function loginWithToken():JsonResponse{
        return $this->success(auth()->user(),'Login successfully!');
    }

    /**
     * User logout
     * @param Request $request
     * @return JsonRespone
    */
    public function logout(Request $request):JsonResponse{
        // need delete the current tokens
        $request->user()->currentAccessToken()->delete();
        return $this->success(null,'Logout successfully!');
    }
}
