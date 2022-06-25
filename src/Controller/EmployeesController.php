<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractController
{
    private $employeeRepository;
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('v1/employees', name: 'get_all_employees', methods: ['GET'])]
    public function getAllEmployee(): Response
    {
        $employees = $this->employeeRepository->findAll();

        $employeesCollection = [];
        foreach($employees as $employee){
            $employeesCollection[] = array(
                'name' => $employee->getName()
            );
        }
        
        return $this->json([
            'employees' => $employeesCollection
        ]);
    }

    #[Route('v1/employees/{id}', name: 'get_employee', methods: ['GET'])]
    public function getEmployee($id): Response
    {
        $employee = $this->employeeRepository->find($id);
        return $this->json([
            'name' => $employee->getName()
        ]);
    }
    
    #[Route('v1/addEmployee', name: 'add_employee', methods: ['POST'])]
    public function addEmployees(Request $request): Response
    {
        $name = $request->request->get('name');
        if(empty($name)){
            return $this->json([
                'message' => 'Name cannot be empty!'
            ],200);
        } 
        $employee = new Employee();
        $employee->setName($name);
        $this->employeeRepository->add($employee,true);
        return $this->json([
            'message' => 'Successfully'
        ],200);
    }

    
}
