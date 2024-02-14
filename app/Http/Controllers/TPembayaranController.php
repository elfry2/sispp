<?php

namespace App\Http\Controllers;

use App\Models\t_dtl_pembayaran;
use App\Models\t_pembayaran;
use App\Models\t_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                = $monthsSinceFirstPayment - $row->pembayaran->count();

            return $row;
        });

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
}
