<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/3/2018
 * Time: 4:26 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class)
            ->add('redirect', TextType::class)
            ->add('commenterName', TextType::class)
            ->add('commenterEmail', TextType::class)
            ->add('parentCommentId', IntegerType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(//'data_class' => 'AppBundle\Entity\Comment'
        ));
    }

}