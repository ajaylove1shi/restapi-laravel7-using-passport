<?php
use Illuminate\Support\Facades\DB;

/**
|-----------------------------------------------
| Api Response Helper Functions.......
|-----------------------------------------------
 */
if (!function_exists('indexApiResponse')) {
    function indexApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => 'All ' . ucfirst($text) . ' has been fetched successfully.', 'results' => $results], $code);
    }
}
if (!function_exists('showApiResponse')) {

    function showApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been fetched successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('storeApiResponse')) {
    function storeApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been added successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('updateApiResponse')) {
    function updateApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been updated successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('destroyApiResponse')) {
    function destroyApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been trashed successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('validatorApiResponse')) {
    function validatorApiResponse($errors = [])
    {
        return response()->json(['status' => false, 'message' => 'Please fill all required fields.', 'errors' => $errors]);
    }
}

if (!function_exists('failedApiResponse')) {
    function failedApiResponse($message = '', $results = [], $code = '201')
    {
        return response()->json(['status' => false, 'message' => $message, 'results' => $results], $code);
    }
}
if (!function_exists('successApiResponse')) {

    function successApiResponse($message = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => $message, 'results' => $results], $code);
    }
}

if (!function_exists('errorApiResponse')) {

    function errorApiResponse()
    {
        return response()->json(['status' => 'error', 'title' => 'Error!', 'text' => 'Something is wrong, please try again...']);
    }
}

if (!function_exists('statusApiResponse')) {
    function statusApiResponse($message = '')
    {
        return response()->json(['status' => 'success', 'title' => 'Changed!', 'text' => $message]);
    }
}

if (!function_exists('date_time')) {
    function date_time($attributes)
    {
        return date(config()->get('constants.date_time'), strtotime($attributes));
    }
}

if (!function_exists('base_url')) {
    function base_url($append_url)
    {
        return 'http://tradzhub.in/' . $append_url;
    }
}

if (!function_exists('per_page_record')) {
    function per_page_record()
    {
        return 20;
    }
}

if (!function_exists('get_country')) {
    function get_country($id)
    {
        $result = DB::table('countries')->where('id', $id)->first();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }
}

if (!function_exists('get_state')) {
    function get_state($id)
    {
        $result = DB::table('states')->where('id', $id)->first();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }
}

if (!function_exists('get_city')) {
    function get_city($id)
    {
        $result = DB::table('cities')->where('id', $id)->first();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }
}

if (!function_exists('user_total')) {
    function user_total($last_days = 0)
    {
        if ($last_days == 0) {
            $time = 0;
        } else {
            $time = time() - (24 * 60 * 60 * $last_days);
        }
        $sales  = DB::table('sale')->where('buyer', Auth::user()->user_id)->get();
        $return = 0;
        foreach ($sales as $row){
            if ($row->sale_datetime >= $time) {
                $payment_status = json_decode($row->payment_status, true);
                foreach ($payment_status as $payment) {
                    // if ($payment['status'] == 'paid') {
                    $return += $row->grand_total;
                    // }
                }
            }

        }
        return number_format((float) $return, 2, '.', '');
    }
}
