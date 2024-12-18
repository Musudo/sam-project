<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Attachment;
use App\Entity\Email;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title', TextType::class)
			->add('content', TextareaType::class)
			->add('user', EntityType::class, [
				'class' => User::class
			])
			->add('activity', EntityType::class, [
				'class' => Activity::class
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Review::class,
			'csrf_protection' => false,
		]);
	}

}