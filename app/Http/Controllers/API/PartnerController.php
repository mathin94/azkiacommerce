<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PartnerCollectionService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $service = new PartnerCollectionService();

        return response()->json($service->getCollections());
    }
}
