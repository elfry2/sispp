<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\t_dtl_pembayaran;
use App\Models\t_pembayaran;
use App\Models\t_kelas;
use App\Models\t_siswa;

class TPembayaranController extends Controller
{
    protected const resource = 't_pembayaran';

    protected const title = 'Pembayaran';

    protected const primaryKeyColumnName = 'id_pembayaran';

    protected const queryColumnNames = [
        'nis',
        'nama_siswa',
    ];

    protected const validationRules = [
        't_pembayaran' => [
            // 'id_pembayaran' => 'required||integer|min:0|unique:t_pembayaran,id_pembayaran',
            'tgl' => 'required|date',
            'nis' => 'required|numeric|digits_between:0,20',
            'total' => 'required|decimal:0,2|min:0',
        ],
        't_dtl_pembayaran' => [
            'id_pembayaran' => 'required|integer|exists:t_pembayaran,id_pembayaran',
            'tahun_pembayaran' => 'required|integer|min:0',
            'bulan' => 'required|integer|min:0|max:11',
            'jumlah' => 'required|decimal:0,2|min:0'
        ],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primary = '\App\Models\t_siswa';

        $data = (object) [
            'resource' => self::resource,
            'title' => self::title,
            'primary'
            => (new $primary)->orderBy(
                preference(self::resource . '.order.column', 'nis'),
                preference(self::resource . '.order.direction', 'ASC')
            ),
        ];

        if(preference(self::resource . '.filters.hasDebt') == 1)
            $data->title .= ' jatuh tempo';

        $classFilterPreference = preference(self::resource . '.filters.classId');

        if($classFilterPreference)
            $data->primary->where('kd_kls', $classFilterPreference);

        $yearFilterPreference = preference(self::resource . '.filters.payments.year');

        if($yearFilterPreference) {

            $data->primary->whereHas(
                'pembayaran',
                function($query) use ($yearFilterPreference) {

                    return $query->whereHas(
                        'detail',
                        function($query) use ($yearFilterPreference) {

                            return $query->where(
                                'tahun_pembayaran',
                                $yearFilterPreference
                            );
                    });
            });
        }

        $monthFilterPreference = preference(self::resource . '.filters.payments.month');

        if($monthFilterPreference) {

            $data->primary->whereHas(
                'pembayaran',
                function($query) use ($monthFilterPreference) {

                    return $query->whereHas(
                        'detail',
                        function($query) use ($monthFilterPreference) {

                            return $query->where(
                                'bulan',
                                $monthFilterPreference
                            );
                    });
            });
        }

        if (!empty(request('q'))) {

            foreach (self::queryColumnNames as $index => $columnName) {

                $method = 'where';

                if($index > 0) $method = 'orWhere';

                $data->primary = $data->primary
                                      ->$method($columnName, 'like', '%' . request('q') . '%');
            }
        }

        $data->primary = $data->primary->get();

        $data->primary->map(function($row) {

            $payments = $row->pembayaran;

            $paymentDetails = $payments->map(function ($payment) {
                return $payment->detail;
            });

            $firstPaymentDetail = $paymentDetails->sortBy([
                ['tahun_pembayaran', 'asc'],
                ['bulan', 'asc']
            ])->first();

            if(is_null($firstPaymentDetail)) return $row;

            $current = (object) [
                'year' => date('Y'),
                'month' => date('m'),
            ];

            $monthsSinceFirstPayment
                = ($current->year - $firstPaymentDetail->tahun_pembayaran) * 12;

            $monthsSinceFirstPayment -= $firstPaymentDetail->bulan;

            $monthsSinceFirstPayment += ($current->month - 1);

            $row->tunggakan
                = $monthsSinceFirstPayment - $row->pembayaran()->count();

            return $row;
        });

        $hasDebtFilter = preference(self::resource . '.filters.hasDebt');

        if($hasDebtFilter) {

            switch($hasDebtFilter) {

            case '1':
                $data->primary = $data->primary->filter(function ($row) {
                    return $row->tunggakan > 0;
                });
                break;

            case '2':
                $data->primary = $data->primary->filter(function ($row) {
                    return $row->tunggakan === 0;
                });
                break;

            default:
                break;
            }
        }

        // $data->primary = $data->primary
        //                       ->paginate(config('app.rowsPerPage'))->withQueryString();

        $data->primary = new LengthAwarePaginator(
            $data->primary->slice(request('page'), config('app.rowsPerPage')),
            $data->primary->count(),
            config('app.rowsPerPage')
        );

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
            'primary' => t_siswa::all(),
        ];

        if(request('nis') && request('tahun_pembayaran')) {

            $validated = (object) request()->validate([
                'nis' => 'required|numeric|digits_between:0,20',
                'tahun_pembayaran' => 'required|integer',
            ]);

            $data->secondary = new ('App\Models\\' . self::resource);

            $data->secondary
                = $data->secondary->where('nis', $validated->nis);

            $data->secondary
                = $data->secondary
                       ->whereHas('detail', function($query) use ($validated) {

                           return $query->where(
                               'tahun_pembayaran',
                               $validated->tahun_pembayaran
                           );
                       });

            $data->secondary = $data->secondary->get();

            $data->secondary = $data->secondary->map(function($row) {
                return $row->detail->bulan;
            });
        }


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
    public function update(Request $request, $id)
    {
        $validationRules = (object) self::validationRules;

        $validated = (object) $request->validate([
            'bulan' => 'nullable|array',
        ]);

        $bulan = collect($request->bulan ?: []);

        $request->merge([
            'nis' => $id,
            'tgl' => date('Y-m-d H:i:s'),
            'total' => 0,
        ]);

        for($i = 0; $i < 12; $i++) {

            if($bulan->search($i) === false) {

                $validated = (object) Validator::make($request->all(), [
                    'nis' => 'required|numeric|digits_between:0,20',
                    'tahun_pembayaran' => 'required|integer|min:0',
                ])->validate();

                $payments = t_pembayaran::where('nis', $validated->nis)
                    ->whereHas('detail', function($query) use ($validated) {

                        return $query->where(
                            'tahun_pembayaran',
                            $validated->tahun_pembayaran
                        );
                    })->whereHas('detail', function($query) use ($i) {

                        return $query->where('bulan', $i);
                    })->get();

                foreach ($payments as $payment) {

                    $payment->detail()->delete();

                    $payment->delete();
                }

                continue;
            }

            $validated = $request->validate($validationRules->t_pembayaran);

            $oldPaymentExists = t_pembayaran::where('nis', $request->nis)
                ->whereHas('detail', function($query) use ($request, $i) {

                    return $query
                        ->where('tahun_pembayaran', $request->tahun_pembayaran)
                        ->where('bulan', $i);
                })->first() != false;

            if($oldPaymentExists) continue;

            $payment = t_pembayaran::create($validated);

            $entry = [
                'id_pembayaran' => $payment->id_pembayaran,
                'tahun_pembayaran' => $request->tahun_pembayaran,
                'bulan' => $i,
                'jumlah' => $payment->siswa->spp_perbulan,
            ];

            $validated = Validator::make(
                $entry,
                $validationRules->t_dtl_pembayaran
            )->validate();

            t_dtl_pembayaran::create($validated);
        }

        return redirect()->back()->with([
            'message' => (object) [
                'type' => 'success',
                'content' => self::title . ' disunting.'
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(t_pembayaran $t_pembayaran)
    {
        //
    }

    public function generateReport(Request $request) {

        if($request->isMethod('get')) {

            $data = [
                'title' => 'Buat laporan ' . str(self::title)->lower(),
                'resource' => self::resource,
                'primary' => t_kelas::all(),
                'secondary'
                => t_dtl_pembayaran::distinct()->pluck('tahun_pembayaran'),
            ];

            return view(self::resource . '.generate-report', $data);
        }

        $validated = (object) $request->validate([
            'tahun_pembayaran' => 'nullable|integer|min:0',
            'bulan' => 'nullable|integer|min:0|max:11',
            'kelas' => 'nullable|integer|exists:t_kelas,kd_kls',
            'punya_tunggakan' => 'nullable|integer|min:0|max:2',
        ]);

        $students = new t_siswa;

        if(!is_null($validated->kelas)) {

            $students = $students->where('kd_kls', $validated->kelas);
        }

        if(!is_null($validated->tahun_pembayaran)) {

            $students = $students
                ->whereHas('pembayaran', function($query) use ($validated) {

                    return $query
                        ->whereHas('detail', function($query) use ($validated) {

                            return $query->where(
                                'tahun_pembayaran',
                                $validated->tahun_pembayaran
                            );
                        });
                });
        }

        if(!is_null($validated->bulan)) {

            $students = $students
                ->whereHas('pembayaran', function($query) use ($validated) {

                    return $query
                        ->whereHas('detail', function($query) use ($validated) {

                            return $query->where(
                                'bulan',
                                $validated->bulan
                            );
                        });
                });
        }

        $students = $students->get();

        $students->map(function($student) {

            $payments = $student->pembayaran;

            $paymentDetails = $payments->map(function ($payment) {
                return $payment->detail;
            });

            $firstPaymentDetail = $paymentDetails->sortBy([
                ['tahun_pembayaran', 'asc'],
                ['bulan', 'asc']
            ])->first();

            if(is_null($firstPaymentDetail)) return $student;

            $current = (object) [
                'year' => date('Y'),
                'month' => date('m'),
            ];

            $monthsSinceFirstPayment
                = ($current->year - $firstPaymentDetail->tahun_pembayaran) * 12;

            $monthsSinceFirstPayment -= $firstPaymentDetail->bulan;

            $monthsSinceFirstPayment += ($current->month - 1);

            $student->tunggakan
                = $monthsSinceFirstPayment - $student->pembayaran()->count();

            return $student;
        });

        if(!is_null($validated->punya_tunggakan)) {

            switch($validated->punya_tunggakan) {

            case '1':
                $students = $students->filter(function ($student) {
                    return $student->tunggakan > 0;
                });
                break;

            case '2':
                $students = $students->filter(function ($student) {
                    return $student->tunggakan === 0;
                });
                break;

            default:
                break;
            }
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $currentRow = 1;

        $sheetTitle = 'Laporan Pembayaran SPP';

        if(
            !is_null($validated->punya_tunggakan)
            && $validated->punya_tunggakan == 1
        ) $sheetTitle .= ' Jatuh Tempo';

        $sheet->setCellValue('A' . $currentRow, $sheetTitle);

        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $currentRow++;

        if(!is_null($validated->kelas)) {

            $class = t_kelas::find($validated->kelas);

            $cell = (object) [
                'coordinate' => 'A' . $currentRow,
                'value' => 'Kelas ' . $class->nm_kelas,
            ];

            $sheet->setCellValue(
                $cell->coordinate,
                $sheet->getCell($cell->coordinate)->getValue() . $cell->value
            );
        }

        if(!is_null($validated->bulan)) {

            $months = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            $cell = (object) [
                'coordinate' =>  'A' . $currentRow,
                'value' => $months[$validated->bulan],
            ];

            if(!is_null($validated->kelas)) $cell->value = ' ' . $cell->value;

            $sheet->setCellValue(
                $cell->coordinate,
                $sheet->getCell($cell->coordinate)->getValue() . $cell->value
            );
        }

        if(!is_null($validated->tahun_pembayaran)) {

            $cell = (object) [
                'coordinate' => 'A' . $currentRow,
                'value' => $validated->tahun_pembayaran,
            ];

            if(!is_null($validated->kelas) || !is_null($validated->bulan))
                $cell->value = ' ' . $cell->value;

            $sheet->setCellValue(
                $cell->coordinate,
                $sheet->getCell($cell->coordinate)->getValue() . $cell->value
            );
        }

        $currentRow += 2;

        $columns = [
            'No',
            'NIS',
            'Nama siswa',
            'Jenis kelamin',
            'Kelas',
            'Nama orang tua',
            'Biaya SPP',
            'Tunggakan',
        ];

        foreach ($columns as $index => $column)
            $sheet->setCellValue(range('a', 'z')[$index] . $currentRow, $column);

        foreach($students as $index => $student) {

            $currentRow++;

            $row = [
                $index + 1,
                $student->nis,
                $student->nama_siswa,
                $student->jk ? 'Laki-laki' : 'Perempuan',
                $student->kelas->nm_kelas,
                $student->nama_orang_tua,
                $student->spp_perbulan,
                $student->tunggakan ? $student->tunggakan . ' bulan' : 'Tidak ada',
            ];

            foreach($row as $index => $cell) $sheet->setCellValue(
                range('a', 'z')[$index] . $currentRow,
                $cell
            );
        }

        foreach([1, 2] as $row) $sheet->mergeCells("A${row}:H{$row}");

        $sheet->getStyle("A1:H4")->getFont()->setBold(true);

        $sheet->getStyle("A1:H4")->getAlignment()->setHorizontal('center');

        for ($i=4; $i <= $currentRow; $i++) {

            $row = $i;

            foreach(range('A', 'H') as $column) {

                $sheet->getStyle($column . $row)
                  ->getBorders()
                  ->getOutline()
                  ->setBorderStyle(Border::BORDER_THIN)
                  ->setColor(new Color('#000'));
            }
        }

        foreach ($sheet->getColumnIterator() as $column) {
           $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
        //
        // $writer = new Xlsx($spreadsheet);
        //
        // $fileName = Str::uuid() . '.xlsx';
        //
        // $directoryPath = "app/public/reports/payments/";
        //
        // Storage::put($directoryPath . $fileName, 'aaa');
        //
        // $writer->save(storage_path($directoryPath . $fileName));
        //
        // return redirect(route(self::resource . '.showReport', [
        //     'fileName' => $fileName
        // ]));

        $directoryPath = 'reports/' . self::resource;

        $fileName = Str::uuid() . '.xlsx';

        $filePath = "{$directoryPath}/{$fileName}";

        Storage::disk('public')->put($filePath, '');

        (new Xlsx($spreadsheet))
            ->save(config('filesystems.disks.public.root') . '/' . $filePath);

        return redirect(route(self::resource . '.showReport', [
            'fileName' => $fileName
        ]));

    }

    public function showReport($fileName) {

        $data = (object) [
            'title' => 'Buat laporan ' . str(self::title)->lower(),
            'resource' => self::resource,
        ];

        $directoryPath = 'reports/' . self::resource;

        $data->url = asset('storage/' . $directoryPath . '/' . $fileName);

        return view(self::resource . '.show-report', (array) $data);
    }

    /**
     * Show the form for editing the preferences for the listing of the resource.
     */
    public function preferences()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => 'Preferensi ' . str(self::title)->lower(),
            'primary' => Schema::getColumnListing('t_siswa'),
            'secondary' => t_kelas::all(),
            'tertiary'
                => t_dtl_pembayaran::distinct()->pluck('tahun_pembayaran'),
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
            'tahun_pembayaran' => 'nullable|integer|min:0',
            'bulan' => 'nullable|integer|min:0|max:11',
            'kd_kls' => 'nullable|integer|exists:t_kelas,kd_kls',
            'punya_tunggakan' => 'nullable|integer|min:0|max:2',
        ]);

        foreach ([
            'order.column' => $validated->order_column,
            'order.direction' => $validated->order_direction,
            'filters.classId' => $validated->kd_kls,
            'filters.payments.year' => $validated->tahun_pembayaran,
            'filters.payments.month' => $validated->bulan,
            'filters.hasDebt' => $validated->punya_tunggakan,
        ] as $key => $value) preference([self::resource . '.' . $key => $value]);

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => 'Preferensi disunting.'
            ]);
    }

}
