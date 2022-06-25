<?php

namespace App\Controller;

use App\Repository\EmployeeProductRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ReportsController extends AbstractController
{
    private $employeeRepository;
    private $productRepository;
    private $employeeProductRepository;

    public function __construct(EmployeeRepository $employeeRepository, ProductRepository $productRepository, EmployeeProductRepository $employeeProductRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->productRepository = $productRepository;
        $this->employeeProductRepository = $employeeProductRepository;
    }


    #[Route('v1/reports', name: 'app_reports')]
    public function reports(): JsonResponse
    {
        $reports = [];
        $employeesProducts = $this->employeeProductRepository->findAll();
    
        foreach($employeesProducts as $employeeProduct){
            $product = $this->productRepository->findOneBy([
                'product' => $employeeProduct->getProductName()
            ]);

            $hourly_quantity = 60 / $employeeProduct->getDuration();
            $reports[] = array(
                'employee_name' => $employeeProduct->getEmployeeName(),
                'product_name' => $employeeProduct->getProductName(),
                'duration' => $employeeProduct->getDuration(),
                'estimated_duration' => $product->getEstimatedDuration(),
                'hourly_quantity' => $hourly_quantity,
                'seven_hourly_quantity' => $hourly_quantity * 7
            );
            
        }
        

        return $this->json(['reports'=> $reports],200);
    }

    /*
        $reports = [];
        $employeesProducts = $this->employeeProductRepository->findAll();
    
        foreach($employeesProducts as $employeeProduct){
            $product = $this->productRepository->findOneBy([
                'product' => $employeeProduct->getProductName()
            ]);

            $hourly_quantity = 60 / $employeeProduct->getDuration();
            $reports[] = array(
                'employee_name' => $employeeProduct->getEmployeeName(),
                'product_name' => $employeeProduct->getProductName(),
                'duration' => $employeeProduct->getDuration(),
                'estimated_duration' => $product->getEstimatedDuration(),
                'hourly_quantity' => $hourly_quantity,
                'seven_hourly_quantity' => $hourly_quantity * 7
            );
            
        }
     */
}
