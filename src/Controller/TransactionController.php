<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\Transaction;
use App\Services\TransactionService;
use App\Repository\ComissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionController extends AbstractController
{
     /**
     * @Route(
     *      name="depotTransaction" ,
     *      path="/api/utilisateur/deposer/" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\TransactionController::depotTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionDepot"
     *     }
     *)
    */
    public function depotTransaction(TokenStorageInterface $tokenStorage, Request $request, TransactionService $transactionService , 
                                    EntityManagerInterface $manager, SerializerInterface $serialize, ComissionRepository $commissionRepo) {
       
        $commission = $commissionRepo->findAll(); 
        $commission =  $commission[0] ;        
        $transaction = $request->getContent();

        $user = $tokenStorage->getToken()->getUser();
        $compte = $user->getAgence()->getCompte();
        $solde = $compte->getSolde();
        if($solde<5000){
            return $this->json("Le solde compte est inférieur à 5000", 404);
        }

        $transaction = $serialize->decode($transaction, 'json');
        $transactionDepot = $serialize->denormalize($transaction, Transaction::class, true);
        $clientDepot = $serialize->denormalize($transaction['clientDepot'], Client::class, true);
        $clientRetrait = $serialize->denormalize($transaction['clientRetrait'], Client::class, true);
        $transactionDepot ->setDateDepot(new \DateTime);
        $transactionDepot ->setTTC($transactionService->calculFrais($transactionDepot->getMontant()));
        $transactionDepot ->setFraisEtat($transactionDepot->getTTC()*$commission->getCommisionEtat()/100);
        $transactionDepot ->setFraisSystem($transactionDepot->getTTC()*$commission->getcommisionSystem()/100);
        $transactionDepot ->setFraisRetrait($transactionDepot->getTTC()*$commission->getcommsionRetrait()/100);
        $transactionDepot ->setFraisEnvoi($transactionDepot->getTTC()*$commission->getcommissionEnvoi()/100);
        $transactionDepot ->setCodeTransaction($transactionService->code());
        $transactionDepot ->setCompte($compte);
        $compte->setSolde($compte->getSolde()-$transactionDepot->getMontant());
        $transactionDepot ->setUserDepot($user);
        $manager->persist($clientDepot);
        $manager->persist($clientRetrait);
        $transactionDepot ->setClientEnvoi($clientDepot);
        $transactionDepot ->setClientRetrait($clientRetrait);
        // dd($transactionDepot);

      
        $manager->persist($transactionDepot);
        $manager->flush();

        return new JsonResponse(["message"=>"Dépot effectué!!", Response::HTTP_CREATED]);
    }


     /**
     * @Route(
     *      name="retraitTransaction" ,
     *      path="/api/utilisateur/retrait/" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\TransactionController::retraitTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionRetrait"
     *     }
     *)
    */
    public function retraitTransaction(TokenStorageInterface $tokenStorage, Request $request, TransactionService $transactionService , 
                                    EntityManagerInterface $manager, SerializerInterface $serialize, ComissionRepository $commissionRepo, TransactionRepository $transRepo) {
       
       $transaction = $request->getContent();
       $user = $tokenStorage->getToken()->getUser();
       $compte = $user->getAgence()->getCompte();
       dd($compte);
       $transaction = $serialize->decode($transaction, 'json');
       $transactionRetrait = $serialize->denormalize($transaction, Transaction::class, true);
       $valCodeRetrait = $transactionRetrait->getCodeTransaction();
    //    dd($valCodeRetrait);
       $transactionDepot=$transRepo->findBy($transaction);
    //    dd($trans);
       foreach ($transactionDepot as $valueDepot){
        if($valueDepot->getCodeTransaction() != $valCodeRetrait ){
            return $this->json("Le code n'existe pas", 404);
        }

        if($valueDepot->GetDateRetrait() != null){
             return $this->json("Le retrait est deja effectue", 404);
        }

        $valueDepot ->setDateRetrait(new \DateTime);
        $valueDepot ->setUserRetrait($user);
        $valueDepot ->setCompte($compte);
        $compte->setSolde($compte->getSolde()+$valueDepot->getMontant());
        // dd($valueDepot);
       }
    
    
    
        $manager->persist($valueDepot);
        $manager->flush();

        return new JsonResponse(["message"=>"Retrait effectue avec succes!", Response::HTTP_CREATED]);
    }

}
