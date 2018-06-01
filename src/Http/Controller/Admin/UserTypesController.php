<?php namespace Anomaly\UsersModule\Http\Controller\Admin;

use Anomaly\UsersModule\UserType\Form\UserTypeFormBuilder;
use Anomaly\UsersModule\UserType\Table\UserTypeTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class UserTypesController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param UserTypeTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserTypeTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param UserTypeFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(UserTypeFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param UserTypeFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(UserTypeFormBuilder $form, $id)
    {
        return $form->render($id);
    }
}
