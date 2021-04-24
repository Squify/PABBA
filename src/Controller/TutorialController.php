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
        $connect = 0;
        $client = new Google_Client();
/*        $client = new Client();
        $client->setAuthConfig(realpath(__DIR__ . '/../../var/credentials.json'));
        $client->setApplicationName("test upload");*/
        $client->setClientId('972402334252-o6l8kn2quaqsko00ejmlnaf0403hd1jd.apps.googleusercontent.com');
        $client->setClientSecret('HTHz_hdrgPxFiatJODd3sX7e');
        $client->setRedirectUri('http://localhost:33/tutoriel/upload');
        $client->setScopes(['https://www.googleapis.com/auth/youtube']);

        $youtube = new Google_Service_YouTube($client);
        if(isset( $_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            dump($token);
            $connect = 1;
        }

        // gérer le fait de ne pas avoir à se reconnecter à chaque fois qu'on reload la page
        /*  if(isset($_SESSION['token']))
        {
            $client->setAccessToken($_SESSION['token']);
        }*/
        dd(__DIR__);
        if($client->getAccessToken()) {

            $snippet = new \Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle('Vidéo de test');
            $snippet->setDescription('Test de description');
            $snippet->setTags(['tag1', 'tag2', 'tag3']);
            $snippet->setCategoryId('17');

            $status = new \Google_Service_YouTube_VideoStatus();
            $status->setPrivacyStatus('private'); // PRIVATE / UNLISTED / PUBLIC


            $video = new \Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);

            $client->setDefer(true);
            $request = $youtube->videos->insert('status,snippet', $video);
            $file = dirname(__DIR__).'/video.mp4';
            $media = new \Google_Http_MediaFileUpload($client, $request, 'video/*', file_get_contents($file));

            $video = $client->execute($request);
            dump($video);
        }

        return $this->render("tutorial/upload.html.twig", [
            'client' => $client,
            'authUrl' => $client->createAuthUrl(),
            'connect' => $connect,
        ]);
    }

}
