<?php
namespace ApaiIODemo\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ApaiIODemo\Form\SearchType;

class PageController
{
	public static function registerController(Application $app)
	{
		$page = $app['controllers_factory'];

		$page->get('/', 'ApaiIODemo\Controller\PageController::index');

		$app->mount('/', $page);
	}

	public function index(Application $app, Request $request)
	{
		$form = $app['form.factory']->create(new SearchType(), array('page' => 1));

		return $app['twig']->render('index.twig', array(
				'form' => $form->createView()
		));
	}
}