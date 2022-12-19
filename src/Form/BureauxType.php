<?php

namespace App\Form;

use App\Entity\Bureaux;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

use Vich\UploaderBundle\Form\Type\VichImageType;

class BureauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom'
            ])
            ->add('prix', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix'
            ])
            ->add('surface', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Surface'
            ])
            ->add('capacite', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Capacité'
            ])
            ->add('ht', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'HT'
            ])
            
            
            ->add('categories', EntityType::class,[
                'class' => Categories::class,
                'choice_label'  => 'libelle',
                'label' => 'Catégories',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('debut', TextType::class, array(
                'required' => false,
                'empty_data' => null,
                'label' => 'Date de début',
                'attr' => array(
                    'placeholder' => 'mm/dd/yyyy'
                )))
            ->add('fin', TextType::class, array(
                'required' => false,
                'empty_data' => null,
                'label' => 'Date de fin',
                'attr' => array(
                    'placeholder' => 'mm/dd/yyyy'
                )))
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bureaux::class,
        ]);
    }
}
