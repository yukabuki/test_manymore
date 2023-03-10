<?php

namespace App\Form;

use App\Entity\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$request = $builder->getData();

		switch ($request->getStatus()) {
			case 'waiting':
				$choices = [
					'En cours' => 'progress',
					'Clos' => 'closed',
				];
				break;

			case 'progress':
				$choices = [
					'Clos' => 'closed',
				];
				break;

			case 'closed':
				$choices = [

				];
				break;

			default:
				$choices = [
					'En attente' => 'waiting',
					'En cours' => 'progress',
					'Clos' => 'closed',
				];
				break;
		}

        $builder
            ->add('Title', TextType::class, [
				'label' => 'Titre',
	            'constraints' => [
		            new Assert\Length([
			            'max' => 128,
			            'maxMessage' => 'Maximum {{ limit }} caractères',
		            ]),
		            new Assert\NotBlank()
	            ]
            ])
            ->add('text', TextareaType::class, [
				'label' => 'Contenu',
	            'constraints' => [
		            new Assert\NotBlank()
	            ]
            ])
            ->add('Attachment', FileType::class, [
	            'required' => false,
	            'label' => 'Piece joint',
            ]);
		if ($request->getId() && $request->getStatus() != 'closed') {
			$builder
				->add('Status', ChoiceType::class, [
					'choices'  => $choices,
					'label' => 'Statut',
				]);
		}
	    $builder
		    ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
