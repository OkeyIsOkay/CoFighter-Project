<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Browser;
use Illuminate\Support\Facades\Session;
use App\Classes\General\General;
use App\Classes\User\ManageUser;
use App\Jobs\SendUserOtp;
use App\Models\User;
use App\Models\otp;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
        if(!$request->username){
            return General::ErrorResponse('Enter your email or phone number.');
        }

        if(!$request->password){
            return General::ErrorResponse('Password can not be empty.');
        }

        $user = ManageUser::SearchUser($request->username);

        if(!$user){
            return General::ErrorResponse('Invalid login credentials.');
        }

        if(!Hash::check($request->password, $user->password)){
            return General::ErrorResponse('Invalid password.');
        }

        if(!$user->active){
            return General::ErrorResponse('Your account has been disabled. Contact support for more info.');
        }

		return $this->generateLoginToken($user, "Login Successful");
    }

    private function generateLoginToken($user, $message=""){
        $this->revokeAllTokens($user);
        $device_name = $this->getDeviceName();

        $token = $user->createToken($device_name)->plainTextToken;
        return $this->loginRegNewToken($token, $user, $message);
    }

    protected function revokeAllTokens($user){
        $user->tokens()->delete();
    }

    private function getDeviceName(){
        return Browser::browserName();
    }

    protected function loginRegNewToken($token, $user, $message=''){
        $data = [
            'token'=>$token,
            'user'=>ManageUser::GetUserData($user->id),
        ];
        return General::SuccessResponse($message, $data, 0);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/CoFighter/login');
    }

    public function getResetPassword(Request $request)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        return view("Auth.reset-password-initiate");
    }
    public function ResetPassword(Request $request)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/reset/initiate')->withErrors($validator)->withInput();
        }

        $email = $request->email;


        if(!$email){
            Session::put('message','Enter your registered email to reset your password.');
            return redirect('/CoFighter/reset/initiate')->withInput();
        }

        $user = ManageUser::SearchUser($email);

        if(!$user){
            Session::put('message','The email entered is unregistered.');
            return redirect('/CoFighter/reset/initiate')->withInput();
        }

        $code = 'RESET-'.ManageUser::NewUserCode();
        $sentOTP = General::GenerateOTP();
        $otp = new otp();
        $otp->trans_id = $code;
        $otp->code = $sentOTP;
        $otp->purpose = 'RESET PASSWORD';
        $otp->save();

        $data = [
            'Reset_Code'=>$code,
            "User_Phone"=>$user->phone,
            "User_Email"=>$user->email,
            "name"=>$user->name,
        ];

        $notificationData = (object)[
            'message' => 'Enter the OTP below to reset your password. OTP expires in 10 minutes.',
            'otp' => $sentOTP,
            'email' => $user->email
        ];

        dispatch(new SendUserOtp($notificationData));

        Session::put('message',"Hello $user->name kindly enter the OTP sent to your email.");
        Session::put('email',$user->email);
        Session::put('reset_code',$code);

        return redirect('/CoFighter/reset/verify');

    }

    public function getResetPasswordVerify(Request $request)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        return view("Auth.reset-password-verify");
    }

    public function ResetPasswordVerify(Request $request)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/reset/initiate')->withErrors($validator)->withInput();
        }

        $otp = $request->otp;
        $email = $request->email;
        $resetCode = $request->reset_code;

        if(!$otp){
            Session::put('message','Enter the OTP sent to your phone and email.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        if(!$email){
            Session::put('message','Email can not be empty.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        if(!$resetCode){
            Session::put('message','Reset Code can not be empty.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        $getOTP = otp::where('trans_id',$resetCode)->first();

        if($otp != $getOTP->code){
            Session::put('message','Invalid OTP.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        if($getOTP->is_used == true){
            Session::put('message','The OTP entered has been used.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        if($getOTP->is_expired == true){
            Session::put('message','The OTP entered has expired.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        if(General::CheckOtpExpiryTime($getOTP->created_at)){
            $getOTP->is_expired = true;
            $getOTP->save();
            Session::put('message','The OTP entered has expired.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        $getOTP->is_used = true;
        $getOTP->save();

        $user = ManageUser::SearchUser($email);

        if(!$user){
            Session::put('message','Invalid Email.');
            return redirect('/CoFighter/reset/verify')->withInput();
        }

        $data = [
            'reset_code'=>$getOTP->trans_id,
            "User_Phone"=>$user->phone,
            "User_Email"=>$user->email,
            "name"=>$user->name,
        ];

        $users = User::find($user->id);
        $users->verify = true;
        $users->save();

        Session::put('email',$user->email);
        Session::put('reset_code',$getOTP->trans_id);
        Session::put('message','You have successfully been verified. Proceed to reset password.');
        return redirect('/CoFighter/reset/complete');

    }

    public function getResetPasswordChangePassword(Request $request)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        if(!$user->verify){
            Session::put('message','Unauthorised Acess');
            return redirect('/CoFighter/login');
        }

        return view("Auth.reset-password-complete");
    }

    public function ResetPasswordChangePassword(Request $request){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        if(!$user->verify){
            Session::put('message','Unauthorised Acess');
            return redirect('/CoFighter/login');
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/reset/complete')->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $resetCode = $request->reset_code;
        $password = $request->password;

        if(!$resetCode){
            Session::put('message','Reset Code can not be empty.');
            return redirect('/CoFighter/reset/complete')->withInput();
        }
        $getOTP = otp::where('trans_id',$resetCode)->first();

        if(!$getOTP){
            Session::put('message','Request could not be authenticated.');
            return redirect('/CoFighter/reset/complete')->withInput();
        }

        if($getOTP->is_used != true){
            Session::put('message','Request could not be authenticated (OTP not verified).');
            return redirect('/CoFighter/reset/complete')->withInput();
        }

        if(!$email){
            Session::put('message','Enter your email to proceed.');
            return redirect('/CoFighter/reset/complete')->withInput();
        }

        if(!$password){
            Session::put('message','Enter a new password to continue.');
            return redirect('/CoFighter/reset/complete')->withInput();
        }

        $users = ManageUser::SearchUser($username);

        if(!$users){
            Session::put('message','User not found.');
            return redirect('/CoFighter/reset/complete')->withInput();
        }

        $user = User::find($users->id);
        $user->password = bcrypt($request->password);
        $user->password_reset = true;
        $user->save();

        $rank = null;
        $link = null;

        $rank = Auth::user()->rank;

        if($rank == 1){
            $link = 'doctor';
        }
        if($rank == 5){
            $link = 'admin';;
        }
        if($rank == 10){
            $link = 'super-admin';;
        }

        return redirect("/CoFighter/$link/dashboard");

    }

    public function cofighterLogin(){
        return view("Auth.signin");
    }

    public function doLogin(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/login')->withErrors($validator)->withInput();
        }

        $message = null;
        $credentials = ['email'=>$request->email,'password'=>$request->password];
        if (Auth::attempt(($credentials))) {
            $rank = Auth::user()->rank;
            if($rank == 0){
                Session::put('message','Unauthorised Access.');
                return redirect('/CoFighter/login')->withInput();
            }
            if($rank == 1){
                return redirect('/CoFighter/doctor/dashboard');
            }
            if($rank == 5){
                return redirect('/CoFighter/admin/dashboard');
            }
            if($rank == 10){
                return redirect('/CoFighter/super-admin/dashboard');
            }
        }else{
            Session::put('message','Invalid Login Credentials.');
            return redirect('/CoFighter/login')->withInput();
        }
    }

    public function getKYC(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            $message = my_encode('Login to continue.');
            return redirect('/kyc/login?message='.$message);
        }

        $users = User::where('verified_at',NULL)->whereNotIn('id', [1,2,3,4,5])->get();
        return view("verify.index",['users'=>$users]);
    }

    public function verifyUser($id){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            $message = my_encode('Login to continue.');
            return redirect('/kyc/login?message='.$message);
        }

        if(!$id){
            $message = my_encode('This request can not be completed.');
            return redirect('/kyc/dashboard?message='.$message);
        }

        $user = User::find($id);

        if(!$user){
            $message = my_encode('Invalid User ID');
            return redirect('/kyc/dashboard?message='.$message);
        }

        if($user->verified_at){
            $message = my_encode('This user have been verified already.');
            return redirect('/kyc/dashboard?message='.$message);
        }

        $user->verified_at = date('Y-m-d H:i:s');
        $user->verified_by = 7;

        if($user->save()){
            $message = my_encode('User successfully verified.');
            $send = $this->subVerify($user->name, $user->email, 'You have been successfully verified. Now you can use DAP with absolutely no limitations and zero boundary. Thanks for choosing DAP!');
            return redirect('/kyc/dashboard?message='.$message);
        }

    }

    public function kycVerify($id){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            $message = my_encode('Login to continue.');
            return redirect('/kyc/login?message='.$message);
        }

        if(!$id){
            $message = my_encode('This request can not be completed.');
            return redirect('/kyc/dashboard?message='.$message);
        }

        $user = User::find($id);

        if($user->verified_at){
            $message = my_encode('This user have been verified already.');
            return redirect('/kyc/dashboard?message='.$message);
        }

        return view("verify.user-verification",['user'=>$user]);
    }

    public function doKyc(Request $request){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            $message = my_encode('Login to continue.');
            return redirect('/kyc/login?message='.$message);
        }

        $id = $request->id;
        $action = $request->action;
        $comment = $request->comment;

        $user = User::find($id);

        if($action == 1){
            $user->verified_at = date('Y-m-d H:i:s');
            $user->verified_by = 7;
            $user->save();

            $send = $this->subVerify($user->name, $user->email, 'You have been successfully verified. Now you can use DAP with absolutely no limitations and zero boundary. Thanks for choosing DAP!');
            $message = my_encode('User successfully verified.');
            return redirect('/kyc/dashboard?message='.$message);
        }

        if($action == 2){
            if($comment){
                $user->verification_comment = $comment;
                $user->save();

                $send = $this->subVerify($user->name, $user->email, "Your KYC verification was not approved. Reason: $comment");
                $message = my_encode('The user have been notified of this action.');
                return redirect('/kyc/dashboard?message='.$message);
            }else{
                $message = my_encode('Please enter the reason for the action. This will enable the user fix up.');
                return redirect('/more/user/'.$id.'?message='.$message)->withInput();
            }
        }
        return redirect('/kyc/dashboard');
    }

    private function subVerify($name, $email, $message){
        $data = (object)[
            'name'=>$name,
            'email'=>$email,
            'message'=>$message
        ];
        dispatch(new SendVerifyMessage($data));
        return true;
    }

    public function test(){
        $pw = 'Dammychology93@';
        return General::SuccessResponse('Done', bcrypt($pw));

    }

}
