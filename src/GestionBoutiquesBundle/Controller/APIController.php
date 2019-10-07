<?php

namespace GestionBoutiquesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class APIController extends Controller
{
    public function selectAllBoutiquesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $boutiques = $em->getRepository('GestionBoutiquesBundle:Boutique')->findAll();
        $normalizer = new ObjectNormalizer();
        ///
        //$ser = new Serializer(array(new ObjectNormalizer()));
        //$data = $ser->normalize($boutiques);
        ///
        ///
        $ser = new Serializer(array($normalizer));
        $data = $ser->normalize($boutiques);
        return new JsonResponse($data);
    }

    public function selectAllProduitsBtAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findProduitsBoutique($id);

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        //$normalizers = array($normalizer);

        $ser = new Serializer(array($normalizer));
        $data = $ser->normalize($produits);
        return new JsonResponse($data);
    }
}
