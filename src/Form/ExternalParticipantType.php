<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ExternalParticipant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalParticipantType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', TextType::class)
//			->add('activities', EntityType::class, [
//				'class' => Activity::class,
//				'multiple' => true,
//				'required' => false
//			])
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => ExternalParticipant::class,
			'csrf_protection' => false,
		]);
	}
}