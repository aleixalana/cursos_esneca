<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cursos;

use App\Form\CursosFormType;

final class CursosController extends AbstractController
{
    // Injectar el entityManagerInterface al constructor per utilitzar-lo als mètodes
    private $em;

    public function __construct(
        EntityManagerInterface $em,
    ){
        $this->em = $em;
    }

    // LLISTAR CURSOS
    #[Route('/cursos', name: 'cursos_inici')]
    public function index(): Response
    {
        $dades_cursos = $this->em->getRepository(Cursos::class)->findBy([], ['id' => 'ASC']);

        return $this->render('cursos/index.html.twig', [
            'cursos' => $dades_cursos
        ]);
    }

    // AFEGIR o EDITAR CURS (Pagina Formulari)
    // Es fa servir la mateixa ruta per donar d'altar i editar cursos seguint les pautes indicades.
    #[Route('/cursos/afegir', name: 'cursos_afegir')]
    public function curs_afegir(Request $request): Response
    {
        
            
        // Formulari sense vinculació a Entitat Cursos (evitar validació automàtica del FrameWork)
        $form = $this->createForm(CursosFormType::class, null);
            
        $form->handleRequest($request);
        $tokenEnviat = $request->request->get('tokenAleix');
            
        $camps = null;

        // SI FORMULARI S'HA ENVIAT
        if ($form->isSubmitted()) {
            
            // Comprovar TOKEN
            if (!$this->isCsrfTokenValid('curs_token', $tokenEnviat)) {
                
                $this->addFlash('css', 'warning');
                $this->addFlash('missatge', 'Token no vàlid');
                
                return $this->redirectToRoute('cursos_afegir');

            }

            $camps = $form->getData();
            $nom            = $camps['nom'];
            $dataInici      = $camps['data_inici'];
            $dataFi         = $camps['data_fi'];
            $datduracioaFi  = $camps['duracio'];
            $preu           = $camps['preu'];
            

            $this->addFlash('css', 'success');
            $this->addFlash('missatge', 'El curs ' . $nom . ' s\'ha creat correctament.');

            return $this->redirectToRoute('cursos_inici');

        }
            
        // FORMULARI (GENERAR HTML) - s'ha separat el formulari la de vista en un arxiu apart
        $plantillaFormulariAfegir = $this->render('cursos/formularis/afegir.html.twig', [
            'formulari_afegir' => $form,
            ])->getContent();
        
        // GENERAR VISTA de creació de curs (es passa el codi del formulari)
        return $this->render('cursos/curs.html.twig', [
            'opcio' => 'afegir',
            'formulariHtml' => $plantillaFormulariAfegir
        ]);


        // si no coincideix amb cap opció, redirigeix a llistat de cursos
        return $this->redirectToRoute('cursos_inici');

    }




/*
    #[Route('/cursos/editar/{id}', name: 'cursos_inici')]
    public function curs_editar(int $id, Request $request): Response
    {
        // Obtenir curs
        $entity = $this->em->getRepository(Cursos::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('El curs amb ID ' . $id . ' no existeix.');
        }

        return $this->render('cursos/editar.html.twig', [
            //'cursos' => $dades_cursos
        ]);
    }
        */
}
