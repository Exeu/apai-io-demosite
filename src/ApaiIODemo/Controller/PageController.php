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
