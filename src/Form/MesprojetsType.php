<?php

namespace App\Form;

use App\Entity\Mesprojets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesprojetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content',TextareaType::class,['mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false,'data_class'=>null])
            ->add('lien_github')
            ->add('lien_web')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mesprojets::class,
        ]);
    }
}
