<?php


namespace App\Managers;


use App\Entity\CuentaZoom;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;

class ZoomManager
{
    private $em;

    public function __construct(EntityManagerInterface $manager, ManagerRegistry $registry, string $client_id, string $client_secret)
    {
//        parent::__construct($registry, CuentaZoom::class);

        $this->em = $manager;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function createMeeting(CuentaZoom $client, Lesson $lesson)
    {

        try {
            $meeting = $this->zoomRequest('https://api.zoom.us/v2/users/me/meetings', [
                "topic" => $lesson->getTitulo(),
                "type" => 2,
                "start_time" => $lesson->getDate()->format("Y-m-d H:i:s"),
                "password" => $lesson->getPassword(),
            ], "POST", $client->getAccessToken());
            $lesson->setLink($meeting["join_url"]);
            $lesson->setZoomPayload(json_encode($meeting));

            return $lesson;
        } catch (\Exception $ex) {
            $refreshToken = $client->getRefreshToken();

            $newClient = $this->refreshToken($refreshToken);

            if (array_key_exists("access_token", $newClient)) {
                $client = new CuentaZoom();
                $client->setAccessToken($newClient["access_token"]);
                $client->setRefreshToken($newClient["refresh_token"]);

                $this->em->persist($client);
                $this->em->flush();

                $this->createMeeting($client, $lesson);
            }
        }
//        dd($client);
    }

    private function refreshToken($refreshToken)
    {
        $ch = curl_init("https://zoom.us/oauth/token?".http_build_query([
                'grant_type' => 'refresh_token',
                "refresh_token" => $refreshToken
            ])
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
        ]);

        $response = curl_exec($ch);

        return json_decode($response, true);
    }

    public function zoomOAuth($code, $redirect)
    {

        $ch = curl_init("https://zoom.us/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic  " . base64_encode($this->client_id . ':' . $this->client_secret),
        ]);
        $response = curl_exec($ch);
        return json_decode($response, true);
    }


    public function zoomRequest($url, $fields_string, $method = 'POST', $token = FALSE, $headers = array())
    {

        if (!is_array($headers)) {
            $headers = array();
        }

        if ($token) {
            $headers[] = "Authorization: Bearer " . $token;
        } else {
            $headers[] = "Authorization: Basic  " . base64_encode($this->client_id . ':' . $this->client_secret);
        }

        if ($method == 'GET') {
            $url .= '?' . $fields_string;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            //        CURLOPT_SSL_VERIFYPEER => true,
            //        CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            //CURLOPT_USERPWD => ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET,
        ]);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Connection: Keep-Alive',
                "Authorization: Bearer " . $token
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields_string));
        }

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $response = curl_exec($curl);

        return json_decode($response, true);

        $err = curl_error($curl);


        rewind($verbose);
        $log = stream_get_contents($verbose) . "\n";
        //var_dump($fields_string);
        //var_dump($headers);
        //var_dump($response);

        curl_close($curl);

        if ($err) {
            $error = array("Status" => 0, "Message" => "cURL Error #:" . $err, "Log" => $log);
        } else {
            $object = json_decode($response, TRUE);
            if ($object) {
                return $object;
            }
        }

        $error = array("Status" => 0, "Message" => "cURL Response #:" . $response);

        return $error;
    }
}
