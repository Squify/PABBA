<?php


namespace App\Controller;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Algolia\SearchBundle\SearchService;


class TutorialController extends AbstractController
{



    /**
     * @Route("/tutoriel", name="tutorial_list")
     * @return Response
     */
    public function list()
    {
        $client = new Google_Client();
        $client->setDeveloperKey('AIzaSyCOFBDunCLrvJ9378MUll0fMWsVaVU-gmc');

        $youtube = new \Google_Service_YouTube($client);
        $response = $youtube->search->listSearch('id,snippet', [
            'q' => 'racoon',
            'order' => 'relevance',
            'maxResults' => 10,
            'type' => 'video'
        ]);


        $first = $youtube->videos->listVideos('id,snippet,contentDetails', [
            'id' => $response['items'][0]['id']['videoId']
        ]);
        return $this->render("tutorial/list.html.twig", [
            'item' => $response['items'],
            'first' => $first['items'][0],
        ]);
    }

    /**
     * @Route("/tutoriel/upload", name="tutorial_upload")
     */
    public function upload()
    {
        $client = new Google_Client();
        $client->setClientId('972402334252-o6l8kn2quaqsko00ejmlnaf0403hd1jd.apps.googleusercontent.com');
        $client->setClientSecret('HTHz_hdrgPxFiatJODd3sX7e');
        $client->setRedirectUri('http://localhost:33/tutoriel/upload');
        $client->setScopes(
            ['https://www.googleapis.com/auth/youtube.upload']
        );
        $client->setAccessType('offline');

        $youtube = new \Google_Service_YouTube($client);


        return $this->render("tutorial/upload.html.twig", [
            'client' => $client,
            'authUrl' => $client->createAuthUrl(),
        ]);
    }

}