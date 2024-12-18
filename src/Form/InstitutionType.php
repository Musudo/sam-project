<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Institution;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('clientId', TextType::class, [
				'required' => false,
			])
			->add('name', TextType::class)
			->add('user', EntityType::class, [
				'class' => User::class
			])
			->add('contacts', EntityType::class, [
				'class' => Contact::class,
				'multiple' => true
			])
			->add('street', TextType::class)
			->add('number', TextType::class)
			->add('postbox', TextType::class, [
				'required' => false
			])
			->add('city', TextType::class)
			->add('zipCode', TextType::class)
			->add('country', TextType::class)
			->add('longitude', TextType::class, [
				'required' => false,
			])
			->add('latitude', TextType::class, [
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Institution::class,
			'csrf_protection' => false,
		]);
	}

}