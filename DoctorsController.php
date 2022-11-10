<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\General\General;
use App\Classes\User\ManageUser;
use App\Models\ReportedCases;
use App\Models\ReportedComplication;
use App\Models\BookVaccination;
use App\Models\Cases;
use App\Jobs\SendUserMessage;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class DoctorsController extends Controller
{
    public function index(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->where('type',1)->get();

        return view("Doctors.index",['user'=>$user,'cases'=>$cases]);
    }

    public function addUser(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        return view("Doctors.add-user",['user'=>$user]);
    }

    public function addCases(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        return view("Doctors.add-case",['user'=>$user]);
    }

    public function manageUser(){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $doctor = User::where('center_name',$user->center_name)
        ->where('center_id',$user->center_id)
        ->where('state',$user->state)
        ->where('rank',1)
        ->where('id','!=',$user_id)
        ->get();

        return view("Doctors.manage-users",['user'=>$user,'doctors'=>$doctor]);
    }

    public function editUser($id){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Invalid Request.');
            return redirect('/CoFighter/doctor/manage-user');
        }

        $user = ManageUser::GetUserData($user_id);

        $edit_user = User::find($id);

        if(!$edit_user){
            Session::put('error_message','Unkown user.');
            return redirect('/CoFighter/admin/manage-user');
        }

        if($edit_user->active){
            $edit_user->active = false;
        }else{
            $edit_user->active = true;
        }

        $edit_user->save();

        $doctor = User::where('center_name',$user->center_name)
        ->where('center_id',$user->center_id)
        ->where('state',$user->state)
        ->where('rank',1)
        ->where('id','!=',$user_id)
        ->get();

        return view("Doctors.manage-users",['user'=>$user,'doctors'=>$doctor]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required',
            'firstname' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/doctor/add-user')->withErrors($validator)->withInput();
        }

        $phone = ManageUser::ValidatePhoneToNational($request->phone, 'NG');

        if(!$phone){
            Session::put('error_message','Phone number is invalid. Please enter national format.');
            return redirect('/CoFighter/doctor/add-user');
        }

        //test user table
        $testForExistingUserData = User::where('phone',$phone)->first();
        if($testForExistingUserData){
            Session::put('error_message','The phone number is already taken.');
            return redirect('/CoFighter/doctor/add-user');
        }

        $testForExistingUserData = User::where('email',$request->email)->first();
        if($testForExistingUserData){
            Session::put('error_message','The email is already taken.');
            return redirect('/CoFighter/doctor/add-user');
        }

        $users = ManageUser::GetUserData();

        $user = new User();
        $user->surname = $request->surname;
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->rank = 1;
        $user->email = $request->email;
        $user->center_id = $users->center_id;
        $user->center_name = ManageUser::GetCenterName($users->center_id);
        $user->state = $users->state;
        $user->password = bcrypt($request->phone);
        $user->save();

        Session::put('message','User added successfully.');
        return redirect('/CoFighter/doctor/add-user');
    }

    public function reportCase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_type' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/CoFighter/doctor/dashboard')->withErrors($validator)->withInput();
        }

        $is_new = true;

        $case_type = $request->case_type;
        $status = $request->status;

        if($request->number){
            $number = $request->number;
        }else{
            $number = 1;
        }

        if($status == 1){
            $is_new = true;
        }

        if($status == 2){
            $is_new = false;
        }

        if(!$is_new){
            if($case_type !=2 && $case_type !=3){
                Session::put('error_message','Invalid request. An old case can either be discharge or death.');
                return redirect('/CoFighter/doctor/dashboard')->withInput();
            }
        }

        $data = ManageUser::GetUserData();

        $user = new Cases();
        $user->user_id = $data->id;
        $user->user_name = $data->surname.' '.$data->firstname;
        $user->number = $number;
        $user->center_id = $data->center_id;
        $user->center_name = ManageUser::GetCenterName($data->center_id);
        $user->state = $data->state;
        $user->is_new = $is_new;
        $user->case_type = $case_type;
        $user->case_date = $case_date;
        $user->description = $request->description;
        $user->save();

        Session::put('message','Case uploaded Successfully.');
        return redirect('/CoFighter/doctor/dashboard');
    }

    public function reportedCases()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('status','PENDING')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->where('type',0)->get();

        return view("Doctors.reported-cases",['user'=>$user,'cases'=>$cases]);
    }

    public function reportedComplications()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedComplication::where('status','PENDING')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->get();

        return view("Doctors.reported-complications",['user'=>$user,'complications'=>$cases]);
    }

    public function bookedVaccination()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $bookings = BookVaccination::where('status','PENDING')->where('prefered_center_id',$user->center_id)->where('prefered_center_name',$user->center_name)->where('prefered_state',$user->state)->get();

        return view("Doctors.booked-vaccination",['user'=>$user,'bookings'=>$bookings]);
    }

    public function editBookings($action,$id){

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Invalid Request.');
            return redirect('/CoFighter/doctor/booked-vaccination');
        }

        if(!$action){
            Session::put('error_message','Invalid Request.');
            return redirect('/CoFighter/doctor/booked-vaccination');
        }

        if($action != 'approve' && $action != 'disapprove'){
            Session::put('error_message','Invalid Request.');
            return redirect('/CoFighter/doctor/booked-vaccination');
        }

        $user = ManageUser::GetUserData($user_id);

        $editBookings = BookVaccination::find($id);

        if(!$editBookings){
            Session::put('error_message','Unkown user.');
            return redirect('/CoFighter/doctor/booked-vaccination');
        }

        $message = null;
        if($action == 'approve'){
            $editBookings->approval_status = 'Approved';
            $message = 'Your covid 19 vaccination request has been approved. Vacciation date is '.date('d/m/Y', strtotime($editBookings->preferred_date)).'. Your Vaccination ID is: '.$editBookings->vaccination_id;
        }

        if($action == 'disapprove'){
            $editBookings->approval_status = 'Disapproved';
            $message = 'Your covid 19 vaccination request has was diapproved. Please re-apply some other time.';
        }

        $editBookings->save();

        $bookings = BookVaccination::where('status','PENDING')
        ->where('prefered_center_id',$user->center_id)
        ->where('prefered_center_name',$user->center_name)
        ->where('prefered_state',$user->state)
        ->get();

        $notificationData = (object)[
            'message' => $message,
            'email' => $editBookings->email,
            'name' => $editBookings->firstname.' '.$editBookings->surname
        ];

        dispatch(new SendUserMessage($notificationData));

        return view("Doctors.booked-vaccination",['user'=>$user,'bookings'=>$bookings]);
    }

    public function reportedCasesPost(Request $request){

        $validator = Validator::make($request->all(), [
            'date' => 'required'
        ]);

        if($validator->fails()){
            return redirect("/CoFighter/doctor/reported-cases/more/$request->id")->withErrors($validator)->withInput();
        }

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $data = ReportedCases::find($request->id);

        if(!$data){
            return redirect("/CoFighter/doctor/reported-cases/more/$request->id")->withInput()->with('error_message','Your request could not be completed');
        }

        $data->type = 1;
        $data->save();

        $notificationData = (object)[
            'message' => "Your reported case has been recieved and you are expected to be at $data->center_name on $request->date between 8am and 4pm. Your unique ID is: $data->case_id.",
            'email' => $data->email,
            'name' => $data->firstname.' '.$data->surname
        ];

        dispatch(new SendUserMessage($notificationData));

        return redirect("/CoFighter/doctor/reported-cases/more/$request->id")->with('message','Reported case successfully confirmed.');
    }

    public function reportedComplicationsPost(Request $request){

        $validator = Validator::make($request->all(), [
            'Comment' => 'required'
        ]);

        if($validator->fails()){
            return redirect("/CoFighter/doctor/reported-complications/more/$request->id")->withErrors($validator)->with('error_message','Field is required.');
        }

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $data = ReportedComplication::find($request->id);

        if(!$data){
            return redirect("/CoFighter/doctor/reported-complications/more/$request->id")->with('error_message','Your request could not be completed');
        }

        $data->admin_id = $user_id;
        $data->comment = $request->Comment;
        $data->status = 'ATTENDED';
        $data->save();

        $notificationData = (object)[
            'message' => $request->Comment." Message from $data->center_name",
            'email' => $data->email,
            'name' => $data->firstname.' '.$data->surname
        ];

        //dispatch(new SendUserMessage($notificationData));

        return redirect("/CoFighter/doctor/reported-complications/more/$request->id")->with('message','Message send successfully.');
    }

    public function moreReportedCases($id)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Inalid Request.');
            return redirect('/CoFighter/doctor/dashboard');
        }

        $user = ManageUser::GetUserData($user_id);

        $case = ReportedCases::find($id);

        return view("Doctors.reported-cases-more",['user'=>$user,'case'=>$case]);
    }

    public function moreReportedComplications($id)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Inalid Request.');
            return redirect('/CoFighter/doctor/dashboard');
        }

        $user = ManageUser::GetUserData($user_id);

        $complication = ReportedComplication::find($id);

        return view("Doctors.reported-complication-more",['user'=>$user,'complication'=>$complication]);
    }

    public function statusReportedCases($id)
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        if(!$id){
            Session::put('error_message','Inalid Request.');
            return redirect('/CoFighter/doctor/dashboard');
        }

        $case = ReportedCases::find($id);

        if(!$case){
            return redirect('/CoFighter/doctor/dashboard')->with('error_message','Invalid ID');
        }

        if($case->status == 'DEATH'){
            return redirect('/CoFighter/doctor/dashboard')->with('error_message','You can not changed the status of the dead.');
        }

        $user = ManageUser::GetUserData($user_id);

        return view("Doctors.reported-cases-status",['user'=>$user,'case'=>$case]);
    }

    public function reportedCasesStatusPost(Request $request){

        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()){
            return redirect("/CoFighter/doctor/reported-cases/status/$request->id")->withErrors($validator)->withInput();
        }

        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $data = ReportedCases::find($request->id);

        if(!$data){
            return redirect("/CoFighter/doctor/reported-cases/status/$request->id")->withInput()->with('error_message','Your request could not be completed');
        }

        $Comment = null;
        $data->status = $request->status;
        if($request->Comment){
            $data->details = $request->Comment;
            $Comment = $request->Comment;
        }
        $data->save();

        if($request->status == 'NEGATIVE'){
            $data2 = Cases::where('case_id', $data->case_id)->where('case_type',4)->first();
            if(!$data2){
                $cases = new Cases();
                $cases->user_id = $data->id;
                $cases->case_id = $data->case_id;
                $cases->user_name = $data->surname.' '.$data->firstname;
                $cases->number = 1;
                $cases->state = $user->state;
                $cases->center_id = $user->center_id;
                $cases->center_name = $user->center_name;
                $cases->case_type = 4;
                $cases->save();
            }
        }

        if($request->status == 'POSITIVE'){
            $data2 = Cases::where('case_id', $data->case_id)->where('case_type',1)->first();
            if(!$data2){
                $cases = new Cases();
                $cases->user_id = $data->id;
                $cases->case_id = $data->case_id;
                $cases->user_name = $data->surname.' '.$data->firstname;
                $cases->number = 1;
                $cases->state = $user->state;
                $cases->center_id = $user->center_id;
                $cases->center_name = $user->center_name;
                $cases->case_type = 1;
                $cases->save();
            }
        }

        if($request->status == 'DEATH' || $request->status == 'DISCHARGED'){
            $data1 = Cases::where('case_id', $data->case_id)->where(function ($query){
                $query->Where('case_type', 3)
                ->orWhere('case_type', 2);
            });
            $data1 = $data1->first();
            if($data1){
                if($request->status =='DEATH'){
                    $data1->case_type = 3;
                }
                if($request->status == 'DISCHARGED'){
                    $data1->case_type = 2;
                }
                $data1->save();
            }else{
                $cases = new Cases();
                $cases->user_id = $data->id;
                $cases->case_id = $data->case_id;
                $cases->user_name = $data->surname.' '.$data->firstname;
                $cases->number = 1;
                $cases->is_new = 0;
                $cases->state = $user->state;
                $cases->center_id = $user->center_id;
                $cases->center_name = $user->center_name;
                if($request->status =='DEATH'){
                    $cases->case_type = 3;
                }
                if($request->status == 'DISCHARGED'){
                    $cases->case_type = 2;
                }
                $cases->save();
            }
        }

        $message = '';

        if($data->status != 'DEATH'){
            if($data->status == 'NEGATIVE'){
                $message = "After due consultation and test, we can now conclude that you are $data->status. Continue statying safe. $Comment";
            }

            if($data->status == 'POSITIVE'){
                $message = "After due consultation and test, we can now conclude that you are $data->status. Please Isolate yourself and await further directives. $Comment";
            }

            if($data->status == 'DISCHARGED'){
                $message = "After due consultation and test, we can now conclude that you are $data->status. Continue statying safe. $Comment";
            }

            $notificationData = (object)[
                'message' => $message,
                'email' => $data->email,
                'name' => $data->firstname.' '.$data->surname
            ];

            dispatch(new SendUserMessage($notificationData));

        }

        return redirect("/CoFighter/doctor/reported-cases/status/$request->id")->with('message','Status updated successfully.');
    }

    public function addCasePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required',
            'firstname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'is_vaccinated' => 'required',
            'city' => 'required',
            'address' => 'required',
            'sickness_type' => 'required'
        ]);

        if($validator->fails()){
            return redirect("/CoFighter/doctor/cases/add")->withErrors($validator)->withInput();
        }

        $phone = ManageUser::ValidatePhoneToNational($request->phone, 'NG');

        if(!$phone){
            return redirect("/CoFighter/doctor/cases/add")->withInput()->with('error_message','Phone number is invalid. Please enter national format.');
        }

        $user = new ReportedCases();
        $user->surname = $request->surname;
        $user->case_id = 'CASE-'.ManageUser::NewUserCode();
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->type = 1;
        $user->email = $request->email;
        $user->is_vaccinated = $request->is_vaccinated;
        $user->address = $request->address;
        $user->city = $request->city;
        if($request->details){
            $user->details = $request->details;
        }
        $user->case_type = $request->sickness_type;

        $userData = ManageUser::GetUserData();
        if(!$userData){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }
        $user->center_id = $userData->center_id;
        $user->center_name = $userData->center_name;
        $user->state = $userData->state;
        $user->save();

        return redirect("/CoFighter/doctor/cases/add")->with('message','Case added successfully.');
    }

    public function positiveCases()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('status','POSITIVE')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->get();

        return view("Doctors.positive-cases",['user'=>$user,'cases'=>$cases]);
    }

    public function negativeCases()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('status','NEGATIVE')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->get();

        return view("Doctors.negative-cases",['user'=>$user,'cases'=>$cases]);
    }

    public function deathCases()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('status','DEATH')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->get();

        return view("Doctors.death-cases",['user'=>$user,'cases'=>$cases]);
    }

    public function dischargedCases()
    {
        $user_id = ManageUser::GetUserId();

        if(!$user_id){
            Session::put('message','Session expired. Login to continue.');
            return redirect('/CoFighter/login');
        }

        $user = ManageUser::GetUserData($user_id);

        $cases = ReportedCases::where('status','DISCHARGED')->where('center_id',$user->center_id)->where('center_name',$user->center_name)->where('state',$user->state)->get();

        return view("Doctors.discharged-cases",['user'=>$user,'cases'=>$cases]);
    }
}
