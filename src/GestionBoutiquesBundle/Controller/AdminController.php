<?php

namespace GestionBoutiquesBundle\Controller;

//use GestionBoutiquesBundle\Entity\Gerant;
use GestionBoutiquesBundle\Entity\Boutique;
use GestionBoutiquesBundle\Entity\Gerant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TestBundle\Entity\User;

class AdminController extends Controller
{
    public function verifierDemandesAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $demandes = $em->getRepository('GestionBoutiquesBundle:Gerant')->findBy(array('etat' => false));
        if ($request->isXmlHttpRequest()) {
            if ($request->get('val') == 'refuser') {
                if ($request->get('id_demande')) {
                    $id_gerant = $request->get('id_demande');

                    $gerant = $em->getRepository("GestionBoutiquesBundle:Gerant")->findOneBy(array('id' => $id_gerant));
                    $em->remove($gerant);
                    $em->flush();

                    $demandes = []; //$em->getRepository('GestionBoutiquesBundle:Gerant')->findBy(array('etat' => false));
                    //$demandes = $em->getRepository('GestionBoutiquesBundle:Gerant')->findAll();

                    $ser = new Serializer(array(new ObjectNormalizer()));
                    $data = $ser->normalize($demandes);
                    dump($data);
                    return new JsonResponse(array('data' => $data));

                }
            } elseif ($request->get('val') == 'accepter')
            {
                $id_gerant = $request->get('id_demande');
                $gerant = $em->getRepository("GestionBoutiquesBundle:Gerant")->findOneBy(array('id' => $id_gerant));
                $boutique = new Boutique();
                $boutique->setGerant($gerant);
                $boutique->setLieu('changeme');
                $boutique->setDescription('changeme');
                $boutique->setNomBoutique('changeme');
                $now = new \DateTime();
                $boutique->setDateCeation($now);
                $gerant->setBoutique($boutique);
                $gerant->setEtat(true);

                $em->persist($boutique);
                $em->flush();

                $retour = [];
                $ser = new Serializer(array(new ObjectNormalizer()));
                $data = $ser->normalize($retour);
                dump($data);
                return new JsonResponse(array('data' => $data));

            }
        }
        return $this->render('GestionBoutiquesBundle:Admin:verifier_demandes.html.twig', array('demandes' => $demandes));
    }

    public function affecterBoutiqueAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('TestBundle:User')->find($request->get('user_id'));
            $gerant = $em->getRepository('GestionBoutiquesBundle:Gerant')->findOneBy(array('user' => $user));
            return $this->render('GestionBoutiquesBundle:Admin:affecter_boutique.html.twig', array('user' => $user, 'gerant' => $gerant));
        }
        /*if($request->isXmlHttpRequest())
        {
            $msg='succes';
            $ser= new Serializer(array(new ObjectNormalizer()));
            $data=$ser->normalize($msg);
            return new JsonResponse($data);
        }*/

        return $this->render('GestionBoutiquesBundle:Admin:affecter_boutique.html.twig', array());
    }

    public function testAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user = new Gerant();
            $user = [];
            $id = $request->get('var1');
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('TestBundle:User')->find($id);


            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($user);
            return new JsonResponse(array('data' => $data));

            /*$encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);

            $data = $serializer->serialize($user, 'json');

            return new Response(json_encode(array('data'=>$data)));*/
            // return new JsonResponse(array('$data'=>json_encode($user)));

        }

        return $this->render('GestionBoutiquesBundle:Admin:test.html.twig', array());
    }

    public function testapiAction()
    {
        $user = new User();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $boutiques=$em->getRepository('GestionBoutiquesBundle:Boutique')->findAll();
        return $this->render('@GestionBoutiques/Api/index.html.twig', array('user'=>$user,'boutiques'=>$boutiques));
    }

    public function supprimerBoutiqueAction()
    {
        return $this->render('@GestionBoutiques/Admin/layout.html.twig');
    }

}
