<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\CreateCategories;
use Log;

class ExcelController extends Controller
{
    function queue()
    {
      $this->dispatch(new CreateCategories());
      return response()->json(
        ['message' => "İşlem Kuyruğa Alındı"]
      );
    }
}
