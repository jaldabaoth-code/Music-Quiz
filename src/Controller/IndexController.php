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
    /**
     * @Route("/", name="index")
     */
    public function index(Music $music): Response
    {
        // Generate a random id
        $artistById1 = $music->getMusicData(rand(1, 9), '');
        $artistById2 = $music->getMusicData(rand(1, 4000), '');
        $artistById3 = $music->getMusicData(rand(1, 4000), '');
        $artists = $music->getArtist($artistById1["name"]);
        $artist = isset($artists[0]) ? $artist = $artists[0] : $artist = $artists;
        $tracks = $music->getMusicData($artist['id'], '/tracks');
        /* We don't use them because, we didn't finished the Hackathon */
        // $albums = $music->getMusicData($artist['id'], '/albums');
        // $pictures = $music->getMusicData($artist['id'], '/pictures');
        return $this->render('index.html.twig', [
            'tracks' => $tracks['data']['item'],
            'artists' => [$artist, $artistById1, $artistById2, $artistById3]
        ]);
    }
}
