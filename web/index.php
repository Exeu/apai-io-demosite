<?php
$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add("ApaiIODemo", __DIR__.'/../src');

require_once __DIR__.'/../app/config/conf.php';

use ApaiIODemo\ApaiIOServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use ApaiIODemo\Controller\ApiController;
use ApaiIODemo\Controller\PageController;

$app = new Silex\Application();

// Registering apaiio service
$app->register(new ApaiIOServiceProvider(), array(
	'apaiio.config' => array(
		'AWS_API_KEY' => AWS_API_KEY,
		'AWS_API_SECRET_KEY' => AWS_API_SECRET_KEY,
		'AWS_ASSOCIATE_TAG' => AWS_ASSOCIATE_TAG,
		'ENDPOINT' => ENDPOINT,
		'REQUEST' => '\ApaiIO\Request\Rest\Request',
		'RESPONSE' => '\ApaiIODemo\ResponseTransformer\ItemSearchResponseTransformer'
)));

$app->register(new Silex\Provider\TranslationServiceProvider(), array('locale_fallback' => 'de',));
$app->register(new FormServiceProvider());

// Registering twig service
$app->register(new TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views',
		'twig.options' => array(
			'debug' => true,
			'cache' => __DIR__.'/../cache/view_cache'
		)
));

ApiController::registerController($app);
PageController::registerController($app);

$app->run();