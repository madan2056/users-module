<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/30/2018
 * Time: 5:14 PM
 */

namespace Anomaly\UsersModule\User\Password;


class PasswordFormFields
{
    public function handle(PasswordFormBuilder $builder)
    {
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
            ]
        );
    }
}