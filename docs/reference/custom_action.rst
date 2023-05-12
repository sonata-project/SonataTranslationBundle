Translate your custom actions in SonataAdmin
============================================

As backend project are not only made of CRUDs, we have to make custom actions.

This bundle allow you to easily add a locale switcher block in your template and will handle your object translation
if you respect Sonata standard structure.

How to make custom actions with Sonata standard structure
---------------------------------------------------------

At this point you already have a working admin in your project.

In this example, we have a ``QuestionnaireAdmin`` setup with a ``Questionnaire`` entity translatable.
We will add a new action to display the list of the current questionnaire's questions with their possible answers.

.. note::

    Note that questions and anwsers are translatable too!

Add new route to your Admin
^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    // src/Admin/QuestionnaireAdmin.php

    use Sonata\AdminBundle\Route\RouteCollectionInterface;

    final class QuestionnaireAdmin extends AbstractAdmin
    {
        protected function configureRoutes(RouteCollectionInterface $collection): void
        {
            $collection
                ->add('show_question_answer', sprintf('%s/show_question_answer', $this->getRouterIdParameter()))
            ;
        }
    }

.. note::

    Add a link to your new action. For example in you list screen an check the user rights.

Create a custom controller with this actions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First update your admin configuration to point to a custom controller:

.. code-block:: yaml

    # config/services.yaml

    admin.questionnaire:
        class: App\Admin\QuestionnaireAdmin
        arguments: [~, App\Entity\Questionnaire, App:Admin/Questionnaire]
        tags:
            - { name: sonata.admin, manager_type: orm, label: 'dashboard.label_questionnaire' }

Then implement your controller.

To benefit from Sonata powerful feature, we need to extend the class ``CRUDController`` and load our current
object the same way as Sonata does in edit or show action::

    // src/Controller/Admin/QuestionnaireController.php

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class QuestionnaireController extends CRUDController
    {
        public function showQuestionAnswerAction(Request $request): Response
        {
            /** @var App\Entity\Questionnaire $questionnaire */
            $questionnaire = $this->admin->getSubject($id);

            if (!$questionnaire) {
                $id = $request->get($this->admin->getIdParameter());
                throw $this->createNotFoundException(sprintf(
                    'Unable to find the object with id : %s',
                    $id
                ));
            }

            return $this->render('admin/questionnaire/show_question_answer.html.twig', [
                'questionnaire' => $questionnaire,
            ]);
        }
    }

Add locale switcher block
^^^^^^^^^^^^^^^^^^^^^^^^^

As we are implementing a 'show' actions type, your template should extend your admin layout and override the show block.
If you are working on an edit action you should work with the edit block instead.

.. code-block:: html+twig

    {# templates/admin/questionnaire/show_question_answer.html.twig #}

    {% extends ':admin:layout.html.twig' %}

    {% block show %}
        {{ sonata_block_render({ 'type': 'sonata_translation.block.locale_switcher' }, {
            'admin': admin,
            'object': questionnaire,
            'locale_switcher_route': 'show_question_answer',
            'locale_switcher_route_parameters': {'type': type}
        }) }}
    {% endblock %}

At this point, you should have a working locale switcher in your actions.

.. note::

    You had noticed that I don't use ``$object`` variable in my custom action like it's the case in ``CRUDController``.
    This is made on purpose cause we are not in a generic action and if your actions manipulate several kind of objects
    you will notice that it's really meaningful to do it this way.
