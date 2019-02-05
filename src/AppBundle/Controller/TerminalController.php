<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Terminal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/terminal"
 * )
 */
class TerminalController extends Controller
{
    /**
     * @Route(
     *     "/delete/{id}/{hash}",
     *     name="route_terminal_delete_by_id"
     * )
     */
    public function deleteAction(Request $request, $id, $hash)
    {
        $terminal = $this->getDoctrine()->getRepository(Terminal::class)
            ->findOneBy(array(
                "id" => $id,
                "hash" => $hash
            ));
        if(NULL == $terminal)
        {
            return $this->json(array(
                "status" => "error",
                "message" => "Błąd usuwania!"
            ));
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($terminal);
        $manager->flush();

        return $this->json(array(
            "status" => "ok",
            "message" => "Terminal został usunięty z systemu!"
        ));

    }
}
