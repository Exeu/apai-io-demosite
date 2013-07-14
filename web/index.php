<?php
$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add("ApaiIODemo", __DIR__.'/../src');

require_once __DIR__.'/../app/config/conf.php';

use ApaiIODemo\ApaiIOServiceProvider;
use ApaiIO\Operations\Search;
use Silex\Provider\TwigServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;
use ApaiIODemo\Form\SearchType;
use ApaiIO\Operations\Lookup;

$app = new Silex\Application();

// Registering apaiio service
$app->register(new ApaiIOServiceProvider(), array(
	'apaiio.config' => array(
		'AWS_API_KEY' => AWS_API_KEY,
		'AWS_API_SECRET_KEY' => AWS_API_SECRET_KEY,
		'AWS_ASSOCIATE_TAG' => AWS_ASSOCIATE_TAG,
		'ENDPOINT' => ENDPOINT,
		'REQUEST' => '\ApaiIO\Request\Soap\Request',
		'RESPONSE' => '\ApaiIO\ResponseTransformer\ObjectToArray'
)));

$app->register(new Silex\Provider\TranslationServiceProvider(), array('locale_fallback' => 'de',));
$app->register(new FormServiceProvider());

// Registering twig service
$app->register(new TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views',
		'twig.options' => array(
			'debug' => true,
			//'cache' => __DIR__.'/../cache/view_cache'
		)
));


$app->get('/', function (Application $app, Request $request) {
	$form = $app['form.factory']->create(new SearchType());

	return $app['twig']->render('index.twig', array(
		'form' => $form->createView()
	));
});

$app->get('/api/lookup/{asin}', function (Application $app, Request $request, $asin) {
	$lookup = new Lookup();
	$lookup
		->setItemId($asin)
		->setResponseGroup(array('ItemAttributes', 'EditorialReview'));

	$result = $app['apaiio']->runOperation($lookup);

	return $app['twig']->render('lookup.twig', array(
		'result' => $result
	));
});

$app->post('/api/search', function (Application $app, Request $request) {
	$form = $app['form.factory']->create(new SearchType());

	$form->bind($request);

	if ($form->isValid()) {
		$formData = $form->getData();

		$search = new Search();
		$search
			->setCategory($formData['category'])
			->setKeywords($formData['search'])
			->setResponseGroup(array('Large'))
			->setMerchantId('Amazon');

		$response = $app['apaiio']->runOperation($search);

		$output = array();
		foreach ($response['Items']['Item'] as $singleItem)
		{
			$data = array();

			$title = $singleItem['ItemAttributes']['Title'];
			if (mb_strlen($title) > 30)
			{
				$title = substr($title,0, 30);
			}

			if (true === empty($title)) {
				continue;
			}

			$data['title'] = $title;
			$data['url']   = $singleItem['DetailPageURL'];
			$data['img']   = $singleItem['MediumImage']['URL'];
			$data['price'] = $singleItem['ItemAttributes']['ListPrice']['FormattedPrice'];
			$data['asin']  = $singleItem['ASIN'];

			$output[] = $data;
		}

		return $app->json($output, 201);
	}

	return "x";
});

$app->run();