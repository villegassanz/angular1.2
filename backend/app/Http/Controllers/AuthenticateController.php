<?php
namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('id','password');
        
        try {
             $user_auxin= User::where('id', $request->id)->first();
             $customClaims = ['rol' => $user_auxin->id_rol];
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
                //return response()->json(['error' => 'invalid_credentials'], 401);
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
        
    public function getAuthenticatedUser()
        {
            try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

            }

            // the token is valid and we have found the user via the sub claim
            return response()->json(compact('user'));
        }
}