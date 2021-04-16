<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tags", name="tag")
     * @return Response
     */
    public function index(): Response
    {



        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}
