<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/series", name="serie_")
 */
class SerieController extends AbstractController
{
    /**
    * @Route("", name="list")
    */
    public function list(SerieRepository $serieRepository): Response
    {
        //$series = $serieRepository->findBy([], ['popularity'=> 'DESC', 'vote' => 'DESC'], 30);
        $series = $serieRepository->findBestSeries();
        return $this->render('serie/list.html.twig', [
            "series"=>$series

        ]);
    }

    /**
     * @Route("/details/{id}", name= "details")
     */
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        if(!$serie) {
            throw $this->createNotFoundException('Oh no !!!!');
        }


        return $this->render('serie/details.html.twig', [
            "serie" => $serie,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(
        Request $request,
    EntityManagerInterface $entityManager
    ): Response
    {
        $serie = new serie();
        $serie->setDateCreated(new \DateTime());


        $serieForm = $this-> createForm(SerieType::class, $serie);

        //traiter le formulaire =>handleRequest et isSubmitted
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie added!  Good Job!');
          return $this->redirectToRoute('serie_details', ['id' =>$serie->getId()]);
        }
        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    /**
     * @route("/demo", name="em-demo")
     */
    public function demo(EntityManagerInterface $entityManager): Response {
        // crée une instance de mon entite
        $serie = new Serie();

        //hydrater toutes les propriétes
        $serie->setName('pif');
        $serie->setBackdrop('dafd');
        $serie->setPoster('qkfhlkqh');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime("- 1 year"));
        $serie->setLastAirDate(new \DateTime("- 6 month"));
        $serie->setGenres('drama');
        $serie->setOverview('bla bla bla');
        $serie->setPopularity(123.00);
        $serie->setVote(8.2);
        $serie->setStatus('Canceled');
        $serie->setTmdbId(329432);

        dump($serie);
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        //$entityManager->remove($serie);
        $serie->setGenres('comedy');
        $entityManager->flush();



        //$entityManager = $this->getDoctrine()->getManager();





        return $this->render('serie/create.html.twig');
    }

    /**
     * @route("/delete/{id}", name="delete")
     */
    public function delete(Serie $serie, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');


    }
}
