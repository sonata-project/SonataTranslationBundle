Translate your custom actions in SonataAdmin
============================================

As backend project are not only made of CRUDs, we have to make custom actions.

This bundle allow you to easily add a locale switcher block in your template and will handle your object translation
if you respect Sonata standard structure.

How to make custom actions with Sonata standard structure
---------------------------------------------------------

At this point you already have a working admin in your project.

In this example, we have a ``QuestionnaireAdmin`` setup with a ``Questionnaire`` entity translatable. We will add a new action
to display the list of the current questionnaire's questions with their possible answers. (Note that questions and anwsers are translatable too).

1 / Add new route to your Admin
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // src/AppBundle/Admin/QuestionnaireAdmin.php
    
    /**
     * @author Nicolas Bastien <nbastien@prestaconcept.net>
     */
    class QuestionnaireAdmin extends AbstractAdmin
    {
        /**
         * {@inheritdoc}
         */
        protected function configureRoutes(RouteCollection $collection)
        {
            $collection
                ->add('show_question_answer', sprintf('%s/show_question_answer', $this->getRouterIdParameter()))
            ;
        }
        ...
    }


.. note::

    Add a link to this new actions. For example in your list screen. + check your user rights so he can reach it.


2 / Create a custom controller with this actions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First update your admin configuration to point to a custom controller :

.. code-block:: xml

    <service id="admin.questionnaire" class="AppBundle\Admin\QuestionnaireAdmin" parent="admin.abstract">
        <tag name="sonata.admin" manager_type="orm" group="user" label="dashboard.label_questionnaire"/>

        <argument />
        <argument>AppBundle\Entity\Questionnaire</argument>
        <argument>AppBundle:Admin/Questionnaire</argument>
    </service>

Then implement your controller. 

To benefit from Sonata powerful feature, we need to extend ``CRUDController`` and load our current
object the same way as Sonata does in edit or show action.

.. code-block:: php

    <?php
    // src/AppBundle/Controller/Admin/QuestionnaireController.php

    /**
     * @author Nicolas Bastien <nbastien@prestaconcept.net>
     */
    class QuestionnaireController extends CRUDController
    {
        /**
         * @param Request $request
         *
         * @return Response
         */
        public function showQuestionAnswerAction(Request $request)
        {
            $id = $request->get($this->admin->getIdParameter());
            /** @var Questionnaire $questionnaire */
            $questionnaire = $this->admin->getObject($id);
    
            if (!$questionnaire) {
                $id = $request->get($this->admin->getIdParameter());
                throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
            }
    
            return $this->render('admin/questionnaire/show_question_answer.html.twig, [
                'questionnaire'     => $questionnaire,
            ]);
        }    
    }

3 / Add locale switcher block
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

As we are implementing a 'show' actions type, your template should extends your admin layout and override the show block.
If you are working on an edit actions you should work with the edit block instead.

.. code-block:: jinja

    {% extends ':admin:layout.html.twig' %}

    {% block show %}

        {{ sonata_block_render({ 'type': 'sonata_translation.block.locale_switcher' }, {
            'admin': admin,
            'object': questionnaire,
            'locale_switcher_route': 'show_question_answer'
        }) }}
        
        ...
    {% endblock %}


At this point, you should have a working locale switcher in your actions.

.. note::
    
    You had noticed that I don't use $object variable in my custom action like it's the case in ``CRUDController``.
    This is made on purpose cause we are not in a generic action and if your actions manipulate several kind of objects
    you will notice that it's really meaningful to do this way.
    