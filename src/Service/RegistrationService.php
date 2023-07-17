<?php

namespace App\Service;
use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ){ }

    public function RegisterUser(User $user, UserPasswordHasherInterface $userPasswordHasher, RegistrationFormType $form) : void
    {
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setRole(['ROLE_USER']);
        $user->setCreatedAt((new DateTimeImmutable));

        $this->UpdateUser($user);
    }

    public function UpdateUser(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}