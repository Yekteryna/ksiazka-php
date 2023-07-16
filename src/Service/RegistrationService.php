<?php

namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ){ }

    public function UpdateUser(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}