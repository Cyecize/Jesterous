<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/3/2018
 * Time: 9:46 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleAsMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscribe_reward__bg_article', IntegerType::class)
            ->add('subscribe_reward__en_article', IntegerType::class)
            ->add('subscribe_message__bg_article', IntegerType::class)
            ->add('subscribe_message__en_article', IntegerType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(//  'data_class' => 'AppBundle\Entity\User',

        ));
    }
}