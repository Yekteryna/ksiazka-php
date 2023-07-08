<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'change_password')]
    #[IsGranted('ROLE_ADMIN')]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        $user = $this->getUser();
        // Create the change password form
        $form = $this->createFormBuilder()
            ->add('current_password', PasswordType::class, ['label' => 'Current Password'])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('save', SubmitType::class, ['label' => 'Change Password'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Verify the current password
            if (!$userPasswordHasher->isPasswordValid($user, $data['current_password'])) {
                $this->addFlash('error', 'Invalid current password.');

                return $this->redirectToRoute('change_password');
            }

            // Update the user's password
            $newEncodedPassword = $userPasswordHasher->hashPassword($user, $data['new_password']);
            $user->setPassword($newEncodedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your password has been changed.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}