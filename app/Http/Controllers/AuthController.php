<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\User;

class AuthController extends Controller
{
    public function getUser(Request $request) {
        return $request->user();
    }

    public function register(Request $request) {
        // $request->validate([
        //     'username' => 'required|max:250|string',
        //     'email' => 'required|max:300|email',
        //     'password' => 'required|string',
        //     'address' => 'required|string',
        //     'phone' => 'required|string',
        //     'hobbies' => 'required',
        //     'gender' => 'required|string',
        // ]);

        // dd($request->image);


        $upload_path = storage_path('app/public/uploads/users');
        $file_name = $request->photo->getClientOriginalName();
        $generated_new_name = time() . '.' . $request->photo->getClientOriginalExtension();
        $request->photo->move($upload_path, $generated_new_name);
        // dd($originalFileName);
      
        return User::create([
            'name' => $request->username,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'hobbies' => json_encode($request->hobbies),
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'image' => $generated_new_name
              ]);

    }

    public function login(Request $request) {
        try {
        $client = new Client();
        $url = config('services.passport.login_endpoint');

        $response = $client->post($url,[
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->username,
                'password' => $request->password
            ]
        ]);

        return $response->getBody();
        }
        catch(\GuzzleHttp\Exception\BadResponseException $e) {
            if($e->getCode() == 400) {
                return response()->json('Please Enter your Email ID and Password',$e->getCode());
            }
            else if($e->getCode() == 401) {
                return response()->json('Incorrect Credentials. Please try again',$e->getCode());
            }
            else
            {
                return response()->json('Something went wrong',$e->getCode());

            }
        }

    }

    public function logout() {
        $user = auth()->user()->token();
        $user->delete();    //This will delete the entire access token row for that particular user
        // $user->revoke();    This will set the revoke field to 1 for the particular user in the oauth_access_tokens table
        return response()->json("You have Logged Out Successfully");
    }
}