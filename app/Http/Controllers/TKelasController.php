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
use App\Models\t_kelas;

class TKelasController extends Controller
{
    protected const resource = 't_kelas';

    protected const title = 'Kelas';

    protected const primaryKeyColumnName = 'kd_kls';

    protected const queryColumnNames = [
        'nm_kelas',
    ];

    protected const validationRules = [
        'nm_kelas' => 'required|string|max:255',
        'jumlah_siswa' => 'required|numeric|max:40',
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
        ];

        return view(self::resource . '.create', (array) $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(self::validationRules);

        $t_kelas = t_kelas::create($validated);

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

        $validated = $request->validate(self::validationRules);

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
            'order.column' => $validated->order_column,
            'order.direction' => $validated->order_direction,
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
            ['Laporan Data Kelas'],
            [],
            [],
        ];

        $currentRowIndex = count($rows);

        $columnTitles = [
            'Kode Kelas',
            'Nama Kelas',
            'Jumlah Siswa',
        ];

        $rows[$currentRowIndex++] = $columnTitles;

        foreach ($primary as $row) $rows[$currentRowIndex++] = [
            $row->kd_kls,
            $row->nm_kelas,
            $row->siswa->count() ?: '0',
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

        $directoryPath = 'app/public/reports/' . self::resource;

        $fileName = Str::uuid() . '.xlsx';

        $filePath = "${directoryPath}/${fileName}";

        Storage::disk('local')->put($filePath, '');

        (new Xlsx($spreadsheet))->save(storage_path($filePath));

        return redirect()->route(self::resource . '.showReportDownloadForm', [
            'fileName' => $fileName,
        ]);
    }

    public function showReportDownloadForm($fileName) {

        $data = (object) [
            'title' => 'Laporan berhasil dibuat',
            'resource' => self::resource,
        ];

        $filePath
            = asset('storage/reports/' . self::resource . '/' . $fileName);


        $data->url = $filePath;

        return view(self::resource . '.download-report', (array) $data);
    }
}

