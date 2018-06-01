<?php namespace Anomaly\UsersModule\User\Login;

use Anomaly\UsersModule\User\UserAuthenticator;
use Anomaly\UsersModule\User\UserSecurity;
use Illuminate\Routing\Redirector;
use Sbweb\AttendanceModule\AbsentReason\Form\AbsentReasonFormBuilder;
use Sbweb\AttendanceModule\Attendance\AttendanceModel;
use Sbweb\AttendanceModule\Attendance\Contract\AttendanceInterface;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Session;

/**
 * Class LoginFormHandler
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class LoginFormHandler
{

    /**
     * Handle the form.
     *
     * @param LoginFormBuilder  $builder
     * @param UserAuthenticator $authenticator
     * @param UserSecurity      $security
     * @param Redirector        $redirect
     */
    public function handle(
        LoginFormBuilder $builder,
        AbsentReasonFormBuilder $absent_form_builder,
        UserAuthenticator $authenticator,
        AttendanceModel $attendance,
        UserSecurity $security,
        Redirector $redirect
    ) {

        /*dd($builder);*/
        /**
         * If we don't have a user from
         * validation there there is more
         * to do yet! Let the form redirect.
         */
        if (!$user = $builder->getUser()) {
            return;
        }



        /*dd($user);*/

/*        UserModel {#2148 ▼
            #with: array:1 [▶]
            #guarded: array:1 [▶]
            #searchable: true
            #table: "users_users"
            #titleName: "display_name"
            #rules: array:16 [▶]
            #fields: array:16 [▶]
            #dates: array:5 [▶]
            #relationships: array:1 [▶]
            #stream: StreamModel {#2040 ▶}
            +timestamps: true
            #translationForeignKey: "entry_id"
            #hidden: array:2 [▶]
            #searchableAttributes: []
            #ttl: false
            #titleKey: "id"
            #observables: array:4 [▶]
            #cascades: []
            #restricts: []
            #cache: []
            #connection: "mysql"
            #primaryKey: "id"
            #keyType: "int"
            +incrementing: true
            #withCount: []
            #perPage: 15
            +exists: true
            +wasRecentlyCreated: false
  #attributes: array:23 [▶]
  #original: array:23 [▶]
  #changes: []
  #casts: []
  #dateFormat: null
  #appends: []
  #dispatchesEvents: []
  #relations: array:1 [▶]
  #touches: []
  #visible: []
  #fillable: []
  #translatedAttributes: []
  #scoutMetadata: []
  #forceDeleting: false
  #rememberTokenName: "remember_token"
}*/

/*dd($user);*/
        $response = $security->check($user);



        if ($response instanceof Response) {

            $authenticator->logout($user);

            $builder->setFormResponse($response);

            return;
        }

        //check attendance state of user


      // $validate_attendance = $authenticator->checkLoginValidation($user, $attendance);
        //dd(Session::get('absent_data'));

       // if($validate_attendance == true) {
            $authenticator->login($user, $builder->getFormValue('remember_me'));


        /*dd('check');*/

       // }
        //else{
          //  if(Session::get('absent_data')['source'] == "absent"){
                /*dd('absent');*/
               /* return redirect()->route('getAbsentreason');*/
                //$builder->setFormResponse(redirect('admin/absent-reason'));
                //$absent_form_builder->setFormResponse(redirect('admin/user/absent-reason'));
           // }
       // }

        $builder->setFormResponse($redirect->intended($builder->getFormOption('redirect', '/')));
    }


}


