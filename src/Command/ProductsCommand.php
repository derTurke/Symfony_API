<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp\Client as Client;

class ProductsCommand extends Command
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('products:create')
            ->setDescription('Get guzzle products and add products.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uris = [
            'https://run.mocky.io/v3/db7caae9-d80c-4029-b596-b5bdb0efcf62',
            'https://run.mocky.io/v3/ede035a4-732d-4724-8311-ab21ccb5dfba',
            'https://run.mocky.io/v3/5d143a33-e02e-42a2-9b83-08accf4f4a80'
        ];

        $client = new Client();
        foreach($uris as $uri){
            $response = $client->request('GET', $uri);
            $products = $response->getBody();
            $products = json_decode($products);

            if(empty($products)){
                $output->writeln('There are no products to register!');
            }
            
            if(!empty($products->products)){
                foreach($products->products as $product){
                    $product_model = new Product();
                    $product_model->setProduct($product->product);
                    $product_model->setEstimatedDuration($product->estimated_duration);
                    $this->productRepository->add($product_model,true);   
                }
            } else {
                $product_model = new Product();
                $product_model->setProduct($products->product);
                $product_model->setEstimatedDuration($products->estimated_duration);
                $this->productRepository->add($product_model,true);
            }
        }
        $output->writeln("Successfully");
        return Command::SUCCESS;
    }

    /*
    $client = new Client();

        //All Product
        $response = $client->request('GET', 'https://run.mocky.io/v3/5d143a33-e02e-42a2-9b83-08accf4f4a80');
        $products = $response->getBody();
        $products = json_decode($products);

        if(empty($products)){
            $output->writeln('There are no products to register!');
        }

        foreach($products->products as $product){
            $product_model = new Product();
            $product_model->setProduct($product->product);
            $product_model->setEstimatedDuration($product->estimated_duration);
            $this->productRepository->add($product_model,true);
        }
        $output->writeln('Successfully');
        return Command::SUCCESS;
    */
}
