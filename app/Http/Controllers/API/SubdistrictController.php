<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Backoffice\Subdistrict;
use Illuminate\Http\Request;

class SubdistrictController extends Controller
{
    public function index()
    {
        $searchTerm = request()->query('search');

        $subdistricts = Subdistrict::limit(10);

        if ($searchTerm) {
            $subdistricts = $subdistricts->where('name', 'like', '%' . $searchTerm . '%');
        }

        return response()->json($subdistricts->get());
    }
}
