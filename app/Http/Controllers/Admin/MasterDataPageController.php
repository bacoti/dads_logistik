<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataPageController extends Controller
{
    /**
     * Display the master data management page
     */
    public function index()
    {
        return view('admin.master-data');
    }
}
