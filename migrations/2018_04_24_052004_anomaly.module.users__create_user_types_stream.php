<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class AnomalyModuleUsersCreateUserTypesStream extends Migration
{

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug' => 'user_types',
         'title_column' => 'title',
         'translatable' => false,
         'trashable' => false,
         'searchable' => false,
         'sortable' => false,
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'title' => [
            'required' => true,
        ],
        'description',
        'department' => [
            'required' => true,
        ],
        'user_level' => [
            'required' => true,
        ],
        'status' => [
            'required' => true,
        ]
    ];

}
