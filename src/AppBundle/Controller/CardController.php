<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Card;
use AppBundle\Form\Card\CardForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CardController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/karty"
 * )
 */
class CardController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_cards"
     * )
     */
    public function indexAction()
    {
        return $this->render('@App/card/list_card.html.twig', array());
    }

    /**
     * @Route(
     *     "/karta/{id}",
     *     name="route_card_add",
     *     defaults={"id"=0}
     * )
     */
    public function addCard(Request $request, $id)
    {
        if($id === 0)
        {
            $card = new Card();
        }else{
            $card = $this->getDoctrine()->getRepository(Card::class)->find($id);
            if(NULL === $card)
            {
                return $this->createNotFoundException("Nie znaleziono takiej karty w systemie!");
            }
        }

        $form = $this->createForm(CardForm::class, $card);
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($card);
                $manager->flush();

                return $this->redirectToRoute('route_cards');
            }
        }

        return $this->render('@App/card/add_update_card.html.twig', array(
            'form' => $form->createView(),
            'title' => ($id == 0) ? 'Dodaj nową kartę' : 'Karta nr: ' .$card->getPan()
        ));
    }

    /********************************API***********************************************/

    /**
     * @Route(
     *     "/api/cards",
     *     name="route_card_api_get"
     * )
     */
    public function getCardsAction()
    {
        $cards = $this->getDoctrine()
            ->getRepository(Card::class)->findCard();

        return $this->json(array(
            "data" => $cards
        ));
    }

    /**
     * @Route(
     *     "/api/card/{clientId}",
     *     name="route_card_api_get_by_client"
     * )
     */
    public function getCardByClientId($clientId)
    {
        $cards = $this->getDoctrine()
            ->getRepository(Card::class)->findCardByClient($clientId);

        return $this->json(array(
            "data" => $cards
        ));
    }

    /**
     * @Route(
     *     "/api/delete/{id}/{hash}",
     *     name="route_card_api_del"
     * )
     */
    public function deleteCard($id, $hash)
    {
        $repo = $this->getDoctrine()->getRepository(Card::class);
        $card = $repo->findOneBy(array(
            'id' => $id,
            'hash' => $hash
        ));

        if($card == NULL)
        {
            return $this->json(array(
                'status' => 'error',
                'message' => 'Nie znaleziono elementu spełniającego kryteria'
            ));
        }

        try{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($card);
            $manager->flush();
        }catch(\Exception $e){
            return $this->json(array(
                'status' => 'error',
                'message' => 'Nie znaleziono elementu spełniającego kryteria'
            ));
        }


        return $this->json(array(
            'status' => 'ok',
            'message' => 'Karte usunięto poprawnie.'
        ));
    }
}
