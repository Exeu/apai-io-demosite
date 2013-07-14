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
					'Blended' => 'Blended'
				)
			))
			->add('locale', 'choice', array(
				'choices' => array(
					'de' => 'DE'
			)));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	public function getName()
	{
		return 'searchform';
	}
}