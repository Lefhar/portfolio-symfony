<?php

namespace App\Form;

use App\Entity\Mesprojets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesprojetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content',TextareaType::class,['mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false,'data_class'=>null,'label'=>'Description du projet'])
            ->add('image',FileType::class,['attr'=>['accept'=>'image/*' , 'class'=>'form-control'],'data_class' => null,'mapped'=>false,'required'=>false])
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
