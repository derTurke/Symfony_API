<?php

namespace App\Controller;

use App\Entity\EmployeeProduct;
use App\Repository\EmployeeProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesProductsController extends AbstractController
{
    private $employeeProductRepository;
    public function __construct(EmployeeProductRepository $employeeProductRepository)
    {
        $this->employeeProductRepository = $employeeProductRepository;  
    }

    #[Route('v1/employeesProducts', name: 'get_all_employee_product', methods: ['GET'])]
    public function getAllEmployeeProduct(): Response
    {
        $employeeProducts = $this->employeeProductRepository->findAll();

        $employeeProductCollection = [];

        foreach($employeeProducts as $employeeProduct){
            $employeeProductCollection[] = array(
                'employee_name' => $employeeProduct->getEmployeeName(),
                'product_name' => $employeeProduct->getProductName(),
                'duration' => $employeeProduct->getDuration()
            );
        }
        
        return $this->json([
            'employeeProduct' => $employeeProductCollection
        ]);
    }


    #[Route('v1/employeesProducts/{id}', name: 'get_employee_product', methods: ['GET'])]
    public function getProduct($id): JsonResponse
    {
        if(empty($id)){
            return $this->json([
                'message' => 'Id cannot be empty!'
            ]);
        }
        $productEmployee = $this->employeeProductRepository->find($id);
        return $this->json([
            'employee_name' => $productEmployee->getEmployeeName(),
            'product_name' => $productEmployee->getProductName(),
            'duration' => $productEmployee->getDuration()
        ]);
    }


    #[Route('v1/addEmployeeProduct', name: 'add_employee_product', methods: ['POST'])]
    public function addEmployeeProduct(Request $request): Response
    {
        $employee_name = $request->request->get('employee_name');
        $product_name = $request->request->get('product_name');
        $duration = $request->request->get('duration');

        if(empty($employee_name)){
            return $this->json([
                'message' => 'Employee name cannot be empty!'
            ],400);
        }

        if(empty($product_name)){
            return $this->json([
                'message' => 'Product name cannot be empty!'
            ],400);
        }

        if(empty($duration)){
            return $this->json([
                'message' => 'Duration cannot be empty!'
            ],400);
        }

        $employeeProduct = new EmployeeProduct();
        $employeeProduct->setEmployeeName($employee_name);
        $employeeProduct->setProductName($product_name);
        $employeeProduct->setDuration($duration);
        $this->employeeProductRepository->add($employeeProduct,true);
        return $this->json(['message'=>'Successfuly'],200);
        
    }

}
