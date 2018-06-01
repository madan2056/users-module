<?php namespace Anomaly\UsersModule\User\Password;

use Anomaly\UsersModule\User\Validation\ValidatePassword;

/**
 * Class ResetPasswordFormFields
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ResetPasswordFormFields
{

    /**
     * Handle the fields.
     *
     * @param ResetPasswordFormBuilder $builder
     */
    public function handle(ResetPasswordFormBuilder $builder)
    {
        $request_uri = app('request')->getRequestUri();


        $full_url = explode('/',$request_uri);
        $token = $full_url[3];



        /*dd('here');*/


    /*    if (!$builder->getEmail()) {
            $builder->addField(
                'email',
                [
                    'type'     => 'anomaly.field_type.email',
                    'label'    => 'anomaly.module.users::field.email.name',
                    'required' => true,
                ]
            );
        }

        if (!$builder->getCode()) {
            $builder->addField(
                'code',
                [
                    'type'     => 'anomaly.field_type.text',
                    'label'    => 'anomaly.module.users::field.reset_code.name',
                    'required' => true,
                ]
            );
        }*/

        $builder->setFields(
            [
                [
                    'field'      => 'password',
                    'type'       => 'anomaly.field_type.text',
                    'label'      => 'anomaly.module.users::field.password.name',
                    'required'   => true,
                    'rules'      => [
                        'confirmed',
                    ],
                    'config'     => [
                        'type' => 'password',
                    ],
                ],
                [
                    'field'    => 'password_confirmation',
                    'type'     => 'anomaly.field_type.text',
                    'label'    => 'anomaly.module.users::field.confirm_password.name',
                    'required' => true,
                    'config'   => [
                        'type' => 'password',
                    ],
                ],
                [
                    'field'  => 'reset_token',
                    'type'   => 'anomaly.field_type.text',
                    'hidden' => true,
                    'config' => [
                        'default_value' => $token,
                    ]
                ]
            ]
        );

    }
}
