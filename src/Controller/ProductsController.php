<?php
namespace App\Controller;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as Client;

class ProductsController extends AbstractController
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('v1/products', name: 'get_all_products', methods: ['GET'])]
    public function getAllProducts(): JsonResponse
    {
        $products = $this->productRepository->findAll();

        $productsCollection = array();
        foreach($products as $product){
            $productsCollection[] = array(
                'product' => $product->getProduct(),
                'estimated_duration' => $product->getEstimatedDuration()
            );
        }
        
        return $this->json(['products'=>$productsCollection],200);
    }

    #[Route('v1/products/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct($id): JsonResponse
    {
        if(empty($id)){
            return $this->json([
                'message' => 'Id cannot be empty!'
            ]);
        }
        $product = $this->productRepository->find($id);
        return $this->json([
            'product' => $product->getProduct(),
            'estimated_duration' => $product->getEstimatedDuration()
        ]);
    }

    #[Route('v1/addProduct', name: 'add_product', methods: ['POST'])]
    public function addEmployees(Request $request): Response
    {
        $product_name = $request->request->get('product');
        $estimated_duration = $request->request->get('estimated_duration');

        if(empty($product_name)){
            return $this->json([
                'message' => 'Product cannot be empty!'
            ],200);
        }

        if(empty($estimated_duration)){
            return $this->json([
                'message' => 'Estimated duration cannot be empty!'
            ],200);
        }

        $product = new Product();
        $product->setProduct($product_name);
        $product->setEstimatedDuration($estimated_duration);
        $this->productRepository->add($product,true);
        return $this->json([
            'message' => 'Successfully'
        ],200);    
    }

    #[Route('v1/getGuzzleProduct', name: 'get_guzzle_product')]
    public function getGuzzleProduct(): Response
    {
        $client = new Client();
        $response = $client->request('GET', 'https://run.mocky.io/v3/5d143a33-e02e-42a2-9b83-08accf4f4a80');
        $products = $response->getBody();
        $products = json_decode($products);

        if(empty($products)){
            return $this->json(['message'=>'There are no products to register!'],400);
        }

        foreach($products->products as $product){
            $product_model = new Product();
            $product_model->setProduct($product->product);
            $product_model->setEstimatedDuration($product->estimated_duration);
            $this->productRepository->add($product,true);
        }
    }

}
