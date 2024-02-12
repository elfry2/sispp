<?php

namespace App\Http\Controllers;

use App\Models\t_pembayaran;
use Illuminate\Http\Request;

class TPembayaranController extends Controller
{
    protected const resource = 't_pembayaran';

    protected const title = 'Pembayaran';

    protected const primaryKeyColumnName = 'id_pembayaran';

    protected const queryColumnNames = [
        'id_pembayaran',
        'nis',
        'nama_siswa',
    ];

    protected const validationRules = [
        't_pembayaran' => [
            'id_pembayaran' => 'required||integer|min:0|unique:t_pembayaran,id_pembayaran',
            'tgl' => 'required|date',
            'nis' => 'required|numeric|digits_between:0,20',
            'total' => 'required|decimal:0,2|min:0',
        ],
        't_dtl_pembayaran' => [
            'id_pembayaran' => 'required|integer|exists:t_pembayaran,id_pembayaran',
            'tahun_pembayaran' => 'required|integer|min:0',
            'bulan' => 'required|integer|min:1|max:12',
            'jumlah' => 'required|decimal:0,2|min:0'
        ],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primary = '\App\Models\\' . self::resource;

        $data = (object) [
            'resource' => self::resource,
            'title' => self::title,
            'primary'
            => (new $primary)->orderBy(
                preference(self::resource . '.order.column', self::primaryKeyColumnName),
                preference(self::resource . '.order.direction', 'ASC')
            ),
        ];

        $classFilterPreference = preference(self::resource . '.filters.classId');

        if($classFilterPreference)
            $data->primary->where('kd_kls', $classFilterPreference);

        if (!empty(request('q'))) {

            foreach (self::queryColumnNames as $index => $columnName) {

                $method = 'where';

                if($index > 0) $method = 'orWhere';

                $data->primary = $data->primary
                                      ->$method($columnName, 'like', '%' . request('q') . '%');
            }
        }

        $data->primary = $data->primary
                              ->paginate(config('app.rowsPerPage'))->withQueryString();

        return view(self::resource . '.index', (array) $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => 'Tambah ' . str(self::title)->lower(),
        ];

        return view(self::resource . '.create', (array) $data);
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
    public function show(t_pembayaran $t_pembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(t_pembayaran $t_pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, t_pembayaran $t_pembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(t_pembayaran $t_pembayaran)
    {
        //
    }
}
