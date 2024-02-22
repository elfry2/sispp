<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    protected const validationRules = [
        'nis' => 'required|numeric|digits_between:0,20',
        'nama_siswa' => 'required|string|max:30',
        'alamat' => 'required|string|max:255',
        'tgl_lahir' => 'required|date',
        'tempat_lahir' => 'required|string|max:30',
        'jk' => 'required|boolean',
        'nama_orang_tua' => 'required|string|max:30',
        'no_hp' => 'required|numeric|digits_between:0,15',
        'kd_kls' => 'required|numeric|exists:t_kelas,kd_kls',
        'spp_perbulan' => 'required|decimal:0,2|min:0',
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

        $validated = $request->validate(self::validationRules);

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

        $validated = $request->validate(self::validationRules);

        $primary->update($validated);

        $validated = (object) $validated;

        return redirect()->route(self::resource . '.edit', [
            'id' => $validated->nis,
        ])->with('message', (object) [
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
            'title' => 'Hapus ' . str(self::title)->lower(),
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
            'secondary' => t_kelas::all(),
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
            'kd_kls' => 'nullable|numeric|digits_between:0,3',
        ]);

        foreach ([
            'order.column' => $validated->order_column,
            'order.direction' => $validated->order_direction,
            'filters.classId' => $validated->kd_kls,
        ] as $key => $value) preference([self::resource . '.' . $key => $value]);

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

    public function showReportGenerationForm() {

        $data = (object) [
            'title' => 'Buat laporan ' . str(self::title)->lower(),
        ];

        return view(self::resource . '.generate-report', (array) $data);
    }

    public function generateReport(Request $request) {

        // $validated = (object) $request->validate([
        //     //
        // ]);

        $primary = new ('App\Models\\' . self::resource);

        // if(!is_null($validated->property))
        //     $primary->where('column', 'value');

        $primary = $primary->get();

        $rows = [
            ['Laporan Data ' . self::title],
            [],
            [],
        ];

        $currentRowIndex = count($rows);

        $columnTitles = [
            'No.',
            'NIS',
            'Nama Siswa',
            'Alamat',
            'Tgl Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Kelas',
        ];

        $rows[$currentRowIndex++] = $columnTitles;

        foreach ($primary as $index => $row) $rows[$currentRowIndex++] = [
            $index+1,
            $row->nis,
            $row->nama_siswa,
            $row->alamat,
            $row->tgl_lahir,
            $row->tempat_lahir,
            $row->jk ? 'Laki-laki' : 'Perempuan',
            $row->kelas->nm_kelas,
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($rows, null);

        $sheet->mergeCells('A1:' . range('a', 'z')[count($columnTitles)-1] . '1');

        $sheet->getStyle('A1:' . range('a', 'z')[count($columnTitles)-1] . '4')
              ->getFont()
              ->setBold(true);

        $sheet->getStyle('A1:' . range('a', 'z')[count($columnTitles)-1] . '4')
              ->getAlignment()
              ->setHorizontal('center');

        foreach ($sheet->getColumnIterator() as $column) {

            $sheet->getColumnDimension($column->getColumnIndex())
                  ->setAutoSize(true);
        }

        for ($i=4; $i <= $currentRowIndex; $i++) {

            $row = $i;

            foreach(range('A', range('A', 'Z')[count($columnTitles)-1]) as $column) {

                $sheet->getStyle($column . $row)
                  ->getBorders()
                  ->getOutline()
                  ->setBorderStyle(Border::BORDER_THIN)
                  ->setColor(new Color('#000'));
            }
        }

        $directoryPath = 'reports/' . self::resource;

        $fileName = Str::uuid() . '.xlsx';

        $filePath = "{$directoryPath}/{$fileName}";

        Storage::disk('public')->put($filePath, '');

        (new Xlsx($spreadsheet))
            ->save(config('filesystems.disks.public.root') . '/' . $filePath);

        return redirect()->route(self::resource . '.showReportDownloadForm', [
            'fileName' => $fileName,
        ]);
    }

    public function showReportDownloadForm($fileName) {

        $data = (object) [
            'title' => 'Buat laporan ' . str(self::title)->lower(),
            'resource' => self::resource,
        ];

        $filePath
            = asset('storage/reports/' . self::resource . '/' . $fileName);


        $data->url = $filePath;

        return view(self::resource . '.download-report', (array) $data);
    }

}

