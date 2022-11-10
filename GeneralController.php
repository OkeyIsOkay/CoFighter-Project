<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\General\General;
use App\Classes\User\ManageUser;
use App\Models\UserSettings;
use App\Models\ReportedCases;
use App\Models\ReportedComplication;
use App\Models\BookVaccination;
use App\Models\Cases;

class GeneralController extends Controller
{
    public function reportCases(Request $request)
    {

        if(!$request->surname){
            return General::ErrorResponse('User surname cannot be empty.');
        }

        if(!$request->firstname){
            return General::ErrorResponse('User firstname cannot be empty.');
        }

        if(!$request->phone){
            return General::ErrorResponse('User Phone cannot be empty.');
        }

        if(!$request->email){
            return General::ErrorResponse('User Email cannot be empty.');
        }

        if(!$request->address){
            return General::ErrorResponse('User address cannot be empty.');
        }

        if(!$request->city){
            return General::ErrorResponse('User city cannot be empty.');
        }

        if(!$request->state){
            return General::ErrorResponse('User state cannot be empty.');
        }

        if(!$request->center){
            return General::ErrorResponse('Choose a center to report case to.');
        }

        $phone = ManageUser::ValidatePhoneToNational($request->phone, 'NG');

        if(!$phone){
            return General::ErrorResponse('Phone number is invalid. Please enter national format.');
        }

        $user = new ReportedCases();
        $user->surname = $request->surname;
        $user->case_id = 'CASE-'.ManageUser::NewUserCode();
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->symptoms = $request->symptoms;
        $user->is_vaccinated = $request->is_vaccinated;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->details = $request->details;
        $user->case_type = $request->case_type;

        if($request->reporter_phone){
            $user->reporter_phone = $request->reporter_phone;
        }

        if($request->reporter_address){
        $user->reporter_address = $request->reporter_address;
        }

        if($request->reporter_name){
            $user->reporter_name = $request->reporter_name;
        }

        $user->center_id = $request->center;
        $user->center_name = ManageUser::GetCenterName($request->center);
        $user->state = $request->state;
        $user->save();

        return General::SuccessResponse('Case reported successfully. You will be contacted shortly.', $user);
    }

    public function reportComplications(Request $request)
    {

        if(!$request->surname){
            return General::ErrorResponse('User surname cannot be empty.');
        }

        if(!$request->firstname){
            return General::ErrorResponse('User firstname cannot be empty.');
        }

        if(!$request->state){
            return General::ErrorResponse('User state cannot be empty.');
        }

        if(!$request->phone){
            return General::ErrorResponse('User phone cannot be empty.');
        }

        if(!$request->email){
            return General::ErrorResponse('User Email cannot be empty.');
        }

        if(!$request->address){
            return General::ErrorResponse('User address cannot be empty.');
        }

        if(!$request->age){
            return General::ErrorResponse('User age cannot be empty.');
        }

        if(!$request->center){
            return General::ErrorResponse('Choose a center to report case to.');
        }

        if(!$request->date_vaccinated){
            return General::ErrorResponse('Date vaccinated cannot be empty.');
        }

        if(!$request->symptoms){
            return General::ErrorResponse('Please let us know the symptoms.');
        }

        $user = new ReportedComplication();
        $user->surname = $request->surname;
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->date_vaccinated = date('Y-m-d', strtotime($request->date_vaccinated));
        $user->age = $request->age;
        $user->symptoms = $request->symptoms;
        $user->center_id = $request->center;
        $user->center_name = ManageUser::GetCenterName($request->center);
        $user->state = $request->state;
        $user->save();

        return General::SuccessResponse('Complication Case reported successfully. You will be contacted shortly via your email.', $user);
    }

    public function bookVaccination(Request $request)
    {
        if(!$request->surname){
            return General::ErrorResponse('User surname cannot be empty.');
        }

        if(!$request->firstname){
            return General::ErrorResponse('User firstname cannot be empty.');
        }

        if(!$request->phone){
            return General::ErrorResponse('User phone cannot be empty.');
        }

        if(!$request->email){
            return General::ErrorResponse('User email cannot be empty.');
        }


        if(!$request->preferred_state){
            return General::ErrorResponse('Let us know your preferred state.');
        }

        if(!$request->center){
            return General::ErrorResponse('Choose a center.');
        }

        if(!$request->date){
            return General::ErrorResponse('Enter your preferred vaccination date');
        }

        $user = new BookVaccination();
        $user->vaccination_id = 'BOOKING-'.ManageUser::NewUserCode();
        $user->surname = $request->surname;
        $user->firstname = $request->firstname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->is_sick = $request->is_sick;
        $user->is_vaccinated = $request->is_vaccinated;
        $user->prefered_center_id = $request->center;
        $user->prefered_center_name = ManageUser::GetCenterName($request->center);
        $user->prefered_state = $request->preferred_state;
        $user->preferred_date = date('Y-m-d', strtotime($request->date));
        $user->save();

        return General::SuccessResponse('Booking done. You will be contacted shortly.', $user);
    }

    public function vaccinationStatus(Request $request)
    {
        if(!$request->vaccination_id){
            return General::ErrorResponse('Vaccination ID cannot be empty.');
        }

        $data = BookVaccination::where('vaccination_id', $request->vaccination_id)->first();

        if(!$data){
            return General::ErrorResponse('No record found for this ID.');
        }

        if($data->status == 'PENDING'){
            return General::SuccessResponse($data->surname.' '.$data->firstname.' is not vaccinated.',[$data->status],1);
        }

        if($data->status == 'VACCINATED'){
            return General::SuccessResponse($data->surname.' '.$data->firstname.' has been vaccinated.',[$data->status],0);
        }
    }

    public function states(Request $request)
    {
        $data = General::states();
        return General::SuccessResponse('Here you go.',$data);
    }

    public function centers($id = 0)
    {
        $data = General::centers($id);

        return General::SuccessResponse('Here you go.',$data);
    }

    public function getCases11(Request $request)
    {
        $state = $request->state;
        $center_id = $request->center_id;

        $data = Cases::where('is_enabled', 1);

        if($state){
            $data = $data->where('state',$state);
        }

        if($center_id){
            $data = $data->where('center_id',$center_id);
        }

        $data = $data->get();

        $postives = $data->WhereIn('is_new', [true]);
        $positive = $postives->sum('number');
        $discharge = $data->WhereIn('case_type', [2]);
        $discharge = $discharge->sum('number');
        $death = $data->WhereIn('case_type', [3]);
        $death = $death->sum('number');
        $active = $positive - ($discharge + $death);

        $todaysCases = $this->getTodaysCases($request);

        $response = (object)[
            'all'=>$positive,
            'active'=>$active,
            'discharge'=>$discharge,
            'death'=>$death,
            'today_all'=>$todaysCases->all,
            'today_active'=>$todaysCases->active,
            'today_discharge'=>$todaysCases->discharge,
            'today_death'=>$todaysCases->death
        ];

        return General::SuccessResponse('Here you go.', $response);
    }

    public function getCases(Request $request)
    {
        $state = $request->state;
        $center_id = $request->center_id;

        $data = Cases::where('is_enabled', 1);

        if($state){
            $data = $data->where('state',$state);
        }

        if($center_id){
            $data = $data->where('center_id',$center_id);
        }

        $data = $data->get();

        $postives = $data->WhereIn('case_type', [1]);
        $positive = $postives->sum('number');
        $discharge = $data->WhereIn('case_type', [2]);
        $discharge = $discharge->sum('number');
        $death = $data->WhereIn('case_type', [3]);
        $death = $death->sum('number');
        $active = $positive - ($discharge + $death);

        $todaysCases = $this->getTodaysCases($request);

        $response = (object)[
            'all'=>$positive,
            'active'=>$active,
            'discharge'=>$discharge,
            'death'=>$death,
            'today_all'=>$todaysCases->all,
            'today_active'=>$todaysCases->active,
            'today_discharge'=>$todaysCases->discharge,
            'today_death'=>$todaysCases->death
        ];

        return General::SuccessResponse('Here you go.', $response);
    }

    public function getTodaysCases(Request $request){

        $state = $request->state;
        $center_id = $request->center_id;

        $data = Cases::where('is_enabled', 1);

        if($state){
            $data = $data->where('state',$state);
        }

        if($center_id){
            $data = $data->where('center_id',$center_id);
        }

        $data = $data->whereDate('created_at',date('Y-m-d'));

        $data = $data->get();

        $postives = $data->WhereIn('case_type', [1]);
        $positive = $postives->sum('number');
        $discharge = $data->WhereIn('case_type', [2]);
        $discharge = $discharge->sum('number');
        $death = $data->WhereIn('case_type', [3]);
        $death = $death->sum('number');
        $active = $positive - ($discharge + $death);

        $response = (object)[
            'all'=>$positive,
            'active'=>$active,
            'discharge'=>$discharge,
            'death'=>$death
        ];

        return $response;
    }

}

