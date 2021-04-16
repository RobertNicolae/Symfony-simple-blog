<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\PostType;
use App\Form\TagType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([
            "user" => $this->getUser()
        ],
            [
                "id" => "desc"
            ]);


        return $this->render('post/index.html.twig', [
            'posts' => $posts

        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @Route("/create", name="create")
     */
    public function create(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        // create a new post or a new title

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("post.show", [
                "id" => $post->getId()
            ]);
        }

        //entity manager

        //return response (view or something)
        return $this->render("/post/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route ("/json/{id}", name="show_json")
     * @param Post $post
     * @return Response
     */
    public function showJson(Post $post): Response
    {
        return $this->json([
            "post" => [
                "id" => $post->getId(),
                "name" => $post->getTitle(),
                "category" => [
                    "id" => $post->getCategory()->getId(),
                    "name" => $post->getCategory()->getName()
                ],
                "username" => $post->getUser()->getUsername()
            ]
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {


        //create the show view
        return $this->render('post/showPosts.html.twig', [
            "post" => $post,
            "user" => $this->getUser()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Post $post
     */
    public function removePost(Post $post): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash("success", "Post was removed");
        return $this->redirect($this->generateUrl('post.index'));
    }

    /**
     * @Route("/update/{id}", name="update")
     * @param Request $request
     * @param PostRepository $postRepository
     * @return Response
     */

    public function updatePost(Request $request, PostRepository $postRepository): Response
    {
        $id = $request->get("id");
        $post = $postRepository->find($id);


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('post.index');
        }
        return $this->render('post/create.html.twig', [
            "form" => $form->createView()
            ]);
    }


    /**
     * @Route("/tag/add", name="tag.add")
     * @param Request $request
     * @param Tag $tag
     * @param PostRepository $postRepository
     * @return RedirectResponse|Response
     */
    public function addTag(Request $request, PostRepository $postRepository)
    {

        $id = $request->get("id");
        $tag = new Tag();
        $post = $postRepository->find($id);
        $tag->addPostTag($post);
        $form = $this->createForm(TagType::class, $tag, array(
            "post" => $post
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute("post.index");
        }

        return $this->render("tag/index.html.twig", [
            "form" => $form->createView()
        ]);
    }

}
