<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Folder;
use App\Models\t_kelas;

class TKelasController extends Controller
{
    protected const resource = 't_kelas';
    protected const title = 'Kelas';
    protected const primaryKeyColumnName = 'kd_kls';
    protected const queryColumnName = 'nm_kelas';

    /**
     * Display a listing of the resource.
     */
    public function index(t_kelas $t_kelas = null)
    {
        $primary = '\App\Models\\' . self::resource;

        $data = (object) [
            'resource' => self::resource,
            'title' => self::title,
            'primary'
            => (new $primary)->orderBy(
                preference(self::resource . '.order.column', 'kd_kls'),
                preference(self::resource . '.order.direction', 'ASC')
            ),
        ];


        if (!empty(request('q'))) {
            $data->primary
                = $data->primary->where(self::queryColumnName, 'like', '%' . request('q') . '%');
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
        $validated = (object) $request->validate([
            'nm_kelas' => 'required|string|max:255',
            'jumlah_siswa' => 'required|numeric|max:40',
        ]);

        $t_kelas = t_kelas::create([
            'nm_kelas' => $validated->nm_kelas,
            'jumlah_siswa' => $validated->jumlah_siswa,
        ]);

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

        $validated = (object) $request->validate([
            'nm_kelas' => 'required|string|max:255',
            'jumlah_siswa' => 'required|numeric|max:40',
        ]);

        $primary = $primary->update([
            'nm_kelas' => $validated->nm_kelas,
            'jumlah_siswa' => $validated->jumlah_siswa,
        ]);

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
