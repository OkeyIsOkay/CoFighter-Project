<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\General\General;
use App\Classes\User\ManageUser;
use App\Models\User;
use App\Models\SaveBenefitiary;
use App\Models\otps;
use App\Models\pendingUsers;
use App\Jobs\SendUserOtp;
use App\Models\ReportedCases;
use App\Models\ReportedComplication;
use App\Models\BookVaccination;
use Illuminate\Support\Facades\Session;
use App\Models\Cases;

class SuperAdminController extends Controller
{

    public function index(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $states = General::states();

        $centers = General::centers();

        return view("OverallSuperAdmins.index",['user'=>$user,'states'=>$states,'centers'=>$centers]);
    }

    public function addUser(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $states = General::states();

        $centers = General::centers();

        return view("OverallSuperAdmins.add-user",['user'=>$user,'states'=>$states,'centers'=>$centers]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required',
            'center' => 'required',
            'surname' => 'required',
            'firstname' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/super-admin/add-user')->withErrors($validator)->withInput();
        }

        $phone = ManageUser::ValidatePhoneToNational($request->phone, 'NG');

        if(!$phone){
            Session::put('error_message','Phone number is invalid. Please enter national format.');
            return redirect('/CoFighter/super-admin/add-user');
        }

        //test user table
        $testForExistingUserData = User::where('phone',$phone)->first();
        if($testForExistingUserData){
            Session::put('error_message','The phone number is already taken.');
            return redirect('/CoFighter/super-admin/add-user');
        }

        $testForExistingUserData = User::where('email',$request->email)->first();
        if($testForExistingUserData){
            Session::put('error_message','The email is already taken.');
            return redirect('/CoFighter/super-admin/add-user');
        }

        $users = ManageUser::GetUserData();

        $user = new User();
        $user->surname = $request->surname;
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->rank = $request->rank;
        if($request->is_admin){
            $user->admin_previledge = true;
        }
        $user->email = $request->email;
        $user->center_id = $request->center;
        $user->center_name = ManageUser::GetCenterName($request->center);
        $user->state = General::state($request->state);;
        $user->password = bcrypt($request->phone);
        $user->save();

        Session::put('message','User added successfully.');
        return redirect('/CoFighter/super-admin/add-user');
    }

    public function manageUser(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $doctor = User::where('rank','!=',10)
        ->where('id','!=',$user_id)
        ->get();

        return view("OverallSuperAdmins.manage-users",['user'=>$user,'doctors'=>$doctor]);
    }

    public function editUser($id){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Invalid Request.');
            return redirect('/CoFighter/super-admin/dashboard');
        }

        $user = ManageUser::GetUserData($user_id);

        $edit_user = User::find($id);

        if(!$edit_user){
            Session::put('error_message','Unkown user.');
            return redirect('/CoFighter/super-admin/dashboard');
        }

        if($edit_user->active){
            $edit_user->active = false;
        }else{
            $edit_user->active = true;
        }

        $edit_user->save();

        $doctor = User::where('rank','!=',10)
        ->where('id','!=',$user_id)
        ->get();

        return view("OverallSuperAdmins.manage-users",['user'=>$user,'doctors'=>$doctor]);
    }

    public function getCenter(){

        $id = $_GET['id'];

        if(!$id){
            Session::put('message','Invalid Request.');
            return redirect('/CoFighter/super-admin/add-user');
        }

        $centers = General::centers($id);

        if(count($centers)){
            echo "<option value=''>Select Center</option>";
            foreach($centers as $center){
                echo "<option value='$center->id'>$center->name</option>";
            }
        }else{
            echo '<option>No center was found for this state.</option>';
        }


    }
}
