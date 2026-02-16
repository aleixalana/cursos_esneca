<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class CursosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            // El codi de curs NO es tracta desde formulari 
            //->add('codi')
            
            ->add('nom', TextType::class, [
                    'label'     => 'Nom del Curs',
                    'help'      => 'Obligatori',
                    'attr'      => [
                        'placeholder' => 'Introdueix el nom del curs',
                    ],
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
            ])
            ->add('data_inici', DateType::class, [
                    'label'     => 'Data Inici',
                    'help'      => 'Obligatori',
                    'widget'    => 'single_text',
                    /*
                    'attr'      => [
                        'placeholder' => 'Introdueix data inici (ex: 13/02/2026)',
                    ],*/
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('data_fi', DateType::class, [
                    'label'     => 'Data Inici',
                    'help'      => 'Obligatori',
                    'widget'    => 'single_text',
                    /*
                    'attr'      => [
                        'placeholder' => 'Introdueix data inici (ex: 28/02/2026)',
                    ],
                    */
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('duracio', NumberType::class, [
                    'label'     => 'Duració (hores)',
                    'help'      => 'Obligatori',
                    'scale'     => 1,
                    'html5'     => true,
                    'attr'      => [
                        'placeholder'   => 'Introdueix la duració del curs en hores',
                        //'step'          => '0.5', // múltiples de 0.5
                        //'min'           => '0.5', // mínim duració curs
                    ],
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('preu', NumberType::class, [
                    'label'     => 'Preu',
                    'help'      => 'Obligatori',
                    'scale'     => 2,
                    'html5'     => true,
                    'attr'      => [
                        'placeholder'   => 'Introdueix el preu en euros (ex: 19.75)',
                        'step'          => '0.01',
                        'min'           => '0',
                    ],
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => 'tokenAleix', // Sistema de Tokenització CSRF per seguretat
            'csrf_token_id'   => 'curs_token' //Este valor es el que debemos pasar al parámetro de comprobar
        ]);
    }
}
