<?php

namespace ApaiIODemo\Controller;

use Silex\Application;
use ApaiIODemo\ApaiIOServiceProvider;
use ApaiIO\Operations\Search;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;
use ApaiIODemo\Form\SearchType;
use ApaiIO\Operations\Lookup;
use ApaiIO\Configuration\GenericConfiguration;

class ApiController
{
	public static function registerController(Application $app)
	{
		$api = $app['controllers_factory'];

		$api->before(function(Request $request, Application $app) {
			if (false === $request->isXmlHttpRequest()) {
				return $app->abort(404);
			}
		});

		$api->get('/lookup/{asin}/{locale}', 'ApaiIODemo\Controller\ApiController::lookup');
		$api->post('/search', 'ApaiIODemo\Controller\ApiController::Search');

		$app->mount('/api', $api);
	}

	public function lookup(Application $app, Request $request, $asin, $locale)
	{
		$lookup = new Lookup();
		$lookup
			->setItemId($asin)
			->setResponseGroup(array('ItemAttributes', 'EditorialReview', 'Images', 'OfferSummary', 'Reviews'));

		$conf = new GenericConfiguration();
		$conf
			->setCountry($locale)
			->setAccessKey($app['apaiio.config']['AWS_API_KEY'])
			->setSecretKey($app['apaiio.config']['AWS_API_SECRET_KEY'])
			->setAssociateTag($app['apaiio.config']['AWS_ASSOCIATE_TAG'])
			->setResponseTransformer('\ApaiIODemo\ResponseTransformer\ItemLookupResponseTransformer');

		$result = $app['apaiio']->runOperation($lookup, $conf);

		return $app['twig']->render('lookup.twig', array('result' => $result));
	}

	public function search(Application $app, Request $request)
	{
		$form = $app['form.factory']->create(new SearchType());

		$form->bind($request);

		if ($form->isValid()) {
			$formData = $form->getData();

			$conf = new GenericConfiguration();
			$conf
				->setCountry($formData['locale'])
				->setAccessKey($app['apaiio.config']['AWS_API_KEY'])
				->setSecretKey($app['apaiio.config']['AWS_API_SECRET_KEY'])
				->setAssociateTag($app['apaiio.config']['AWS_ASSOCIATE_TAG'])
				->setResponseTransformer('\ApaiIODemo\ResponseTransformer\ItemSearchResponseTransformer');

			$search = new Search();
			$search
				->setCategory($formData['category'])
				->setKeywords($formData['search'])
				->setResponseGroup(array('Large'))
				->setPage((int) $formData['page']);

			$result = $app['apaiio']->runOperation($search, $conf);

			return $app['twig']->render('singleItem.twig', array('result' => $result, 'locale' => $formData['locale']));
		}

		return $app->json(-1, 200);
	}
}