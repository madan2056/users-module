<?php namespace Anomaly\UsersModule\User\Validation;

/*use Anomaly\UsersModule\User\Contract\UserInterface;*/
use Sbweb\UserModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Anomaly\UsersModule\User\UserAuthenticator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ValidateCredentials
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class ValidateCredentials
{

    /**
     * Handle the validation.
     *
     * @param  UserAuthenticator $authenticator
     * @param  LoginFormBuilder $builder
     * @return bool
     */
    public function handle(UserAuthenticator $authenticator, LoginFormBuilder $builder)
    {

        if (!$response = $authenticator->authenticate($builder->getPostData())) {
            return false;
        }




        if ($response instanceof UserInterface) {
            $builder->setUser($response);
        }

        /*dd($builder);*/
        /*dd($builder);*/

    /*    LoginFormBuilder {#1953 ▼
        #model: null
        #user: UserModel {#2080 ▶}
        #actions: array:1 [▶]
        #options: array:3 [▶]
        #ajax: false
        #handler: "Anomaly\UsersModule\User\Login\LoginFormHandler@handle"
        #validator: "Anomaly\Streams\Platform\Ui\Form\FormValidator"
        #repository: null
        #entry: null
        #fields: array:3 [▶]
        #skips: []
        #rules: []
        #buttons: []
        #sections: []
        #assets: []
        #save: true
        #readOnly: false
        #form: Form {#1954 ▶}
        #callbacks: []
    }*/


        if ($response instanceof Response) {
            $builder->setFormResponse($response);
        }


        return true;
    }
}
