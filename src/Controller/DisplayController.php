<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\InputPhpJsonFile;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DisplayController
 * @package App\Controller
 */

class DisplayController extends AbstractController
{

    /**
     * @Route("/display",  name="display")
     */
    public function alltransaction(InputPhpJsonFile $arrayJson1)
    {
        $arrayJson1=$arrayJson1->decodeJson();

        return $this->render('display/index.html.twig', [
            'arrayAllCustomer' =>  $arrayJson1,
        ]);
    }

    /**
     * @Route("/allunique",  name="tenbestcustomer")
     */

    public function tencustomer(InputPhpJsonFile $arrayJsonAllCustomer,InputPhpJsonFile $arrayUnique,InputPhpJsonFile $arrayCustomerNet,InputPhpJsonFile $arraySortTen)
    {
        $arrayJsonAllCustomer=$arrayJsonAllCustomer->decodeJson();

        $arrayUnique= $arrayUnique->DisplayAllUniqueCustomers($arrayJsonAllCustomer);

        $arrayCustomerNet=$arrayCustomerNet->SumAmountAllCustomers($arrayUnique, $arrayJsonAllCustomer);


        $arraySortTen=$arraySortTen->sortTenCustomers($arrayCustomerNet);


        return $this->render('display/firsttenamountsevent.html.twig', [
            'arrayFirstTenCustomer' =>   $arraySortTen,
        ]);
    }

    /**
     * @Route("/fiveevent",  name="fivecustomer")
     */

    public function fivecustomer(InputPhpJsonFile $arrayJsonAllCustomer,InputPhpJsonFile $arrayUnique,InputPhpJsonFile $arrayCustomerNet,InputPhpJsonFile $arraySortFive)
    {
        $arrayJsonAllCustomer=$arrayJsonAllCustomer->decodeJson();

        $arrayUnique= $arrayUnique->DisplayAllUniqueCustomers($arrayJsonAllCustomer);

        $arrayCustomerNet=$arrayCustomerNet->SumAmountAllCustomers($arrayUnique, $arrayJsonAllCustomer);


        $arraySortFive=$arraySortFive->sortFiveCustomersMoreTransaction($arrayCustomerNet);

       // dd($arraySortFive);

        return $this->render('display/firstfiveevent.html.twig', [
            'arrayFirstTenCustomer' =>   $arraySortFive,
        ]);
    }



}
