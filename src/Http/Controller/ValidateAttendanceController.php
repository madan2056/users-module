<?php
/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 5/8/2018
 * Time: 10:37 AM
 */

namespace Anomaly\UsersModule\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Illuminate\Translation\Translator;


class ValidateAttendanceController extends PublicController
{
    public function absent(Translator $translator)
    {
        /*dd('die here');*/
        $this->template->set(
            'meta_title',
            $translator->trans('anomaly.module.users::breadcrumb.login')
        );

        return $this->view->make('anomaly.module.users::absent');

    }
}