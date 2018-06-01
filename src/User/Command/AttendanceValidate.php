<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/7/2018
 * Time: 4:07 PM
 */

namespace Anomaly\UsersModule\User\Command;


use Sbweb\UserModule\User\Contract\UserInterface;

class AttendanceValidate
{

    /**
     * The user object.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Create a new UserWasLoggedIn instance.
     *
     * @param UserInterface $user
     */
    function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}