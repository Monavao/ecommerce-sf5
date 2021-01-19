<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager     = $entityManager;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homePage(): Response
    {
        $products = $this->productRepository->findBy([], [], 3);

//        dump($products);

        return $this->render('home.html.twig', ['products' => $products]);
    }
}
