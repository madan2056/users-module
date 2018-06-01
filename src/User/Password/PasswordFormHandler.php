<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/30/2018
 * Time: 5:22 PM
 */

namespace Anomaly\UsersModule\User\Password;


use Anomaly\UsersModule\User\UserPassword;
use Sbweb\UserModule\User\Contract\UserRepositoryInterface;

use Anomaly\Streams\Platform\Message\MessageBag;
use Illuminate\Contracts\Config\Repository;
use \Session;
use Illuminate\Validation\Validator;

class PasswordFormHandler
{
    public function handle(
        PasswordFormBuilder $builder,
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

        if(Session::has('user_id')){
            Session::forget('user_id');
        }

        //dd(\Session::get('user_id'));
    //dd(\Session::get('user_id'));
        if(\Session::get('user_id') == ""){
            //return redirect('/admin/login')->send();
            $messages->error('check');
            return;
        }






        //$users = $users->findById();
    }
}