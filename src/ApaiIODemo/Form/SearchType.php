<?php
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
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	public function getName()
	{
		return 'searchform';
	}
}