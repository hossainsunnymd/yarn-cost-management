<?php



namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    //user login page
    public function loginPage(){
       return Inertia::render('Auth/LoginPage');
    }

    //user login
    public function login(Request $request){

     
          $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
          ]);

          if($validator->fails()){

            return redirect()->back()->with(['errors' => $validator->errors()]);

          }


          $user = User::where('email', $request->email)->first();

          if (!$user || !Hash::check($request->password, $user->password)) {
              return redirect()->back()->with(['status'=>false,'message'=>'Invalid email or password']);
          }

          Auth::login($user);
          return redirect('/yarn-purchase-list')->with(['status'=>true,'message'=>'Login Successfully']);
    }

    //user logout
    public function logout(Request $request){

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
