<?php
/*
 * Copyright 2013 Jan Eichhorn <exeu65@googlemail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
