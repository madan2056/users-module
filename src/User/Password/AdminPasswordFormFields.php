<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/30/2018
 * Time: 6:25 PM
 */

namespace Anomaly\UsersModule\User\Password;


class AdminPasswordFormFields
{
    public function handle(AdminPasswordFormBuilder $builder)
    {
        $builder->setFields(
            [
                [
                    'field'      => 'password',
                    'type'       => 'anomaly.field_type.text',
                    'label'      => 'Password',
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
                    'label'    => 'Confirm Password',
                    'required' => true,
                    'config'   => [
                        'type' => 'password',
                    ],
                ],
            ]
        );
    }
}