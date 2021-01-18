<?php

namespace App\Repository;

use App\Entity\CuentaZoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CuentaZoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuentaZoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuentaZoom[]    findAll()
 * @method CuentaZoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentaZoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $client_id, string $client_secret)
    {
        parent::__construct($registry, CuentaZoom::class);

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }


    public function createMeeting()
    {
        $token = 123;
        $response = $this->zoomRequest('/users/me/meetings', http_build_query([

        ]), "POST", $token);

        dd($response);

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
        $response = curl_exec($ch);
        return json_decode($response);
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
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        }

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $response = curl_exec($curl);
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
