<?php namespace Anomaly\UsersModule\UserType;

use Anomaly\UsersModule\UserType\Contract\UserTypeRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class UserTypeRepository extends EntryRepository implements UserTypeRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var UserTypeModel
     */
    protected $model;

    /**
     * Create a new UserTypeRepository instance.
     *
     * @param UserTypeModel $model
     */
    public function __construct(UserTypeModel $model)
    {
        $this->model = $model;
    }
}
