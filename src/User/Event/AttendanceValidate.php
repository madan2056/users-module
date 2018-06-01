<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/7/2018
 * Time: 4:14 PM
 */

namespace Anomaly\UsersModule\User\Event;


use Sbweb\UserModule\User\Contract\UserInterface;

class AttendanceValidate
{
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