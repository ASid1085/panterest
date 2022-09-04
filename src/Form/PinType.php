<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'JPG or PNG file',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Delete ?',
                //'download_label' => 'Download',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small'
                //'image_uri' => true,
                //'asset_helper' => true,
            ])
            ->add('title', null, [
                'attr' => ['autofocus' => true]
            ])
            ->add('description', null, [
                'attr' => [
                    'rows' => 10,
                    'cols' => 50
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
