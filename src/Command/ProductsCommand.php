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
        $client = new Client();
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
    }
}
