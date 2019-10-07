<?php

namespace GestionBoutiquesBundle\Controller;

use GestionBoutiquesBundle\Entity\Commande;
use GestionBoutiquesBundle\Entity\ProduitBoutique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PanierController extends Controller
{
    public function ajoutAuPanierAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $session = new Session();
            $panier = $session->get('panier');
            $tab = [];
            $produitsPanier = array();
            if (!isset($panier)) {
                $session->start();

                $produitsPanier[] = $request->get('produit_id');
                $user_id = $request->get('user_id');
                $session->set('panier', true);
                $session->set('user_id', $user_id);
                $session->set('produitsPanier', $produitsPanier);
            } else {

                $produit_id = $request->get('produit_id');
                $produitsPanier = $session->get('produitsPanier');
                $produitsPanier[] = $produit_id;
                $session->set('produitsPanier', $produitsPanier);
                $tab = $produitsPanier;
            }
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));


        }
    }


    public function afficherPanierAction()
    {
        // UTILITAIRE
        function in_array_r($needle, $haystack, $strict = false)
        {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }
            return false;
        }

        ///
        $session = new Session();
        $test = $session->get('panier');
        if (isset($test)) {
            $panier = array(array());
            $panier[0]['id_produit'] = 0;
            $panier[0]['qtite'] = 0;

            $produitsPanier = $session->get('produitsPanier');
            foreach ($produitsPanier as $produit) {
                if (!in_array_r($produit, $panier)) {
                    $count = 0;
                    foreach ($produitsPanier as $produit2) {
                        if ($produit2 == $produit)
                            $count++;
                    }
                    echo($count);
                    $panier[] = array('id_produit' => $produit, 'qtite' => $count);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $produits = [];
            foreach ($panier as $unproduit) {
                $id_prod = $unproduit['id_produit'];
                $produits[] = $em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findOneBy(array('id' => $id_prod));
            }
            return $this->render('@GestionBoutiques/Panier/panier.html.twig', array('produits' => $produits, 'panier' => $panier));
        } else
            return $this->render('@GestionBoutiques/Panier/panier.html.twig', array('msg' => 'Vous n\'avez pas de panier.'));
    }

    public function suppDeMonPanierAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $session = new Session();
            $produitsPanier = [];
            $produitsPanier = $session->get('produitsPanier');
            $produit_id = $request->get('produit_id');
            foreach ($produitsPanier as $key => $value)
            {
                if($value==$produit_id)
                    unset($produitsPanier[$key]);
            }
            array_values($produitsPanier);
            $session->set('produitsPanier', $produitsPanier);
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($produitsPanier);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function creerCommandeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $session = new Session();
            $produitsPanier = $session->get('produitsPanier');
            $user_id = $request->get('user_id');
            $em=$this->getDoctrine()->getManager();
            $user = $em->getRepository('TestBundle:User')->find($user_id);
            $quantite = 0;
            $now = new \DateTime();
            foreach ($produitsPanier as $item) {
                $count = 0;
                foreach ($produitsPanier as $item2) {
                    if ($item == $item2)
                        $count++;
                }

                //

                foreach ($produitsPanier as $key => $value)
                {
                    if($value==$item)
                        unset($produitsPanier[$key]);
                }
                array_values($produitsPanier);

                //
                if($count!=0){
                $quantite = $count;
                $produit=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->find($item);
                $commande = new Commande();
                $commande->setUser($user);
                $commande->setCloturee(false);
                $commande->setDateCreation($now);
                $commande->setProduit($produit);
                $commande->setQuantite($quantite);

                $em->persist($commande);
                $em->flush();
                }

            }
            $new = [];
            $produitsPanier = $new;
            $session->set('produitsPanier', $produitsPanier);
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($produitsPanier);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

}