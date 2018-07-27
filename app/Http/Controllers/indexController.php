<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Provider\zh_CN\DateTime;

class indexController extends Controller
{
    public function index() {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $daysOfMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $days = array();
        $week = 1;
        for($d = 1; $d <= $daysOfMonth; $d++){
            $date = $currentYear . '-' . $currentMonth . '-' . $d;
            $dayOfWeek = date('N', strtotime($date));
            $days['w' . $week][$dayOfWeek][$d] = $date;
            if($dayOfWeek % 7 === 0) {
                $week++;
            }
        }
        return view('welcome', ['days' => $days]);
    }

    public function showAgendaDates($date) {
        $this->apiQuery($date);
    }

    public function makeReservation(){
        $data = request()->all();
        $this->apiQuery($data, 'save');
    }

    private function apiQuery($data = '', $action="get"){
        $url = 'http://104.131.110.211:8000/api/appointments/';
        switch($action){
            case "get": 
            $url .= $data;
            $session = curl_init($url);
            break;
            case "save": 
            $session = curl_init($url);
            curl_setopt($session, CURLOPT_POST, true);
            curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($data));
            break;
            case "update":
            $session = curl_init($url);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        }
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($session);
        $code = curl_getinfo($session, CURLINFO_HTTP_CODE);

        curl_close($session);
        print_r($response);
    }
}