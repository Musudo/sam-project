<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Contact;
use App\Entity\Institution;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('firstName', TextType::class)
			->add('lastName', TextType::class)
			->add('email1', TextType::class)
			->add('email2', TextType::class, [
				'required' => false,
			])
			->add('phoneNumber1', TextType::class)
			->add('phoneNumber2', TextType::class, [
				'required' => false,
			])
			->add('jobTitle', TextType::class)/*
			->add('institutions', EntityType::class, [
				'class' => Institution::class,
				'multiple' => true
			])*/
			->add('activities', EntityType::class, [
				'class' => Activity::class,
				'multiple' => true,
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Contact::class,
			'csrf_protection' => false,
		]);
	}

}