<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentController.
 */
class ErrorController extends AbstractController
{
    /**
     * AccessDenied action.
     *
     * @return Response HTTP response
     */
    public function accessDenied(): Response
    {
        return $this->render('errors/access_denied.html.twig');
    }
}
