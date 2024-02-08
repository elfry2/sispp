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

class TSiswaController extends Controller
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

        $data = (object) [
            'resource' => self::resource,
            'title' => self::title,
            'primary'
            => (new $primary)->orderBy(
                preference(self::resource . '.order.column', self::primaryKeyColumnName),
                preference(self::resource . '.order.direction', 'ASC')
            ),
        ];


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
            'primary' => t_kelas::all(),
        ];

        return view(self::resource . '.create', (array) $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $table->string('nis')->unique();
        // $table->primary('nis');
        // $table->string('nama_siswa', 30);
        // $table->string('alamat', 40);
        // $table->date('tgl_lahir');
        // $table->string('tempat_lahir', 30);
        // $table->string('jk', 15);
        // $table->string('nama_orang_tua', 15);
        // $table->string('no_hp');
        // $table->unsignedBigInteger('kd_kls');
        // $table->foreign('kd_kls')->references('kd_kls')->on('t_kelas');
        // $table->decimal('spp_perbulan');

        $validated = $request->validate([
            'nis' => 'required|numeric|digits_between:0,20',
            'nama_siswa' => 'required|string|max:30',
            'alamat' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:30',
            'jk' => 'required|boolean',
            'nama_orang_tua' => 'required|string|max:30',
            'no_hp' => 'required|numeric|digits_between:0,15',
            'kd_kls' => 'required|numeric|exists:t_kelas,kd_kls',
            'spp_perbulan' => 'required|integer|min:0',
        ]);

        $t_siswa = t_siswa::create($validated);

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => self::title . ' ditambahkan.'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect(route(self::resource . '.edit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $primary = '\App\Models\\' . self::resource;

        $primary = (new $primary)
            ->where(self::primaryKeyColumnName, $id)
            ->first();

        $data = (object) [
            'resource' => self::resource,
            'title' => 'Sunting ' . str(self::title)->lower(),
            'primary' => $primary,
            'secondary' => t_kelas::all(),
        ];

        return view(self::resource . '.edit', (array) $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $primary = '\App\Models\\' . self::resource;

        $primary = (new $primary)
            ->where(self::primaryKeyColumnName, $id);

        $validated = $request->validate([
            'nis' => 'required|numeric|digits_between:0,20',
            'nama_siswa' => 'required|string|max:30',
            'alamat' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:30',
            'jk' => 'required|boolean',
            'nama_orang_tua' => 'required|string|max:30',
            'no_hp' => 'required|numeric|digits_between:0,15',
            'kd_kls' => 'required|numeric|exists:t_kelas,kd_kls',
            'spp_perbulan' => 'required|integer|min:0',
        ]);

        $primary = $primary->update($validated);

        return redirect()->back()->with('message', (object) [
            'type' => 'success',
            'content' => self::title . ' disunting.'
        ]);
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete($id)
    {
        $primary = '\App\Models\\' . self::resource;

        $primary = (new $primary)
            ->where(self::primaryKeyColumnName, $id)
            ->first();

        $data = (object) [
            'resource' => self::resource,
            'title' => 'Hapus ' . self::title,
            'primary' => $primary
        ];

        return view(self::resource . '.delete', (array) $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $primary = '\App\Models\\' . self::resource;

        $primary = (new $primary)
            ->where(self::primaryKeyColumnName, $id);

        $primary->delete();

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => self::title . ' dihapus.'
            ]);
    }

    /**
     * Show the form for editing the preferences for the listing of the resource.
     */
    public function preferences()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => 'Preferensi ' . str(self::title)->lower(),
            'primary' => Schema::getColumnListing(self::resource),
        ];

        $data->primary = collect($data->primary)->map(function ($element) {
            return (object) [
                'value' => $element,
                'label' => str($element)->headline(),
            ];
        });

        return view(self::resource . '.preferences', (array) $data);
    }

    /**
     * Update the preferences for the listing of the resource in storage.
     */
    public function applyPreferences(Request $request)
    {
        $validated = (object) $request->validate([
            'order_column' => 'required|max:255',
            'order_direction' => 'required|max:255',
        ]);

        foreach ([
            [self::resource . '.order.column' => $validated->order_column],
            [self::resource . '.order.direction' => $validated->order_direction],
        ] as $preference) {
        preference($preference);
        }

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => 'Preferensi disunting.'
            ]);
    }

    /**
     * Show the form for searching the resource.
     */
    public function search()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => 'Cari ' . str(self::title),
        ];

        return view(self::resource . '.search', (array) $data);
    }
}

