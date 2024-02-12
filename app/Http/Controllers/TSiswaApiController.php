<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Folder;
use App\Models\t_siswa;
use App\Models\t_kelas;

class TSiswaApiController extends Controller
{
    protected const resource = 't_siswa';

    protected const title = 'Siswa';

    protected const primaryKeyColumnName = 'nis';

    protected const queryColumnNames = [
        'nis',
        'nama_siswa',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primary = '\App\Models\\' . self::resource;

        $primary = new $primary;

        if (!empty(request('q'))) {

            foreach (self::queryColumnNames as $index => $columnName) {

                $method = 'where';

                if($index > 0) $method = 'orWhere';

                $data->primary = $data->primary
                    ->$method($columnName, 'like', '%' . request('q') . '%');
            }
        }

        $primary = $primary->get();

        return response()->json($primary);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       //
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete($id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       //
    }
}

