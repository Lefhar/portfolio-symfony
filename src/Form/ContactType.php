<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

            $builder
                ->add('email',EmailType::class,['label'=>'Votre Email','required'=>true])
                ->add('sujet', ChoiceType::class,[
                    'choices' => [
                        'Séléctionnez le sujet' => '',
                        'Demande de CV' => 'cv',
                        'Offre d\'emploi' => 'offre',
                        'Autres offres' => 'autreoffre',
                        'remarque' => 'Remarque',
                    ],'required'=>true
                ])
                ->add('message',TextareaType::class,['label'=>'Votre message','attr'=>['rows'=>10],'required'=>false])
                ->add("recaptcha", ReCaptchaType::class)
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
