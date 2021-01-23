<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;
    /**
     * @var FormFactoryInterface
     */
    protected FormFactoryInterface $formFactory;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository, FormFactoryInterface $formFactory)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository  = $productRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/{slug}", name="product_category")
     * @param $slug
     * @return Response
     */
    public function category($slug): Response
    {
        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Cette page catégorie n\'existe pas');
        }

        return $this->render('product/category.html.twig', [
            'slug'     => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     * @param $slug
     * @return Response
     */
    public function show($slug): Response
    {
        $product = $this->productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas');
        }

        return $this->render('product/show.html.twig', [
            'product'      => $product
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {

//        dump($request);

        $builder = $this->formFactory->createBuilder();

        $builder->add(
                'name', TextType::class, [
                'label' => 'Nom du produit',
            ])
                ->add(
                'shortDescription', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
            ])
            ->add(
                'category', EntityType::class, [
                'label'        => 'Catégorie',
                'placeholder'  => '-- Choisir une catégorie --',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->orderBy('u.name', 'ASC');
                },
                'class'        => Category::class,
                'choice_label' => function(Category $category) {
                    return mb_strtoupper($category->getName());
                }
            ]);

//        $options = [];
//        foreach ($this->categoryRepository->findAll() as $category) {
//            $options[$category->getName()] = $category->getId();
//        }

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $product = new Product();

            $product->setName($data['name'])
                    ->setShortDescription($data['shortDescription'])
                    ->setPrice($data['price'])
                    ->setCategory($data['category']);

            dump($product);
        }


        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
