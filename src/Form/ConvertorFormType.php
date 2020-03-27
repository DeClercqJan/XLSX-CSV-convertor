<?php

namespace App\Form;

use App\Entity\FileToBeConverted;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvertorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('File', FileType::class, ['label' => 'Select file'])
            ->add('xml', SubmitType::class, ['label' => 'Convert to XML'])
            ->add('csv', SubmitType::class, ['label' => 'Convert to CSV']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileToBeConverted::class,
        ]);
    }
}
