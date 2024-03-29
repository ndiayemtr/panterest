<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG file)',
                #'allow_delete' => true,
                'delete_label' => 'Delete',
                #'download_label' => '...',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_smail'
                #'image_uri' => true,
                #'asset_helper' => true,
            ])
            ->add('title', null)
            ->add('description', null, [
            'attr' => ['rows' => 10, 'cols' => 30]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
