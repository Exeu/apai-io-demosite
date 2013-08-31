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

namespace ApaiIODemo\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('search', 'text')
        ->add('category', 'choice', array(
            'choices' => array(
                "All" => "All",
                "Books" => "Books",
                "DVD" => "DVD",
                "Apparel" => "Apparel",
                "Automotive" => "Automotive",
                "Electronics" => "Electronics",
                "GourmetFood" => "GourmetFood",
                "Kitchen" => "Kitchen",
                "Music" => "Music",
                "PCHardware" => "PCHardware",
                "PetSupplies" => "PetSupplies",
                "Software" => "Software",
                "SoftwareVideoGames" => "SoftwareVideoGames",
                "SportingGoods" => "SportingGoods",
                "Tools" => "Tools",
                "Toys" => "Toys",
                "VHS" => "VHS",
                "VideoGames" => "VideoGames"
            )
        ))
        ->add('locale', 'choice', array(
            'choices' => array(
                'de' => 'Germany',
                'com' => 'USA',
                'ca' => 'Canada',
                'cn' => 'China',
                'it' => 'Italy',
                'es' => 'Spain',
                'fr' => 'France',
                'co.jp' => 'Japan',
                'co.uk' => 'United Kingdom'
            )
        ))
        ->add('page', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    public function getName()
    {
        return 'searchform';
    }
}
