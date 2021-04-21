<?php


namespace App\Controller;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Google\Client;
use Google_Client;
use Google_Service_YouTube;
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
        $client = new Client();
        $client->setAuthConfig(realpath(__DIR__ . '/../../var/credentials.json'));
        $client->setApplicationName("test upload");
        $client->setScopes(['https://www.googleapis.com/auth/youtube.upload']);
        $youtube = new Google_Service_YouTube($client);

        $response = $youtube->videos->insert(
            'id',
            new \Google_Service_YouTube_Video(),
            [
                'data' => file_get_contents('/Users/benjamin/Pictures/Screenshot/test.mov'),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
        dump($response);
        die;


        return $this->render("tutorial/upload.html.twig", [
            'client' => $client,
            'authUrl' => $client->createAuthUrl(),
        ]);
    }

}
