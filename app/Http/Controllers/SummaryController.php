<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\t_kelas;
use App\Models\t_siswa;

class SummaryController extends Controller
{
    public function index() {

        $data = (object) [
            'title' => 'Ikhtisar',
            'resource' => 'summary',
            'primary' => collect([
                (object) [
                    'title' => 'Kelas',
                    'content' => t_kelas::count(),
                ],
                (object) [
                    'title' => 'Siswa',
                    'content' => t_siswa::count(),
                ],
            ]),
        ];

        $studentsWithDebt = [
            'title' => 'Jatuh tempo',
            'content' => t_siswa::all()->filter(function($student) {

                $payments = $student->pembayaran;

                if($payments->count() == 0) return false;

                $paymentDetails = $payments->map(function($payment) {

                    return $payment->detail;
                });

                $firstPaymentDetail = $paymentDetails->sortBy([
                    ['tahun_pembayaran', 'asc'],
                    ['bulan', 'asc'],
                ])->first();

                $monthsSinceFirstPayment
                    = date('Y') - $firstPaymentDetail->tahun_pembayaran
                    - $firstPaymentDetail->bulan
                    + date('m') - 1;

                return $monthsSinceFirstPayment - $paymentDetails->count();
            })->count()
        ];

        $data->primary->push((object) $studentsWithDebt);

        return view('summary.index', (array) $data);
    }
}
