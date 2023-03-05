<?php
namespace App\Http\Controllers;

use App\Models\Quehaceres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuehaceresController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return DB::select('SELECT * from quehaceres');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Quehaceres $quehaceres) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quehaceres $quehaceres) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quehaceres $quehaceres) {
        //
    }
}
