<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\TokenRepository;
use App\Http\Requests\AuthenticationRequest;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\Http\Controllers\AccessTokenController;

class AuthenticationController extends Controller
{

    public function __construct(
        protected AuthorizationServer $server,
        protected TokenRepository $tokens
    ){}

    private function getToken(string $email, string $password): array{
        
        $serverRequest = (new \GuzzleHttp\Psr7\ServerRequest('POST', '/oauth/token'))->withParsedBody([
            'grant_type' => 'password',
            'client_id' => config('passport.password.id'),
            'client_secret' => config('passport.password.secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);

        $controller = new AccessTokenController(
            $this->server,
            $this->tokens
        );

        $response = $controller->issueToken($serverRequest);
        $token = json_decode($response->getContent(), true);

        return $token;
        
    }

    public function register(AuthenticationRequest $request){

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            DB::commit();
            
            $token = $this->getToken($user->email, $request->password);

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            if(isset($user)){
                $user->delete();
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function login(Request $request){

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['error' => 'Wrong credentials'], 401);
        }

        $token = $this->getToken($user->email, $request->password);
        return response()->json($token);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
