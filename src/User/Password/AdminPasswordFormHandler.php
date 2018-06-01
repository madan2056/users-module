<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/30/2018
 * Time: 6:26 PM
 */

namespace Anomaly\UsersModule\User\Password;

use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Anomaly\UsersModule\User\UserPassword;
use Sbweb\UserModule\User\Contract\UserRepositoryInterface;

use Anomaly\Streams\Platform\Message\MessageBag;
use Illuminate\Contracts\Config\Repository;
use \Session;
use Illuminate\Validation\Validator;
class AdminPasswordFormHandler
{
    public function handle(
        AdminPasswordFormBuilder $builder,
        LoginFormBuilder $loginbuilder,
        UserRepositoryInterface $users,
        UserPassword $password,
        MessageBag $messages,
        Repository $config
    )
    {
        if ($builder->hasFormErrors()) {
            return;
        }

        $factory = app('validator');
        //session()->forget('user_id');

        //session()->flush();

        /*if(Session::has('user_id')){
            Session::forget('user_id');
        }*/

        //dd(\Session::get('user_id'));
        //dd(\Session::get('user_id'));
        if(\Session::get('user_id') == ""){

            //return redirect('/admin/login')->send();
            $messages->success($loginbuilder->getFormOption('success_message','<p class="change">User not set</p>'));
            return;
        }
        else{
            $users = $users->findById(\Session::get('user_id'));
            $password->change($users,$builder);
            $messages->success($builder->getFormOption('success_message','<p class="change">Password change Successfully!</p>'));
        }






        //$users = $users->findById();
    }
}