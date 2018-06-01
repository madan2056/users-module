<?php namespace Anomaly\UsersModule\User;

use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\UsersModule\User\Authenticator\Contract\AuthenticatorExtensionInterface;
/*use Anomaly\UsersModule\User\Contract\UserInterface;*/

use Anomaly\UsersModule\User\Command\AttendanceValidate;
use Illuminate\Database\Query\Builder;
use Sbweb\AttendanceModule\Attendance\AttendanceModel;
use Sbweb\AttendanceModule\Attendance\Contract\AttendanceInterface;
use Sbweb\AttendanceModule\JobShift\JobShiftModel;
use Sbweb\UserModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Event\UserWasKickedOut;
use Anomaly\UsersModule\User\Event\UserWasLoggedIn;
use Anomaly\UsersModule\User\Event\UserWasLoggedOut;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\RedirectResponse;
use DB;
use AppHelper;
use Session;

/**
 * Class UserAuthenticator
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class UserAuthenticator
{

    /**
     * Laravel's authentication.
     *
     * @var Guard
     */
    protected $guard;

    /**
     * The event dispatcher.
     *
     * @var Dispatcher
     */
    protected $events;

    /**
     * The service container.
     *
     * @var Container
     */
    protected $container;

    /**
     * The extension collection.
     *
     * @var ExtensionCollection
     */
    protected $extensions;

    /**
     * Create a new Authenticator instance.
     *
     * @param Guard               $guard
     * @param Dispatcher          $events
     * @param Container           $container
     * @param ExtensionCollection $extensions
     */
    public function __construct(Guard $guard, Dispatcher $events, Container $container, ExtensionCollection $extensions)
    {
        $this->guard      = $guard;
        $this->events     = $events;
        $this->container  = $container;
        $this->extensions = $extensions;
    }

    /**
     * Attempt to login a user.
     *
     * @param  array $credentials
     * @param  bool  $remember
     * @return bool|UserInterface
     */
    public function attempt(array $credentials, $remember = false)
    {
        if ($response = $this->authenticate($credentials)) {

            if ($response instanceof UserInterface) {
                $this->login($response, $remember);
            }

            return $response;
        }

        return false;
    }

    /**
     * Attempt to authenticate the credentials.
     *
     * @param  array $credentials
     * @return bool|UserInterface
     */
    public function authenticate(array $credentials)
    {

        $authenticators = $this->extensions->search('anomaly.module.users::authenticator.*');
        /*dd($authenticators);*/
        /* @var AuthenticatorExtensionInterface $authenticator */
        foreach ($authenticators as $authenticator) {

            $response = $authenticator->authenticate($credentials);


            if ($response instanceof UserInterface) {
                return $response;
            }

            if ($response instanceof RedirectResponse) {
                return $response;
            }
        }
      //  die;

        return false;
    }

    /**
     * Force login a user.
     *
     * @param UserInterface $user
     * @param bool          $remember
     */
    public function login(UserInterface $user, $remember = false)
    {
        /*dd($user);*/
        /*$this->events->fire(new AttendanceValidate($user));*/

        /*echo $user->change_password_next_login;
        dd($user);*/
        /*
         * check change_password_next_login
         */

        if($user->change_password_next_login == 1){
            //
            /*dd('change');*/
            //echo "auth id is".auth()->user()->id;
            /*Session::put('user_id', $user->id);*/
            \Session::put('user_id',$user->id);
            return redirect('users/change_password')->send();

        }
        else {
            $this->guard->login($user, $remember);
            $this->events->fire(new UserWasLoggedIn($user));
        }
    }

    public function checkLoginValidation(UserInterface $user, AttendanceInterface $attendance)
    {
        /*dd($user);*/
        $cur_time_only = date('H:i');

        $last_attendance_sucess = $this->getUserLastSuccessAttendance($cur_time_only, $user);


        $last_attendance_sucess_calender = $last_attendance_sucess->office_calender_id;

        $current_calender = $this->getCurrentCalenderid();

        $diff = $current_calender - $last_attendance_sucess_calender;


        $total_absent_without_holiday = $this->countTotalAbsent($last_attendance_sucess_calender,$current_calender);


        if($total_absent_without_holiday > 1){
                //absent for more days
                //check leave form for that shift

            $total_leave_formal_informal_without_holiday = $this->getDateWithOutHoliday($last_attendance_sucess_calender,$current_calender);

               $user_jobshift_leave = DB::table('attendance_leave_form')
                                        ->join('attendance_attendance_leave_reason','attendance_attendance_leave_reason.leave_form_id','=','attendance_leave_form.id')
                                        ->where('attendance_leave_form.users_id',$last_attendance_sucess->users_id)
                                        ->select('attendance_attendance_leave_reason.absent_from as leave_absent_from','attendance_attendance_leave_reason.absent_to as leave_absent_to')
                                        ->get();

                foreach($user_jobshift_leave as $user_jobshift_leave_data){
                    $total_leave_form_without_holiday = $this->getDateDiffWithOutHoliday($user_jobshift_leave_data->leave_absent_from,$user_jobshift_leave_data->leave_absent_to);
                }



                $result = array_diff($total_leave_formal_informal_without_holiday, $total_leave_form_without_holiday);



                if(sizeof($result) > 0){
                    //mean uninformed attendance preset
                    $result = array_values($result);
                    $absent_data_arr['userid'] = $user->id;
                    $absent_data_arr['absent_calender_arr'] = $result;
                    $user_inf = DB::table('job_shifts')
                        ->join('user_topic_configs', 'user_topic_configs.job_shift_id', '=', 'job_shifts.id')
                        ->where('user_topic_configs.user_id', $user->id)
                        ->first();
                    $user_shift_office_start = $user_inf->time_from;
                    $absent_data_arr['usershift_start'] = $user_shift_office_start;
                    Session::put('absent_data', $absent_data_arr);
                    $this->checkDelay($user);
                    //return redirect()->route('getAbsentreason');
                    return redirect('admin/absent_reason')->send();
                }
                else{
                    //mean all are informed so procceed
                    //TODO:: all are informed so procceed
                    //$this->checkDelay();
                }

                
        }
        else{
            //$this->checkDelay();
            //not absent found
            //TODO:: no absent found
        }





        //

        //dd($last_attendance_sucess);
        //check which shift it lies at



        /*$cur_date_only = date('Y-m-d');
        $cur_time_only = date('H:i');
        $check = DB::table('attendance_attendance')
            ->join('office_calender_office_calender','office_calender_office_calender.id','attendance_attendance.office_calender_id')
            ->where('attendance_attendance.user_id', $user->getId())
            ->where('office_calender_office_calender.calender_date', $cur_date_only)
            ->first();
        if($check) {
            return true;
        }
        else{
            $get_latest_user_data = DB::table('attendance_attendance')
                ->join('office_calender_office_calender','office_calender_office_calender.id','attendance_attendance.office_calender_id')
                ->where('attendance_attendance.user_id', $user->getId())
                ->orderby('attendance_attendance.office_calender_id','desc')
                ->first();
            //check if absent in previous date
            if($get_latest_user_data) {
                $last_user_attendance = $get_latest_user_data->calender_date;
                $actual_day_diff = AppHelper::getDateDiff($cur_date_only, $last_user_attendance);
                if ($actual_day_diff > 0) {
                    //absent found so open form for reason
                    $absent_data_arr = [];
                    $absent_data_arr['absent_from'] = AppHelper::addDaysToDate($last_user_attendance);
                    $absent_data_arr['absent_to'] = AppHelper::subtractDaysToDate($cur_date_only);
                    $absent_data_arr['dayabsent'] = $actual_day_diff;
                    $absent_data_arr['userid'] = $user->getId();
                    $absent_data_arr['source'] = "absent";

                    Session::put('absent_data', $absent_data_arr);

                    return false;

                } else {
                    // absent not found so check time only
                }
            }
            else{
                $calender_info = DB::table('office_calender_office_calender')
                    ->where('calender_date', $cur_date_only)
                    ->first();
                $calender_date_id = $calender_info->id;
                $attendance->status = 1;
                $attendance->user_id = $user->getId();
                $attendance->office_calender_id = $calender_date_id;
                $attendance->in_time = $cur_time_only;
                $attendance->save();
                return true;
            }
        }*/

     /*   $cur_date_only = date('Y-m-d');
        $cur_time_only = date('H:i');
        $check = DB::table('attendance_attendance')
            ->join('office_calender_office_calender','office_calender_office_calender.id','attendance_attendance.office_calender_id')
            ->where('attendance_attendance.user_id', $user->getId())
            ->where('office_calender_office_calender.calender_date', $cur_date_only)
            ->first();
        if($check) {
            echo "login  found for today";
        }
        else{
            echo "login not found for today"
        }*/
    }

    public function checkDelay($user){
        $cur_date_only = date('Y-m-d');
        $cur_time_only = date('H:i');
        $user_info = DB::table('job_shifts')
            ->join('user_topic_configs', 'user_topic_configs.job_shift_id', '=', 'job_shifts.id')
            ->where('user_topic_configs.user_id', $user->id)
            ->first();
        //check if job_shift_start present in user_top_configs
        if ($user_info) {
            $user_shift_office_start = $user_info->time_from;
            //check here if late
            if ($cur_time_only > $user_shift_office_start) {
                //late
                $data_delay_arr = [];
                $data_delay_arr['userid'] = $user->id;
                $data_delay_arr['officearrival_time'] = $cur_time_only;
                Session::put('delay_data', $data_delay_arr);
            }

        }
    }

    public function getDateDiffWithOutHoliday($leave_from, $leave_to)
    {
        $leave_interval_without_holiday_array = [];
        for($j=$leave_from;$j<=$leave_to;$j++){
           //check the date
            $leave_date_interval_without_holiday = DB::table('office_calender_office_calender')
                                        ->where('calender_date',$j)
                                        ->where('is_holiday','0')
                                        ->first();
            if($leave_date_interval_without_holiday != "") {
                //array_push($leave_interval_without_holiday_array, $leave_date_interval_without_holiday);
                $leave_interval_without_holiday_array[] = $leave_date_interval_without_holiday->id;
            }
        }

        return $leave_interval_without_holiday_array;

    }

    public function getUserLastSuccessAttendance($current_time=null, $user=array())
    {
                    //user is assigned to that shift so get usershiftid
                    $get_last_attendance = DB::table('attendance_attendance')
                                        ->where('users_id',$user->id)
                                        ->where('status','1')
                                        ->orderby('id','desc')
                                        ->first();
                    return $get_last_attendance;

    }

    public function getDateWithOutHoliday($last_attendance_sucess_calender,$current_calender)
    {
        $date_without_holiday = [];
        for($i=$last_attendance_sucess_calender+1;$i<$current_calender;$i++){
            //check if that day is holiday in office calender or not
            $attendance_without_holiday = DB::table('office_calender_office_calender')
                ->where('id',$i)
                ->where('is_holiday','0')
                ->get()->toArray();
            foreach($attendance_without_holiday as $attendace_without_holiday_data){
                $date_without_holiday[] = $attendace_without_holiday_data->id;
            }

        }
        return $date_without_holiday;

    }

    public function countTotalAbsent($last_attendance_sucess_calender,$current_calender)
    {
        $total_absent_without_holiday = 0;
        for($i=$last_attendance_sucess_calender+1;$i<$current_calender;$i++){
            //check if that day is holiday in office calender or not
            $attendance_without_holiday = DB::table('office_calender_office_calender')
                ->where('id',$i)
                ->where('is_holiday','0')
                ->get()->toArray();
            foreach($attendance_without_holiday as $attendace_without_holiday_data){
                $total_absent_without_holiday += 1;
            }

        }
        return $total_absent_without_holiday;
    }

    public function getCurrentCalenderid()
    {
        $cur_date_only = date('Y-m-d');
        $cur_calender_info = DB::table('office_calender_office_calender')
                                ->where('calender_date',$cur_date_only)
                                ->first();

        return $cur_calender_info->id;
    }




    /**
     * Logout a user.
     *
     * @param UserInterface $user
     */
    public function logout(UserInterface $user = null)
    {
        if (!$user) {
            $user = $this->guard->user();
        }

        if (!$user) {
            return;
        }

        $this->guard->logout($user);

        $this->events->fire(new UserWasLoggedOut($user));
    }

    /**
     * Kick out a user. They've been bad.
     *
     * @param UserInterface $user
     */
    public function kickOut(UserInterface $user, $reason)
    {
        $this->guard->logout($user);

        $this->events->fire(new UserWasKickedOut($user, $reason));
    }
}
