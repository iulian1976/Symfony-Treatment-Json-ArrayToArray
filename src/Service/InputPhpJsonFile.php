<?php

namespace App\Service;


use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class InputPhpJsonFile
{
    private $arrayJson;
    private $arrayClearJson;
    private $arrayCustomerNet;
    private $itemamount=0;
    private $arrayIntermedCustomer;
    private $arrayCustomerDistinct;


//$transaction=file_get_contents('transactions_mock.json');
//dd($transaction);


    public function decodeJson(){
        $data = file_get_contents('transactions_mock.json');

        $this->arrayJson= json_decode($data, true);

        $this->clearAmount();

        return  $this->clearAmount();
    }


    // --->>>treatment and modify "amount"
    public function clearAmount(){  // because each amont have a string '$','€'...

         $i=0;
        foreach ($this->arrayJson as $item) {

            //$amount=$item['amount'];

            $item['amount']=$this->filterItemAmount($item['amount']);

            $this->arrayClearJson[$i]=$item;
         $i++;
        }

        return  $this->arrayClearJson;
    }

    public function filterItemAmount($itema){

        $this->itemamount=$itema;

        $exchangeRates = [
            '€' => 1,  // reference euro
            '$' => 0.88,
            '¥' => 0.008,
            '£' => 1.13
        ];

        $extractexchange=mb_substr($this->itemamount, 0, 1);

        $extractamount=mb_substr($this->itemamount, 1);

        $this->itemamount=round(floatval($extractamount) * $exchangeRates[ $extractexchange], 2);

        return  $this->itemamount;
    }

    public function DisplayAllUniqueCustomers($arrayListCustomer)
    {
        foreach ($arrayListCustomer as $key =>$result) {
            $this->arrayIntermedCustomer[$key]=$arrayListCustomer[$key]["event_name"];
        }
        $this->arrayCustomerDistinct=array_unique( $this->arrayIntermedCustomer);
        return $this->arrayCustomerDistinct;
    }

    public function SumAmountAllCustomers($arrayUnique, $arrayJsonAllCustomer)
    {

        $i=0;
        $event_name=0;
        $amountSum=0;
        $count=0;
        foreach ($arrayUnique as $key1 =>$result1) {

            foreach ($arrayJsonAllCustomer as $key2 =>$result2) {
                if($arrayJsonAllCustomer[$key2]["event_name"]==$result1) {
                    $i=$i+1;
                    $event_name=$result1;
                    $amountSum = $amountSum +$arrayJsonAllCustomer[$key2]["amount"] ;
                    $count=$i;
                }
            }
            $this->arrayCustomerNet[$key1]["event_name"]=$event_name;
            $this->arrayCustomerNet[$key1]["amount"]=$amountSum;
            $this->arrayCustomerNet[$key1]["count"]=$count;

            $i=0;
        }
        //dd($this->arrayCustomerNet);
        return  $this->arrayCustomerNet;
    }

    public function sortTenCustomers($arrayCustomerNet)
    {

         usort($arrayCustomerNet, function ($item1, $item2) {return $item2["amount"]  > $item1["amount"];});

        return $arrayCustomerNet;
    }
    public function sortFiveCustomersMoreTransaction($arrayCustomerNet)
    {
        usort($arrayCustomerNet, function ($item1, $item2) {return $item2["count"]  > $item1["count"];});
        //dd($arrayListCustomer);
        return $arrayCustomerNet;
    }
}
