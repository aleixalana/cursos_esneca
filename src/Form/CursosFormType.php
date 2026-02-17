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
        $dades = $options['dades'];

        $builder
            ->add('codi', TextType::class, [
                    'label'     => 'Codi del Curs',
                    'help'      => 'Obligatori',
                    'attr'      => [
                        'placeholder' => 'Introdueix el codi del curs',
                    ],
                    'data' => $dades['codi'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
            ])
            ->add('nom', TextType::class, [
                    'label'     => 'Nom del Curs',
                    'help'      => 'Obligatori',
                    'attr'      => [
                        'placeholder' => 'Introdueix el nom del curs',
                    ],
                    'data' => $dades['nom'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
            ])
            ->add('data_inici', DateType::class, [
                    'label'     => 'Data Inici',
                    'help'      => 'Obligatori',
                    'widget'    => 'single_text',
                    'data' => $dades['data_inici'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('data_fi', DateType::class, [
                    'label'     => 'Data Inici',
                    'help'      => 'Obligatori',
                    'widget'    => 'single_text',
                    'data' => $dades['data_fi'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('duracio', NumberType::class, [
                    'label'     => 'Duració (hores)',
                    'help'      => 'Obligatori',
                    'scale'     => 1, // Sol 1 decimal
                    'html5'     => true,
                    'attr'      => [
                        'placeholder'   => 'Introdueix la duració del curs en hores',
                    ],
                    'data' => $dades['duracio'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
            ->add('preu', NumberType::class, [
                    'label'     => 'Preu',
                    'help'      => 'Obligatori',
                    'scale'     => 2, // preu amb dos decimals
                    'html5'     => true, // teclat númeric a mòbil
                    'attr'      => [
                        'placeholder'   => 'Introdueix el preu en euros (ex: 19.75)',
                    ],
                    'data' => $dades['preu'] ?? null,
                    'required' => false, // CAMP NO REQUERIT: Ho desactivem per aquest ejercici per validar-ho nosaltres de forma manual
                ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'dades' => array(),
            'data_class' => null, // No lligar a cap entitat (ho fem manual per a aquesta pràctica)
            'csrf_protection' => true,
            'csrf_field_name' => 'tokenAleix', // Sistema de Tokenització CSRF per seguretat
            'csrf_token_id'   => 'curs_token' // AquestEste valor es el que debemos pasar al parámetro de comprobar
        ]);
    }
}
