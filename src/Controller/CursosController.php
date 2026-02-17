<?php

namespace App\Controller;

use App\Entity\Cursos;
use App\Form\CursosFormType;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\FormService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;


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

        // RENDERITZA LA TAULA del llistat de cursos
        $taulaCursosHtml = $this->render('cursos/taulaCursos.html.twig', [
            'cursos' => $dades_cursos,
            ])->getContent();

        return $this->render('cursos/index.html.twig', [
            'taulaCursosHtml' => $taulaCursosHtml
        ]);
    }

    // LLISTAR Cursos amb AJAX
    // Al polsar sobre el botó "Refrescar" de la vista
    #[Route('/cursos-llistat-ajax', name: 'cursos_llistat_AJAX',  methods: ['GET'])]
    public function mostrarLlistatCursos_AJAX(
        Request $request
    ) {
        if ($request->isXmlHttpRequest()) {

            $dades_cursos = $this->em->getRepository(Cursos::class)->findBy([], ['id' => 'ASC']);
            
            return $this->render('cursos/taulaCursos.html.twig', [
                'cursos' => $dades_cursos
            ]);

        } else {
            throw new \Exception("Anti Hack webmaster");
        }
    }


    // AFEGIR CURS - Pagina Formulari
    #[Route('/cursos/afegir', name: 'cursos_afegir')]
    public function curs_afegir(
        Request $request,
        FormService $formService
    ): Response 
    {
        
        // Formulari sense vinculació a Entitat Cursos (evitar validació automàtica del FrameWork)
        $form = $this->createForm(CursosFormType::class, null);
            
        $form->handleRequest($request);
        $tokenEnviat = $request->request->get('tokenAleix');


        // SI FORMULARI S'HA ENVIAT
        if ($form->isSubmitted()) {
            
            // Comprovar TOKEN CSRF (seguretat)
            if (!$this->isCsrfTokenValid('curs_token', $tokenEnviat)) {
                
                $this->addFlash('css', 'warning');
                $this->addFlash('missatge', 'Token no vàlid');
                return $this->redirectToRoute('cursos_afegir');
            }

            $dadesForm = $form->getData();
            // VALIDEM FORMULARI desde servidor (en cas de manipulacions malintencionades de codi al FrontEnd)
            // a través d'un servei creat:
            // src/Form/FormService.php
            $resValidacio = $formService->validar($dadesForm);

            // HI HA ERRORS
            if (!$resValidacio['success']) {

                $plantillaFormulariAfegir = $this->render('cursos/formularis/afegir.html.twig', [
                    'formulari_afegir' => $form,
                    ])->getContent();
                
                // GENERAR VISTA de creació de curs (es passa el codi del formulari)
                return $this->render('cursos/curs.html.twig', [
                    'opcio' => 'afegir',
                    'formulariHtml' => $plantillaFormulariAfegir,
                    'errors' => $resValidacio['errors']
                ]);
            }

            // Preparar dades per entrar a BBDD
            $entity = new Cursos;
            $entity->setCodi($dadesForm['codi']);
            $entity->setNom($dadesForm['nom']);
            $entity->setDataInici($dadesForm['data_inici']);
            $entity->setDataFi($dadesForm['data_fi']);
            $entity->setDuracio($dadesForm['duracio']);
            $entity->setPreu($dadesForm['preu']);
            
            // GUARDAR a BBDD
            $this->em->persist($entity);
            $this->em->flush();
            
            $this->addFlash('css', 'success');
            $this->addFlash('missatge', $dadesForm['nom'] . ' s\'ha creat correctament');

            // TORNAR AL LLISTAT
            return $this->redirectToRoute('cursos_inici');
        }

        // SI NO ES DETECTA FORMULARI ENVIAT:
            
        // FORMULARI (GENERAR HTML) - s'ha separat el formulari la de vista en un arxiu apart
        $plantillaFormulariAfegir = $this->render('cursos/formularis/afegir.html.twig', [
            'formulari_afegir' => $form,
            ])->getContent();
        
        // GENERAR VISTA de creació de curs (es passa el codi del formulari)
        return $this->render('cursos/curs.html.twig', [
            'opcio' => 'afegir',
            'formulariHtml' => $plantillaFormulariAfegir,
            'errors' => array()
        ]);
    }



    // EDITAR CURS - Pàgina Formulari
    #[Route('/cursos/editar/{id}', name: 'cursos_editar')]
    public function curs_editar(
        Request $request,
        FormService $formService,
        int $id): Response
    {
        // Obtenir curs
        $entity = $this->em->getRepository(Cursos::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('El curs amb ID ' . $id . ' no existeix.');
        }

        // PASEM DADES de la entitat Cursos AL FORMULARI PER EDITAR (Reciclem el mateix form)
        // src/Form/CursosFormType.php
        $form = $this->createForm(CursosFormType::class, null, [
            'dades' => [
                'codi' => $entity->getCodi(),
                'nom' => $entity->getNom(),
                'data_inici' => $entity->getDataInici(),
                'data_fi' => $entity->getDataFi(),
                'duracio' => $entity->getDuracio(),
                'preu' => $entity->getPreu()
            ]
        ]);
            
        $form->handleRequest($request);
        $tokenEnviat = $request->request->get('tokenAleix');


        // SI FORMULARI S'HA ENVIAT
        if ($form->isSubmitted()) {
            
            // Comprovar TOKEN CSRF (seguretat)
            if (!$this->isCsrfTokenValid('curs_token', $tokenEnviat)) {
                
                $this->addFlash('css', 'warning');
                $this->addFlash('missatge', 'Token no vàlid');
                return $this->redirectToRoute('cursos_editar', ['id' => $entity->getId()]);
            }


            $dadesForm = $form->getData();

            // VALIDAR FORMULARI EDITAT
            $resValidacio = $formService->validar($dadesForm);

            // HI HA ERRORS
            if (!$resValidacio['success']) {

                $plantillaFormulariAfegir = $this->render('cursos/formularis/afegir.html.twig', [
                    'formulari_afegir' => $form,
                    ])->getContent();
                
                // GENERAR VISTA de creació de curs (es passa el codi del formulari)
                return $this->render('cursos/curs.html.twig', [
                    'opcio' => 'afegir',
                    'formulariHtml' => $plantillaFormulariAfegir,
                    'errors' => $resValidacio['errors']
                ]);
            }

            // ACTUALITZAR dades del Curs que estem editant
            $entity->setCodi($dadesForm['codi']);
            $entity->setNom($dadesForm['nom']);
            $entity->setDataInici($dadesForm['data_inici']);
            $entity->setDataFi($dadesForm['data_fi']);
            $entity->setDuracio($dadesForm['duracio']);
            $entity->setPreu($dadesForm['preu']);

            $this->em->flush();
            

            $this->addFlash('css', 'success');
            $this->addFlash('missatge', $dadesForm['nom'] . ' s\'ha actualitzat correctament');

            // TORNAR AL LLISTAT
            return $this->redirectToRoute('cursos_inici');$this->em->flush();

        }


        // FORMULARI (GENERAR HTML) - s'ha separat el formulari la de vista en un arxiu apart
        $plantillaFormulariEditar = $this->render('cursos/formularis/editar.html.twig', [
            'id' => $id,
            'formulari_editar' => $form,
            ])->getContent();
        
        // GENERAR VISTA de creació de curs (es passa el codi del formulari)
        return $this->render('cursos/curs.html.twig', [
            'opcio' => 'editar',
            'formulariHtml' => $plantillaFormulariEditar,
            'errors' => array()
        ]);
    }



    // ELIMINAR CURSOS per ID
    #[Route('/cursos/eliminar/{id}', name: 'cursos_eliminar')]
    public function eliminar(Request $request, $id) {
        
        $entity = $this->em->getRepository(Cursos::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('El curs amb ID ' . $id . ' no existeix.');
        }

        $nomCurs = $entity->getNom();
        
        // Eliminar curs de la Base de Dades
        $this->em->remove($entity);
        $this->em->flush();
        
        $this->addFlash('css', 'success');
        $this->addFlash('missatge', $nomCurs . ' eliminat correctament');

        return $this->redirectToRoute('cursos_inici');
    }


    // LLISTAR CURSOS
    #[Route('/cursos/suma-hores', name: 'cursos_suma_hores_AJAX',  methods: ['GET'])]
    public function sumaHores_AJAX(
        Request $request
    ) {
        if ($request->isXmlHttpRequest()) {

            // CÀLCUL HORES per Consulta SQL: el cálcul es fa a través de consulta a la BBDD per major eficiència
            // Arxiu: src/Entity/CursosRepository.php
            $totalDuracio = $this->em->getRepository(Cursos::class)->sumarHoresTotalsCursos();
            
            return new JsonResponse([
                'totalDuracio' => $totalDuracio
            ]);

        } else {
            throw new \Exception("Anti Hack webmaster");
        }
        
    }
}
