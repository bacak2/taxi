<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\Blockade;
use AppBundle\Form\BlockadeAddForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/blokady"
 * )
 */
class BlockadeController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_blockade_list"
     * )
     */
    public function indexAction()
    {
        return $this->render('@App/blockade/blockade_list.html.twig', array());
    }

    /**
     * AJAX - pobiera listę zablokowanych
     * @Route(
     *     "/api/blockades",
     *     name="route_blockade_api_get"
     * )
     */
    public function getBlockadeList()
    {
        $blockades = $this->getDoctrine()->getRepository(Blockade::class)
            ->getBlockedDrivers();

        return $this->json(array(
            'data' => $blockades
        ));
    }

    /**
     * AJAX - pobiera transakcje dla użytkownika
     * @Route(
     *     "/api/transaction",
     *     name="route_blockade_api_get_transaction"
     * )
     */
    public function getTransactionList(Request $request)
    {
        $driver = $request->request->get('driver');
        $transactionType = $request->request->get('transactionType');

        $repo = $this->getDoctrine()->getRepository(Blockade::class);
        $data = $repo->findTransactionsForDriver($driver, $transactionType);

        return $this->json($data);
    }

    /**
     * @Route(
     *     "/dodaj",
     *     name="route_blockade_add"
     * )
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $blockade = new Blockade();
        $form = $this->createForm(BlockadeAddForm::class, $blockade);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $driver = $this->getDoctrine()->getRepository(Driver::class)
                    ->find($blockade->getDriver()->getId());
                $driver->setBlocked(true);

                $blockade->setUser($this->getUser());

                $em->persist($blockade);
                $em->persist($driver);
                $em->flush();

                return $this->redirectToRoute('route_blockade_add');
            }
        }

        return $this->render('@App/blockade/blockade_add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *     "/delete-blockade",
     *     name="route_blockade_delete"
     * )
     */
    public function deleteAction(Request $request)
    {
        $driverId = $request->request->get('driverId');
        $blockadeId = $request->request->get('blockadeId');

        /**
         * @var Blockade $blockade
         */
        $blockade = $this->getDoctrine()->getRepository(Blockade::class)
            ->find($blockadeId);
        $driver = $this->getDoctrine()->getRepository(Driver::class)
            ->find($driverId);

        $driver->setBlocked(false);
        $blockade
            ->setDeleteDate(new \DateTime())
            ->setIsActive(false)
            ->setUser($this->getUser());


        $manager = $this->getDoctrine()->getManager();
        $manager->persist($driver);
        $manager->persist($blockade);
        $manager->flush();

        return $this->json(array(
            "status" => "ok",
            "message" => "Blokada został usunięty z systemu!"
        ));

    }
}
