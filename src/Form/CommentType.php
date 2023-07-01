<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for Comment entity.
 */
class CommentType extends AbstractType
{
    /**
     * @var Security The security service
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param Security $security The security service
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder->add('email', $user ? HiddenType::class : TextType::class, ['data' => $user ? $user->getEmail() : '']);
        $builder->add('nickname', $user ? HiddenType::class : TextType::class, ['data' => $user ? $user->getNickname() : '']);
        $builder->add('message');
    }

    /**
     * Configure the form options.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

