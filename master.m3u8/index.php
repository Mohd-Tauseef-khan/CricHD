<?php

//============================== GET_ALL_HEADERS ==============================//

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

//============================== SERVER_URL ==============================//

function ServerUrl()
{

    $url = 'https://crichd.extratvteam.workers.dev/';

    $data = array(
        'Key' => 'extratvteamkey-523f5cd0c0dbd06b3e596889e4ccda1c'
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    $ResponseJson = @json_decode($response, true);

    if (isset($ResponseJson['extra_data']['ServerUrl'])) {

        return @$ResponseJson['extra_data']['ServerUrl'];
    }
}

//============================== SOURCE CODE ==============================//

$headers = getallheaders();

$domain_referer = 'https://stream.crichd.vip/';

$referer = $headers['Referer'];

if (($referer != "") && ($_GET["key"] == 'Extra-Tv')) {

    if (!empty($_GET["cricts"])) {

        $ts_url = $_GET["cricts"];

        // header("Content-Type: video/mp2t");
        header("Connection: keep-alive");
        header("Accept-Ranges: bytes"); {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $ts_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            Curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Accept: */*',
                'Accept-Language: en-IN,en;q=0.9,hi;q=0.8,zh-CN;q=0.7,zh;q=0.6',
                'Connection: keep-alive',
                'Origin: ' . $domain_referer,
                'Referer: ' . $domain_referer,
            ]);

            $rense = curl_exec($curl);
            curl_close($curl);
        }
        echo $rense;
    }

    if (!empty($_GET["c"])) {

        $type = @$_GET["c"];

        header("Content-Type: application/vnd.apple.mpegurl");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Expose-Headers: Content-Length,Content-Range");
        header("Access-Control-Allow-Headers: Range");
        header("Accept-Ranges: bytes");

        if (ServerUrl() !== '') {

            $url = ServerUrl() . ".mobile&live=" . $type;
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "gzip",
                CURLOPT_REFERER => 'https://stream.crichd.vip/',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36"
                ]
            ]);

            $player_response = curl_exec($curl);
            curl_close($curl);
            // echo ServerUrl();
        }

        $temp = str_replace('"', '', $player_response);
        $temp2 = str_replace(',', '', $temp);
        $gen_data = str_replace('\/', '/', $temp2);
        preg_match_all('#https(.*)]#', @$gen_data, $matches);
        if (!empty($matches[0][0])) {
            $gen_url = str_replace(']', '', $matches[0][0]);
            preg_match_all('#http(.*)/hls/#', $gen_url, $match);

            $domain = $match[0][0];

            $domain = str_replace("////", "//", $domain);
            $domain = str_replace("https://", "", $domain);
            $gen_url = str_replace("////", "//", $gen_url); {

                $cur = curl_init();
                curl_setopt($cur, CURLOPT_URL, $gen_url);
                curl_setopt($cur, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cur, CURLOPT_CUSTOMREQUEST, 'GET');
                Curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($cur, CURLOPT_HTTPHEADER, [
                    'Accept: */*',
                    'Accept-Language: en-IN,en;q=0.9,hi;q=0.8,zh-CN;q=0.7,zh;q=0.6',
                    'Connection: keep-alive'
                ]);

                $respone_ = curl_exec($cur);
                curl_close($cur);
            }

            preg_match_all('#/hls/(.*).m3u8#', $gen_url, $typer);

            if (stripos($respone_, "#EXTM3U") !== false) {
                $ts_list = str_replace($typer[1][0], '?key=Extra-Tv&cricts=' . $domain . $typer[1][0], $respone_);
                echo $ts_list;
            }
        }
    }
} else {
    $redirect_url = 'https://telegram.dog/Extra_Tv_Team_Official';
    header('Location: ' . $redirect_url);
}
