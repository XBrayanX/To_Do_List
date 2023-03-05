<?php
namespace App\Http\Controllers;

use App\Http\Middleware\Validate_Quehaceres;
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
    public function show(Request $request) {
        //Validaciones
        $this->validate_id($request);

        return DB::select('SELECT * from quehaceres where id = ? limit 1', [$request->id]);
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

    private function validate_id(Request $request){
        $request->validate([
            'id' => 'required|numeric'
        ]);
    }
}
