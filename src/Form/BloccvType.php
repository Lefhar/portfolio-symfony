<?php

namespace App\Form;

use App\Entity\Bloccv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BloccvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['label'=>'Titre'])
            ->add('content',TextareaType::class,['label'=>'Contenu','mapped'=>false,'attr'=>['class'=>'form-control tinymce'],'required'=>false])

            ->add('emplacement', ChoiceType::class, array(
                'choices' => [
                    'Gauche' => 'left',
                    'Droite' => 'right'
                ]))
            ->add('position')
            ->add('public')
            ->add('cv')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bloccv::class,
        ]);
    }
}
