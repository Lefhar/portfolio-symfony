<?php

namespace App\Form;

use App\Entity\Blocimage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocimageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content',TextareaType::class,['mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false])
            ->add('type')
            ->add('cv')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blocimage::class,
        ]);
    }
}
