<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function getRandomUserFromSheet(Request $request){

        $temp_csv_data = Storage::disk('local')->get('data.csv');
        $temp_csv_data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $temp_csv_data));
        $formatted_values = [];
        if (!empty($temp_csv_data)) {
            $temp_headers = array_values($temp_csv_data[0]);
            $headers = [];

            foreach ($temp_headers as $header) {
                $headers[] = str($header)->trim()->lower()->slug('_')->value();
            }

            foreach ($temp_csv_data as $key => $item) {
                if ($key === 0) continue;
                if (count($item) == count($headers)) {
                    $row = [];
                    foreach ($item as $column_number => $column_value) {
                        $row[$headers[$column_number]] = $column_value;
                    }
                    $formatted_values[] = $row;
                }
            }
        }
        dd($formatted_values);
    }
}
