<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ChangePasswordService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ){ }

    public function UpdatePassword(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}