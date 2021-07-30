<?php

namespace App\Controllers;

use App\Models\UrlsModel;
use App\Models\UrlsStatisticsModel;
use App\Libraries\Ciqrcode;
use \DateTime;
use \DateInterval;
use \DatePeriod;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use function file_exists;
use function mkdir;

class UrlsController extends BaseController
{

    use ResponseTrait;

    public function __construct()
    {
        helper('cookie');
    }

    public function insert(): Response
    {
        try {
            if ($this->validate([
                'url' => 'required|valid_url|max_length[1000]'
            ])) {

                $urls_full = prep_url($this->request->getPost('url'));
                $urls_short =  $this->generaterandomString();

                $data = [
                    'urls_full' =>  $urls_full,
                    'urls_short'    => $urls_short,
                    'urls_cid'    => $_COOKIE['auth_cookie_id']
                ];

                $UrlsModel = new UrlsModel();
                $urls_id = $UrlsModel->insert_urls($data);

                $data = [
                    'urls_id' =>  $urls_id,
                    'ust_date'    => date('Y-m-d')
                ];

                $UrlsStatisticsModel = new UrlsStatisticsModel();
                $UrlsStatisticsModel->insert_urlsStatistics($data);

                $qr_code = $this->generate_qrcode($urls_short);
                $result['url_full'] = $urls_full;
                $result['url_short'] =  base_url($urls_short);
                $result['url_qrcode'] =  base_url($qr_code['file']);
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        return $this->respond($result);
    }

    public function listDateClickByShortUrl(): Response
    {

        try {
            $day = 7;
            $path = $this->request->getPost('url');
            $UrlsStatisticsModel = new UrlsStatisticsModel();
            $qr = $UrlsStatisticsModel->listDateDayByShort($path, $day);
            $today = new DateTime();
            $begin = $today->sub(new DateInterval('P' . $day . 'D'));
            $end = new DateTime();
            $end = $end->modify('+1 day');
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);
            // var_dump($daterange);
            $result = array();
            foreach ($daterange as $date) {
                $key = array_search($date->format("Y-m-d"), array_column($qr, 'ust_date'));
                $result['listdate'][] = [
                    'date' => $date->format("d-m-Y"),
                    'click' => is_int($key) ? $qr[$key]['ust_click'] : "0"
                ];
            }

            usort($result['listdate'], function ($a, $b) {
                if (strtotime($a['date']) == strtotime($b['date'])) return 0;
                return strtotime($a['date']) < strtotime($b['date']) ? 1 : -1;
            });


            $UrlsModel = new UrlsModel();
            $data_urls = $UrlsModel->getShortUrls($path);

            $result['url_full'] = $data_urls['urls_full'];
            $result['url_create_at'] = date('d-m-Y H:i:s', strtotime($data_urls['urls_create_at']));
            $result['url_short'] =  base_url($data_urls['urls_short']);
            $result['url_click'] =  $data_urls['urls_click'];
            $result['url_qrcode'] =  base_url('qrcode/' . $data_urls['urls_short'] . '.png');
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        return $this->respondCreated($result);
    }

    public function listShortUrlTop5(): Response
    {
        try {
            $cid = $_COOKIE['auth_cookie_id'];
            $UrlsModel = new UrlsModel();
            $result = $UrlsModel->listShortUrlsTop5ByCID($cid);


            foreach ($result as $k => $v) {
                $result[$k]['urls_short_link'] = base_url($v['urls_short']);
                $result[$k]['urls_statistics_link'] = base_url('statistics/' . $v['urls_short']);
                $result[$k]['urls_qrcode'] = base_url('qrcode/' . $v['urls_short'] . '.png');
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        return $this->respond($result);
    }

    public function getShortUrl()
    {
        $path = $this->request->getPath();
        $UrlsModel = new UrlsModel();
        $result = $UrlsModel->getShortUrls($path);

        if ($result) {

            $urls_id = $result['urls_id'];

            $UrlsStatisticsModel = new UrlsStatisticsModel();
            if (!$UrlsStatisticsModel->getStatisticsByUrlsIDAndDate($urls_id, date('Y-m-d'))) {
                $data = [
                    'urls_id' =>  $urls_id,
                    'ust_date'    => date('Y-m-d')
                ];
                $UrlsStatisticsModel->insert_urlsStatistics($data);
            }

            $UrlsStatisticsModel->update_ClickUrlsStatistics($urls_id);


            $UrlsModel->update_ClickUrls($path);
            return redirect()->to($result['urls_full'], 301);
        } else {
            return redirect()->to(base_url());
        }
    }



    public function generaterandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function generate_qrcode($path)
    {

        $this->ciqrcode = new Ciqrcode();

        $save_name  = $path . '.png';

        $dir = 'qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '1024';
        $config['black']        = [255, 255, 255];
        $config['white']        = [255, 255, 255];
        $this->ciqrcode->initialize($config);

        /* QR Data  */
        $params['data']     = base_url($path);
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->ciqrcode->generate($params);

        /* Return Data */
        return [
            'content' => $path,
            'file'    => $dir . $save_name,
        ];
    }
}
