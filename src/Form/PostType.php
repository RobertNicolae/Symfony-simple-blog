<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', EntityType::class, [
                "class" => Category::class
//                "query_builder" => function (EntityRepository $entityRepository) {
//                    return $entityRepository->createQueryBuilder('c')
//                        ->where('c.name LIKE :name ')
//                        ->setParameter('name', "req")
//                        ->orderBy("c.id", "desc");
//                }
            ])
            ->add('save', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-primary float-right"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
