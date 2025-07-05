<?php

namespace App\Controller;

use App\Entity\Usr;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsrRepository;

class UsrController extends AbstractController
{
    // private $passwordHasher;

    // public function __construct(UserPasswordHasherInterface $passwordHasher)
    // {
    //     $this->passwordHasher = $passwordHasher;
    // }
    public function __invoke(Usr $data, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Usr
    {
        $plaintextPassword = $data->getPassword();

        if(strlen($plaintextPassword) > 5 && strlen($plaintextPassword) < 20 )
        {

            $hashedPassword = $passwordHasher->hashPassword(
                $data,
                $plaintextPassword
            );

            $data->setPassword($hashedPassword);

        }else{
            $data->setPassword($entityManager->getRepository(User::class)->getOldPass($data->getId()));
        }

        return $data;
    }

    // /**
    //  * @Route("/api/usrs/register", name="user_register", methods={"POST"})
    //  */
    // public function register(Request $request): Response
    // {
    //     $data = json_decode($request->getContent(), true);

    //     $usr = new Usr();
    //     $usr->setUsername(trim($data['username']));
    //     $usr->setMail(trim($data['mail']));
    //     $hashedPassword = $this->passwordHasher->hashPassword($usr,$data['password']);
    //     $usr->setPassword($hashedPassword);

    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->persist($usr);
    //     $entityManager->flush();

    //     return new JsonResponse([
    //         'id' => $usr->getId(),
    //         'username' => $usr->getUsername(),
    //         'mail' => $usr->getMail(),
    //     ], Response::HTTP_CREATED);
    // }
}
// $data = [
//     "CODECH" => "",
//     "NOOPER2" => "",
//     "NOOPER" => "",
//     "AGENCE" => "",
//     "DEVOUV" => "",
//     "MNTCR" => "",
//     "CLI1" => "",
//     "APPLICANT" => "",
//     "NOM5" => "",
//     "CLI5ADR1" => "",
//     "CLI5ADR2" => "",
//     "CLI4BIC" => "", 
//     "DATECH" => "",
//     "DATOPER" => ""
// ];
// if($ref_lci=="LCI 4965"){
//     $data = [
//         "CODECH" => "100JDREF", //usance
//         "NOOPER2" => "LCI 4965", //ref_credoc
//         "NOOPER" => "ZEZ1245", //ref_igor
//         "AGENCE" => "07500", //code_agence
//         "DEVOUV" => "USD", //monnaie
//         "MNTCR" => "80979.78", //montant
//         "CLI1" => "581759", //matricule
//         "APPLICANT" => "SFOI", //ordonnateur
//         "NOM5" => "DAPLAST JOINT STOCK COMPANY", //beneficiaire
//         "CLI5ADR1" => "323, BD MOULAY ISMALL 2", //coordonnee_beneficiaire
//         "CLI5ADR2" => "300 CASABLANCA MAROC", //coordonnee_beneficiaire
//         "CLI4BIC" => "BIDVVNVX",  // bic_banq
//         "DATECH" => "2024-08-21", // validite_credoc
//         "DATOPER" => "11/06/24"
//     ];
// }
// if($ref_lci=="LCI 4085"){
//     $data = [
//         "CODECH" => "MIXED", //usance
//         "NOOPER2" => "LCI 4965", //ref_credoc
//         "NOOPER" => "QLC9117", //ref_igor
//         "AGENCE" => "07100", //code_agence
//         "DEVOUV" => "EUR", //monnaie
//         "MNTCR" => "4514141.70", //montant
//         "CLI1" => "100473", //matricule
//         "APPLICANT" => "TELMA MOBILE S.A.", //ordonnateur
//         "NOM5" => "ERICSSON AB", //beneficiaire
//         "CLI5ADR1" => "S-164 80 STOCKOLM", //coordonnee_beneficiaire
//         "CLI5ADR2" => "STOCKOLM", //coordonnee_beneficiaire
//         "CLI4BIC" => "SWEDSESS",  // bic_banq
//         "DATECH" => "2022-11-28", // validite_credoc
//         "DATOPER" => "09/06/22"
//     ];
// }

// return $this->json($data);