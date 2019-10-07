<?php

namespace GestionBoutiquesBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use GestionBoutiquesBundle\Entity\Boutique;
use GestionBoutiquesBundle\Entity\Categorie;
use GestionBoutiquesBundle\Entity\Gerant;
use GestionBoutiquesBundle\Entity\ProduitBoutique;
use GestionBoutiquesBundle\Form\ProduitBoutiqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TestBundle\Entity\User;

class GestionController extends Controller
{
    /*public function demandeBoutiqueAction(Request $request)
    {
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $usr = $this->getUser();
        $valide = 0;

        $em = $this->getDoctrine()->getManager();
        $gerant = $em->getRepository('GestionBoutiquesBundle:Gerant')->findOneBy(array('user' => $usr));

        if ($gerant) {
            if ($gerant->getEtat() == false) {
                //Demande déja envoyée
                $valide = 1;
            }

            if ($gerant->getEtat() == true) {
                //Déja gérant d'une boutique
                $valide = 2;
            }
        }

        if ($request->isMethod('POST')) {
            $dmdgerant = new Gerant();
            $dmdgerant->setEtat(false);
            $dmdgerant->setUser($usr);
            $date = "1-1-1990";
            $dmdgerant->setDateDebut(new \DateTime($date));
            $valide = 1;

            $em->persist($dmdgerant);
            $em->flush();
            return $this->render('GestionBoutiquesBundle:Gestion:demande_boutique.html.twig', array('usr'=>$usr,'valide'=>$valide));
        }

        if ($gerant==null) {
            return $this->render('GestionBoutiquesBundle:Gestion:demande_boutique.html.twig', array('usr'=>$usr,'valide'=>$valide));
        }

        return $this->render('GestionBoutiquesBundle:Gestion:demande_boutique.html.twig', array('usr' => $usr, 'valide' => $valide));
    }*/


    public function SupprimerBoutiquesAction()
    {
        return $this->render('GestionBoutiquesBundle:Gestion:supprimer_boutiques.html.twig', array(// ...
        ));
    }


    //Envoi de demande ou attente traitement ou espace boutique
    public function espaceBoutiqueAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $gerant = $em->getRepository('GestionBoutiquesBundle:Gerant')->findOneBy(array('user' => $user));

        if (!$gerant) {
            return $this->render('@GestionBoutiques/Gestion/demande_boutique.html.twig', array('user_id' => $user->getId()));
        }

        $msg = '';
        if ($gerant->getEtat() == false) {
            $msg = 'Votre demande n\'a pas encore été traitée';
            return $this->render('@GestionBoutiques/Gestion/espace_boutique.html.twig', array('msg' => $msg));
        } elseif ($gerant->getEtat() == true) {
            //$boutique = new Boutique();
            //$boutique=$em->getRepository('GestionBoutiquesBundle:Boutique')->findOneBy('');
            $produits = new ProduitBoutique();
            $produits = $em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findBy(array('boutique' => $gerant->getBoutique()));

            $categories = new Categorie();
            $categories = $em->getRepository('GestionBoutiquesBundle:Categorie')->findAll();

            $boutique = new Boutique();
            $boutique = $gerant->getBoutique();
            $id_boutique = $boutique->getId();

            //Selection des 3 derniers prods vendus
            $idProdsVendu = $em->getRepository('GestionBoutiquesBundle:Commande')->findDernierProdsDQL($gerant->getBoutique()->getId());
            $prodVendu = [];//new ProduitBoutique();
            foreach ($idProdsVendu as $id_prod)
            {
                $prodVendu[]=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->find($id_prod);
            }


            //BLOCK FORMULAIRE
            $produit = new ProduitBoutique();

            $form = $this->createForm(ProduitBoutiqueType::class, $produit);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $file = $form['image']->getData();
                $file->move('uploads/images', $file->getClientOriginalName());
                $produit->setImage("uploads/images/" . $file->getClientOriginalName());
                $produit->setBoutique($boutique);
                $now = new \DateTime();
                $produit->setDateDepot($now);
                $em->persist($produit);
                $em->flush();
                return $this->redirectToRoute('espace_boutique');
            }
            //FIN FORMULAIRE
            return $this->render('@GestionBoutiques/Gestion/espace_boutique.html.twig', array('msg' => $msg, 'produits' => $produits,
                'categories' => $categories, 'id_boutique' => $id_boutique,
                'boutique' => $boutique,'form'=>$form->createView(),
                'prodVendu'=>$prodVendu));
        }
        return $this->render('@GestionBoutiques/Gestion/espace_boutique.html.twig', array('msg' => $msg));
    }

    //ajout gerant avec etat = 0
    public function envoiDemandeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('user_id');
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('TestBundle:User')->find($id);
            $gerant = new Gerant();
            $gerant->setUser($user);
            $gerant->setEtat(0);
            $now = new \DateTime();
            //echo $now->format('Y-m-d');
            $gerant->setDateDebut($now);
            $em->persist($gerant);
            $em->flush();

            //$user=$em->getRepository('TestBundle:User')->find(4);
            $tab = [];
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function ajoutProduitAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //data: {'nom_p':nom_p,'desc_p':desc_p,'prix_p':prix_p,'id_categorie':id_categorie,'id_boutique':id_boutique},
            $nom_p = $request->get('nom_p');
            $desc_p = $request->get('desc_p');
            $prix_p = $request->get('prix_p');
            $id_categorie = $request->get('id_categorie');
            $id_boutique = $request->get('id_boutique');

            $produit = new ProduitBoutique();
            $produit->setNomProduit($nom_p);
            $produit->setDescription($desc_p);
            $produit->setPrix($prix_p);
            $now = new \DateTime();
            $produit->setDateDepot($now);

            $boutique = $em->getRepository('GestionBoutiquesBundle:Boutique')->find($id_boutique);
            $categorie = $em->getRepository('GestionBoutiquesBundle:Categorie')->find($id_categorie);

            $produit->setCategorie($categorie);
            $produit->setBoutique($boutique);

            $em->persist($produit);
            $em->flush();

            $tab = [];
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function modifierBoutiqueAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            /*'nom_b': nom_b,
                        'desc_b': desc_b,
                        'lieu_b': lieu_b,
                        'id_boutique': id_boutique*/
            $em = $this->getDoctrine()->getManager();
            $id_boutique = $request->get('id_boutique');
            $boutique = new Boutique();
            $boutique = $em->getRepository('GestionBoutiquesBundle:Boutique')->find($id_boutique);
            $nom_b = $request->get('nom_b');
            $desc_b = $request->get('desc_b');
            $lieu_b = $request->get('lieu_b');
            $boutique->setNomBoutique($nom_b);
            $boutique->setDescription($desc_b);
            $boutique->setLieu($lieu_b);

            $em->persist($boutique);
            $em->flush();

            $tab = [];
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function ajoutimgAction(Request $request)
    {
        $produit = new ProduitBoutique();

        /*if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            //$file = $request->get('image')->getData();
            //$file->move('uploads/images', $file->getClientOriginalName());
            //$modele->setImage("uploads/images/" . $file->getClientOriginalName());
            $file = $request->get('image');
            $produit->setImage($file);
            //$produit->setImage("uploads/images/" . $file->getClientOriginalName());
            $produit->setDescription("testimg");
            $produit->setPrix('1000');
            $produit->setNomProduit('testimg');
            $now = new \DateTime();
            $produit->setDateDepot($now);

            $em->persist($produit);
            $em->flush();

        }*/
        $produit = new ProduitBoutique();

        $form = $this->createForm(ProduitBoutiqueType::class, $produit);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $form['image']->getData();
            $file->move('uploads/images', $file->getClientOriginalName());
            $produit->setImage("uploads/images/" . $file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            //return $this->redirectToRoute('accueil');
            return $this->render('@GestionBoutiques/Admin/test.html.twig', array('form' => $form->createView()
                // ...
            ));
        }

        return $this->render('@GestionBoutiques/Admin/test.html.twig');
    }

    public function supprimerProduitAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $em=$this->getDoctrine()->getManager();
            $id_prod=$request->get('id_prod');
            $produit=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->find($id_prod);

            $em->remove($produit);
            $em->flush();

            $tab = [];
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function modifierProduitAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $em=$this->getDoctrine()->getManager();
            $id_prod=$request->get('id_prod');
            $nom_prod=$request->get('nom_prod');
            $desc_prod=$request->get('description_prod');
            $prix_prod=$request->get('prix_prod');
            $produit=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->find($id_prod);
            $produit->setNomProduit($nom_prod);
            $produit->setDescription($desc_prod);
            $produit->setPrix($prix_prod);
            $em->persist($produit);
            $em->flush();

            $tab = [];
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($tab);
            dump($data);
            return new JsonResponse(array('data' => $data));
        }
    }

    public function AfficherBoutiquesAction()
    {
        $em=$this->getDoctrine()->getManager();
        $gerants=$em->getRepository('GestionBoutiquesBundle:Gerant')->findAll();
        $user = new User();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getUser();
        $boutiques=$em->getRepository('GestionBoutiquesBundle:Boutique')->findAll();

        return $this->render('GestionBoutiquesBundle:Gestion:afficher_boutiques.html.twig', array('gerants'=>$gerants,'user'=>$user,'boutiques'=>$boutiques));
    }

    public function AfficherBoutiques2Action()
    {
        $em=$this->getDoctrine()->getManager();
        $gerants=$em->getRepository('GestionBoutiquesBundle:Gerant')->findAll();
        return $this->render('GestionBoutiquesBundle:Gestion:afficher_boutiques2.html.twig', array('gerants'=>$gerants));
    }

    public function afficherProduitsBoutiqueAction(Request $request)
    {
        $id_gerant = $request->get('id_gerant');
        $em=$this->getDoctrine()->getManager();
        $gerant = $em->getRepository('GestionBoutiquesBundle:Gerant')->find($id_gerant);
        ///
        /*$prods = [];
        $prods=$gerant->getBoutique()->
        $categories = [];*/
        $boutiqueid=$gerant->getBoutique()->getId();
        //$categories = $em->getRepository('GestionBoutiquesBundle:Categorie')->findCategoriesBoutiqueDQL($boutiqueid);
        $categories = [];
        $categories_id = $em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findCategoriesBoutique($boutiqueid);
        foreach ($categories_id as $categorie)
        {
            $categories[]=$em->getRepository('GestionBoutiquesBundle:Categorie')->find($categorie);
        }
        ///
        return $this->render('@GestionBoutiques/Gestion/afficher_produits.html.twig', array('gerant'=>$gerant,'categories'=>$categories));
    }

    public function rechercheAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $em=$this->getDoctrine()->getManager();
            $id_boutique = $request->get('id_boutique');
            $valeur = $request->get('valeur');
            $produits = $em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findProduitsBoutiques($id_boutique,$valeur);

            /*$produits = [];
            foreach ($id_produits as $id)
            {
                $produits[]=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->find($id);
            }*/


            return $this->render('@GestionBoutiques/Gestion/resultats_prods_recherche_ajax.html.twig', array('produits'=>$produits));
        }

    }

    public function chartAction()
    {

        function in_array_r($needle, $haystack, $strict = false)
        {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }
            return false;
        }

        $pieChart = new PieChart();
        $em= $this->getDoctrine();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $gerant = $em->getRepository('GestionBoutiquesBundle:Gerant')->findOneBy(array('user' => $user));

        $boutique = $gerant->getBoutique();

        $commandes=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findCommandesBoutiquesVendus($boutique);
        //$commandes=$em->getRepository('GestionBoutiquesBundle:ProduitBoutique')->findAll();

        //$classes = $em->getRepository(User::class)->findAll();
        /*$totalEtudiant=0;
        foreach($classes as $classe) {
            $totalEtudiant=$totalEtudiant+1;
        }*/
        $data= array();
        $stat=['produit', 'nbProduit'];
        //$stat=['classe', 'nbEtudiant'];
        array_push($data,$stat);
        foreach ($commandes as $item)
        {
            $data2=array();
            $count = 0;
            $commande = $item;
            foreach ($commandes as $item2)
            {
                if($item2==$commande);
                $count++;
            }
            $count=$count+$item->getQuantite();
            $stat = [$commande->getProduit()->getNomProduit(),$count];
            if(!in_array($stat,$data)){

                array_push($data,$stat);
            }

        }

        $pieChart->getData()->setArrayToDataTable($data);

        /*$pieChart->getData()->setArrayToDataTable(
            $data
        );*/
        $pieChart->getOptions()->setTitle('Vos produits vendu');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('@GestionBoutiques/Gestion/stats.html.twig', array('piechart' => $pieChart));
    }

}