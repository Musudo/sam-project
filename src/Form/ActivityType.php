<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Contact;
use App\Entity\ExternalParticipant;
use App\Entity\Institution;
use App\Entity\Report;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('subject', TextType::class)
			->add('externalNote', TextType::class, [
				'required' => false
			])
			->add('internalNote', TextareaType::class, [
				'required' => false,
			])
			->add('type', TextType::class)
			->add('user', EntityType::class, [
				'class' => User::class
			])
			->add('institution', EntityType::class, [
				'class' => Institution::class
			])
			->add('contacts', EntityType::class, [
				'class' => Contact::class,
				'multiple' => true
			])
			->add('externalParticipants', EntityType::class, [
				'class' => ExternalParticipant::class,
				'multiple' => true,
				'required' => false,
			])
			->add('tags', EntityType::class, [
				'class' => Tag::class,
				'multiple' => true
			])
			->add('start', TextType::class)
			->add('end', TextType::class)
			->add('emailSentAt', TextType::class, [
				'required' => false
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Activity::class,
			'csrf_protection' => false,
		]);
	}

}