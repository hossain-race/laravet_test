<?php
/**
 * Created by PhpStorm.
 * User: octopus
 * Date: 4/11/17
 * Time: 2:22 PM
 */

function allowPermissions()
{
    if (auth()->user()->hasRole('super-admin'))
        return \Backpack\PermissionManager\app\Models\Permission::pluck('name')->toArray();

    return auth()->user()->permissions->pluck('name')->toArray();
}

function denyPermissions()
{
    $userPermissions = allowPermissions();
    $permissions = \Backpack\PermissionManager\app\Models\Permission::pluck('name')->toArray();

    return array_diff($permissions, $userPermissions);
}

function allowExport()
{
    if (in_array('export', allowPermissions()))
        return true;

    return false;
}

function getLastNDays($days, $format = 'd/m'){
    $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y));
    }
    return array_reverse($dateArray);
}

function getDayWiseArray($days, $datas){
    $dateWiseArray = array();
    foreach ($days as $day){
        if (array_key_exists($day, $datas))
            $dateWiseArray[] = $datas[$day];
        else
            $dateWiseArray[] = 0;

    }
    return $dateWiseArray;
}

function getChartData($numberOfDays)
{
    $arr = getLastNDays($numberOfDays, 'Y-m-d');
    $totalTicket =  \App\Models\Ticket::select(\Illuminate\Support\Facades\DB::raw('DATE(created_at) as Date'), \Illuminate\Support\Facades\DB::raw("count(id) as Total"))->where(\Illuminate\Support\Facades\DB::raw('DATE(created_at)'),'>=',$arr[0])->groupBy('Date')->pluck('Total','Date')->toArray();
    $activeTicket =  \App\Models\Ticket::select(\Illuminate\Support\Facades\DB::raw('DATE(created_at) as Date'), \Illuminate\Support\Facades\DB::raw("count(id) as Total"))->where('status',1)->where(\Illuminate\Support\Facades\DB::raw('DATE(created_at)'),'>=',$arr[0])->groupBy('Date')->pluck('Total','Date')->toArray();
    $resolvedTicket =  \App\Models\Ticket::select(\Illuminate\Support\Facades\DB::raw('DATE(updated_at) as Date'), \Illuminate\Support\Facades\DB::raw("count(id) as Total"))->where('status',3)->where(\Illuminate\Support\Facades\DB::raw('DATE(created_at)'),'>=',$arr[0])->groupBy('Date')->pluck('Total','Date')->toArray();
    $data['days'] = $arr;
    $data['total'] = getDayWiseArray($arr, $totalTicket);
    $data['active'] = getDayWiseArray($arr, $activeTicket);
    $data['resolved'] = getDayWiseArray($arr, $resolvedTicket);
//    $arr = getLastNDays(7, 'Y-m-d');
//    dd($data);
    return json_encode($data);
}


function amwsWithHijackData()
{
        try {
        $client = amwsAuthentication();
        if ($client->validateCredentials()) {
            $searchField = \App\Models\Product::select('asin')->pluck('asin')->toArray();; // Can be GCID, SellerSKU, UPC, EAN, ISBN, or JAN
            $mwesDatas = $client->GetLowestOfferListingsForASIN($searchField, 'new');
            $asinWiseArray = array();
            if (count($mwesDatas) > 0){
                foreach ($mwesDatas as $key => $value){
                    if (array_key_exists('Qualifiers', $value) ){
                        $asinWiseArray[$key] = 1;
                    }else
                        $asinWiseArray[$key] = count($value);
                }
            }
            return $asinWiseArray;
        } else {
            $parts['content'] = 'Invalid Authentication!!!';
            return new JsonResponse($parts, 200, []);
        }
    } catch (ClientException $exception) {
        $responseBody = $exception->getResponse()->getBody(true)->getContents();
        $response = json_decode($responseBody);
        return new JsonResponse((array)$response, 200, []);
    }
}

function amwsWithNameData($asin)
{
        try {
            $client = amwsAuthentication();
            if ($client->validateCredentials()) {
                $asinWithData = array();
                $result = $client->GetMatchingProductForId([$asin], 'ASIN');
                $mwesDatas = $client->GetLowestOfferListingsForASIN([$asin], 'new');
                if ($result['found'])
                    if (array_key_exists($asin, $result['found']) ){
                        $asinWithData['name'] = $result['found'][$asin]['Title'];
                        if (count($mwesDatas) > 0){
                            if (array_key_exists('Qualifiers', $mwesDatas[$asin]) ){
                                $asinWithData['selling_qty'] = 1;
                            }else
                                $asinWithData['selling_qty'] = count($mwesDatas[$asin]);
                        }else
                            $asinWithData['selling_qty'] = 1;
                        return $asinWithData;
                    }else
                        return false;
                else
                    return false;

            } else {
                return  false;
        }
    } catch (ClientException $exception) {
        $responseBody = $exception->getResponse()->getBody(true)->getContents();
        $response = json_decode($responseBody);
        return new JsonResponse((array)$response, 200, []);
    }
}

function amwsGetReportId()
{
        try {
            $client = amwsAuthentication();
            if ($client->validateCredentials()) {
                $reportId = $client->RequestReport('_GET_FLAT_FILE_OPEN_LISTINGS_DATA_');
                return $reportId;
            } else {
                return  false;
        }
    } catch (ClientException $exception) {
        $responseBody = $exception->getResponse()->getBody(true)->getContents();
        $response = json_decode($responseBody);
        return new JsonResponse((array)$response, 200, []);
    }
}

function amwsGetReportContent($reportId)
{
        try {
            $client = amwsAuthentication();
            if ($client->validateCredentials()) {
                $report_content = $client->GetReport($reportId);
                return $report_content;
            } else {
                return  false;
        }
    } catch (ClientException $exception) {
        $responseBody = $exception->getResponse()->getBody(true)->getContents();
        $response = json_decode($responseBody);
        return new JsonResponse((array)$response, 200, []);
    }
}

function amwsAuthentication()
{
    $client = new \MCS\MWSClient([
        'Marketplace_Id' => 'ATVPDKIKX0DER',
        'Seller_Id' => 'A391W15J2L70QL',
        'Access_Key_ID' => 'AKIAJTXVJWPD35G3U33Q',
        'Secret_Access_Key' => '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',
        'MWSAuthToken' => 'amzn.mws.86b09314-5f54-cf44-5eba-ee8a74f8f8b3'
    ]);


    return $client;
}

