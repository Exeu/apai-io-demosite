<?php

namespace ApaiIODemo;

use Silex\ServiceProviderInterface;
use Silex\Application;
use ApaiIO\Configuration\GenericConfiguration;

class ApaiIOServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		$app['apaiio.config'] = array(
			'ENDPOINT' => null,
			'AWS_API_KEY' => null,
			'AWS_API_SECRET_KEY' => null,
			'AWS_ASSOCIATE_TAG' => null
		);

		$app['apaiio'] = $app->share(function($app) {
			$conf = new GenericConfiguration();
			$conf
				->setCountry($app['apaiio.config']['ENDPOINT'])
				->setAccessKey($app['apaiio.config']['AWS_API_KEY'])
				->setSecretKey($app['apaiio.config']['AWS_API_SECRET_KEY'])
				->setAssociateTag($app['apaiio.config']['AWS_ASSOCIATE_TAG']);

			if (false === empty($app['apaiio.config']['REQUEST'])) {
				$conf->setRequest($app['apaiio.config']['REQUEST']);
			}

			if (false === empty($app['apaiio.config']['RESPONSE'])) {
				$conf->setResponseTransformer($app['apaiio.config']['RESPONSE']);
			}

			return new \ApaiIO\ApaiIO($conf);
		});
	}


	public function boot(Application $app)
	{

	}
}