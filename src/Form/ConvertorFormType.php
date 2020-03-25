<?php

namespace App\Form;

use App\Entity\FileToBeConverted;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvertorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('FileType', TextType::class, ['label' => 'FileType'])
            // ->add('File')
            ->add('SubmitXLXS', SubmitType::class, ['label' => 'Submit XLXS'])
            ->add('SubmitCSV', SubmitType::class, ['label' => 'Submit CSV']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileToBeConverted::class,
        ]);
    }
}
