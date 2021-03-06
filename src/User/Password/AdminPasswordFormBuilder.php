<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/30/2018
 * Time: 6:25 PM
 */

namespace Anomaly\UsersModule\User\Password;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Sbweb\UserModule\User\UserModel;

class AdminPasswordFormBuilder extends FormBuilder
{
    /**
     * No model.
     *
     * @var bool
     */
    protected $model = UserModel::class;

    /**
     * The form actions.
     *
     * @var array
     */
    protected $actions = [
        'submit',
    ];

    protected $options = [
        'redirect' => '/',
    ];
}