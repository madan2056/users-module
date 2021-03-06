<?php namespace Anomaly\UsersModule;
use Anomaly\UsersModule\User\Password\AdminPasswordFormBuilder;
use Anomaly\UsersModule\User\Password\PasswordFormBuilder;
use Anomaly\UsersModule\UserType\Contract\UserTypeRepositoryInterface;
use Anomaly\UsersModule\UserType\UserTypeRepository;
use Anomaly\Streams\Platform\Model\Users\UsersUserTypesEntryModel;
use Anomaly\UsersModule\UserType\UserTypeModel;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Application\Event\ApplicationHasLoaded;
use Anomaly\Streams\Platform\Model\Users\UsersRolesEntryModel;
use Anomaly\Streams\Platform\Model\Users\UsersUsersEntryModel;
use Anomaly\UsersModule\Http\Middleware\AuthorizeControlPanel;
use Anomaly\UsersModule\Http\Middleware\AuthorizeModuleAccess;
use Anomaly\UsersModule\Http\Middleware\AuthorizeRoutePermission;
use Anomaly\UsersModule\Http\Middleware\AuthorizeRouteRoles;
use Anomaly\UsersModule\Http\Middleware\CheckSecurity;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\Role\RoleModel;
use Anomaly\UsersModule\Role\RoleRepository;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\UsersModule\User\Event\UserHasRegistered;
use Anomaly\UsersModule\User\Event\UserWasLoggedIn;
use Anomaly\UsersModule\User\Event\AttendanceValidate;
use Anomaly\UsersModule\User\Listener\SendNewUserNotifications;
use Anomaly\UsersModule\User\Listener\TouchLastActivity;
/*use Anomaly\UsersModule\User\Listener\TouchLastLogin;*/

use Sbweb\AttendanceModule\AbsentReason\Form\AbsentReasonFormBuilder;
use Sbweb\UserModule\User\ChangePassword\ChangePasswordFormBuilder;
use Sbweb\UserModule\User\Listener\TouchLastLogin;
use Sbweb\UserModule\User\Listener\TouchAttendance;

use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Anomaly\UsersModule\User\Password\ForgotPasswordFormBuilder;
use Anomaly\UsersModule\User\Password\ResetPasswordFormBuilder;
use Anomaly\UsersModule\User\Register\RegisterFormBuilder;
use Anomaly\UsersModule\User\UserModel;
use Anomaly\UsersModule\User\UserRepository;

/**
 * Class UsersModuleServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class UsersModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon plugins.
     *
     * @var array
     */
    protected $plugins = [
        UsersModulePlugin::class,
    ];

    /**
     * The module middleware.
     *
     * @var array
     */
    protected $middleware = [
        CheckSecurity::class,
        AuthorizeRouteRoles::class,
        AuthorizeModuleAccess::class,
        AuthorizeControlPanel::class,
        AuthorizeRoutePermission::class,
    ];

    /**
     * The addon event listeners.
     *
     * @var array
     */
    protected $listeners = [
        UserWasLoggedIn::class      => [
            TouchLastLogin::class,
        ],
        AttendanceValidate::class => [
            TouchAttendance::class,
        ],
        UserHasRegistered::class    => [
            SendNewUserNotifications::class,
        ],
        ApplicationHasLoaded::class => [
            TouchLastActivity::class,
        ],
    ];

    /**
     * The class bindings.
     *
     * @var array
     */
    protected $bindings = [
        UsersUserTypesEntryModel::class => UserTypeModel::class,
        'login'                     => LoginFormBuilder::class,
        'register'                  => RegisterFormBuilder::class,
        'reset_password'            => ResetPasswordFormBuilder::class,
        'admin_change_password'           => AdminPasswordFormBuilder::class,
        'forgot_password'           => ForgotPasswordFormBuilder::class,
        'absent'                    => AbsentReasonFormBuilder::class,


        UsersUsersEntryModel::class => UserModel::class,
        UsersRolesEntryModel::class => RoleModel::class,
    ];

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
        UserTypeRepositoryInterface::class => UserTypeRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
    ];

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [

        'admin/users/user_types'           => 'Anomaly\UsersModule\Http\Controller\Admin\UserTypesController@index',
        'admin/users/user_types/create'    => 'Anomaly\UsersModule\Http\Controller\Admin\UserTypesController@create',
        'admin/users/user_types/edit/{id}' => 'Anomaly\UsersModule\Http\Controller\Admin\UserTypesController@edit',
        '/users/self'                        => [
            'as'   => 'anomaly.module.users::self',
            'uses' => 'Anomaly\UsersModule\Http\Controller\UsersController@self',
        ],
        '@{username}'                        => [
            'as'   => 'anomaly.module.users::users.view',
            'uses' => 'Anomaly\UsersModule\Http\Controller\UsersController@view',
        ],
        'login'                              => [
            'as'   => 'anomaly.module.users::login',
            'uses' => 'Anomaly\UsersModule\Http\Controller\LoginController@login',
        ],
        'logout'                             => [
            'as'   => 'anomaly.module.users::logout',
            'uses' => 'Anomaly\UsersModule\Http\Controller\LoginController@logout',
        ],
        'register'                           => [
            'as'   => 'anomaly.module.users::register',
            'uses' => 'Anomaly\UsersModule\Http\Controller\RegisterController@register',
        ],
        'users/activate'                     => [
            'as'   => 'anomaly.module.users::users.activate',
            'uses' => 'Anomaly\UsersModule\Http\Controller\RegisterController@activate',
        ],

        'users/change_password'             => 'Anomaly\UsersModule\Http\Controller\PasswordController@resetPassword',

        'users/reset/{id}'             => 'Anomaly\UsersModule\Http\Controller\PasswordController@reset',

        /*'users/password/reset'               => [
            'as'   => 'anomaly.module.users::users.reset',
            'uses' => 'Anomaly\UsersModule\Http\Controller\PasswordController@reset',
        ],*/
        'users/password/forgot'              => [
            'as'   => 'anomaly.module.users::password.forgot',
            'uses' => 'Anomaly\UsersModule\Http\Controller\PasswordController@forgot',
        ],
        'users/absent'              => [
            'as'   => 'anomaly.module.users::users.absent',
            'uses' => 'Anomaly\UsersModule\Http\Controller\ValidateAttendanceController@absent',
        ],

        'admin'                              => 'Anomaly\UsersModule\Http\Controller\Admin\HomeController@index',


        'auth/login'                         => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@logout',



        'auth/logout'                        => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@logout',
        'admin/login'                        => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@login',



        'admin/absent_reason'              => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@getAbsentReason',

        'admin/logout'                       => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@logout',
        'admin/users'                        => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@index',
        'admin/users/create'                 => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@create',
        'admin/users/edit/{id}'              => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@edit',
        'admin/users/view/{id}'              => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@view',
        'admin/users/delete/{id}'            => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@delete',
        'admin/users/permissions/{id}'       => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@permissions',
        'admin/users/impersonate/{id}'       => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@impersonate',
        'admin/users/activate/{id}'          => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@activate',
        'admin/users/deactivate/{id}'        => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@deactivate',
        'admin/users/block/{id}'             => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@block',
        'admin/users/unblock/{id}'           => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@unblock',
        'admin/users/logout/{id}'            => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@logout',


        //'admin/users/reset/{id}'             => 'Anomaly\UsersModule\Http\Controller\Admin\UsersController@reset',
        'admin/users/roles'                  => 'Anomaly\UsersModule\Http\Controller\Admin\RolesController@index',
        'admin/users/roles/create'           => 'Anomaly\UsersModule\Http\Controller\Admin\RolesController@create',
        'admin/users/roles/edit/{id}'        => 'Anomaly\UsersModule\Http\Controller\Admin\RolesController@edit',
        'admin/users/roles/permissions/{id}' => 'Anomaly\UsersModule\Http\Controller\Admin\RolesController@permissions',
        'admin/users/fields'                 => 'Anomaly\UsersModule\Http\Controller\Admin\FieldsController@index',
        'admin/users/fields/choose'          => 'Anomaly\UsersModule\Http\Controller\Admin\FieldsController@choose',
        'admin/users/fields/create'          => 'Anomaly\UsersModule\Http\Controller\Admin\FieldsController@create',
        'admin/users/fields/edit/{id}'       => 'Anomaly\UsersModule\Http\Controller\Admin\FieldsController@edit',


        'admin/absent-reason'           => [
            'as' => 'getAbsentreason',
            'uses' => 'Anomaly\UsersModule\Http\Controller\Admin\LoginController@getAbsentReason',
        ],

    ];
}

