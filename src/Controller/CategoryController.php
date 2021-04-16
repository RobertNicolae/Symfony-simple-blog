<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CategoryController
 * @Route("/category", name="category.")
 * @package App\Controller
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/show", name="show")
     * @param CategoryRepository $categoryRepository
     */
    public function showCategory(CategoryRepository $categoryRepository) {
        $category = $categoryRepository->findBy([
            "user" => $this->getUser()
        ],
            [
                "id" => "desc"
            ]);

        return $this->render('category/show.html.twig', [
            "categories" => $category
        ]);
    }


    /**
     * @param Request $request
     * @Route("/create", name="create")
     * @return RedirectResponse|Response
     */
    public function createCategory(Request $request)
    {
        $body = $request->getContent();

        $data = json_decode($body, true);

        $category = new Category();
        $category->setUser($this->getUser());
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        if($form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            http_response_code(200);
            return $this->json([
                "name" => $category->getName(),
                "user" => $category->getUser()->getUsername()
            ]);
//            return $this->redirect($this->generateUrl("category.show"));
        }

//        http_response_code(400);
//        return $this->json([
//            "error" => "Application error"
//        ]);
        return $this->render('category/index.html.twig', [
           "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Category $category
     */
    public function removeCategory(Category $category){

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute("category.show");
    }

    /**
     * @Route("/update/{id}", name="update")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function updateCategory(Request $request) {


        $id = $request->get('id');
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $categoryData = $form->getData();
            $em->persist($categoryData);
            $em->flush();
            return $this->redirectToRoute('category.show');
        }

        return $this->render('category/index.html.twig',array(
            'form' => $form->createView(),
        ));

    }
}
