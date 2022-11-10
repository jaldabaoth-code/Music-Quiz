<?php

namespace App\Controller;

use App\Form\QuizType;
use App\Service\Music;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public $compteur = 0;

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, Music $music, SessionInterface $session): Response
    {

        $form = $this->createForm(QuizType::class);
        $form->handleRequest($request);

        $name1 = "";
        $artist = null;
        $pictures = null;
        $tracks = null;
        $albums = null;


        $artistById1 = $music->getArtistById(rand(1, 4000));
        $artistById2 = $music->getArtistById(rand(1, 4000));
        $artistById3 = $music->getArtistById(rand(1, 4000));

        $name1 = $artistById1["name"];
        $name2 = str_replace(" ", "%20", $name1);

        $artist = "";
        $artists = $music->getArtist($name2, $name1);
        if (isset($artists[0])) {
            $artist = $artists[0];
        } else {
            $artist = $artists;
        }
        $albums = $music->getAlbums($artist['id']);
        $pictures = $music->getPicture($artist['id']);
        $tracks = $music->getTracks($artist['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            dd($_POST);
        }
        return $this->render('index.html.twig', [
            'pictures' => $pictures,
            'albums' => $albums,
            'tracks' => $tracks,
            'artists' => [$artist, $artistById1, $artistById2, $artistById3]
        ]);
    }
}
