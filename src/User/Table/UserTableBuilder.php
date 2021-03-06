<?php namespace Anomaly\UsersModule\User\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Anomaly\UsersModule\User\Table\Filter\StatusFilterQuery;
use Anomaly\UsersModule\User\Table\View\OnlineQuery;

/**
 * Class UserTableBuilder
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class UserTableBuilder extends TableBuilder
{

    /**
     * The table views.
     *
     * @var array
     */
    protected $views = [
        'all',
        'online' => [
            'query'   => OnlineQuery::class,
            'text'    => 'anomaly.module.users::view.online',
            'columns' => [
                'entry.last_activity_at.diffForHumans()',
                'display_name',
                'username',
                'email',
            ],
        ],
        'trash',
    ];

    /**
     * The table actions.
     *
     * @var array
     */
    public $actions = [
        'delete',
    ];

    /**
     * The table filters.
     *
     * @var array
     */
    protected $filters = [
        'search' => [
            'filter' => 'search',
            'fields' => [
                'display_name',
                'username',
                'email',
            ],
        ],
        'roles',
        'status' => [
            'filter'  => 'select',
            'query'   => StatusFilterQuery::class,
            'options' => [
                'active'   => 'anomaly.module.users::field.status.option.active',
                'inactive' => 'anomaly.module.users::field.status.option.inactive',
                'disabled' => 'anomaly.module.users::field.status.option.disabled',
            ],
        ],
    ];

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'display_name',
        'username',
        'email',
        'status' => [
            'value' => 'entry.status_label',
        ],
    ];

    /**
     * The table buttons.
     *
     * @var array
     */
    protected $buttons = [
        'edit',
        'settings' => [
            'text'     => false,
            'href'     => false,
            'dropdown' => [
                'view'        => [
                    'icon'   => null,
                    'target' => '_blank',
                    'text'   => 'anomaly.module.users::button.view_profile',
                ],
                'permissions' => [
                    'button' => 'info',
                    'href'   => 'admin/users/permissions/{entry.id}',
                ],
                'impersonate' => [
                    'text'       => 'anomaly.module.users::button.login_as_user',
                    'permission' => 'anomaly.module.users::users.impersonate',
                ],
                'reset'       => [
                    'text'       => 'anomaly.module.users::button.reset_password',
                    'permission' => 'anomaly.module.users::users.reset',
                    'attributes' => [
                        'data-toggle'  => 'confirm',
                        'data-message' => 'anomaly.module.users::message.confirm_reset_user',
                    ],
                ],
            ],
        ],
    ];

}
