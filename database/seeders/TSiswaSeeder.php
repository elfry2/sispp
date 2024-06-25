<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* TSiswa::factory(40)->create(); */
        $rows = [
            [
                '01001005432',
                'Munawaroh',
                'Bangun Jaya',
                '2003-12-07',
                'Bangun Jaya',
                '0',
                'Udin',
                '085363419225',
                2,
                200000.00,
                '2024-06-05 00:29:57',
                '2024-06-05 00:29:57'
            ],['01011015432','Fajar Nahot Pakpahan','Jl. Sultan Syarif Kasim','2005-07-17','Medan','1','Lina Br Ruma Horbo','081365781740',1,200000.00,'2024-06-05 01:01:52','2024-06-05 01:01:52'],['01021025432','Adzril Kurniawan L','Jl. Sultan Syarif Kasim','2007-05-02','Pekanbaru','1','Agusanto','081365454532',1,200000.00,'2024-06-05 01:16:28','2024-06-05 01:16:28'],['0480485432','Rian Iqbal Duha','PTPN IV Sawit Langkat','2003-08-23','Ps. Langkat','1','Afrianto Duha','082169657626',3,200000.00,'2024-06-05 00:17:19','2024-06-05 00:17:19'],['0500505432','Riski Kurniadi','Pondok 1 Kandista','2003-03-12','Kandis','1','Kasiadi','082287717954',2,200000.00,'2024-06-05 00:49:08','2024-06-05 00:49:08'],['0510515432','Rizky Andrean','Kota Bangun','2002-09-04','Kota bangun','1','Erwadi','081374953059',2,200000.00,'2024-06-05 00:51:00','2024-06-05 00:51:00'],['056565432','Murdan Febrianto','Pondok 1 Palapa','2003-08-30','Siak','1','Lilik','082284420566',3,200000.00,'2024-06-05 00:02:17','2024-06-05 00:02:17'],['057575432','Dimas Triansyah','PKS Sam - Sam','2004-02-09','Belutu','1','Syahdan','082288316793',3,200000.00,'2024-06-04 22:34:07','2024-06-04 22:34:07'],['059595432','Elman Fernando Hulu','Jl. Jendral Sudirman','2004-03-14','Nanggala','1','Kliwon','082169144511',3,200000.00,'2024-06-04 22:39:47','2024-06-04 22:39:47'],['060605432','Ade Irwansyah','Gondang','2003-06-03','Aek Nabara','1','Karimuddin','082266943262',3,200000.00,'2024-06-04 22:23:40','2024-06-04 22:23:40'],['061615432','Renaldi Halomon Siahaan','PDK Pks Sam - Sam','2003-12-25','Kandis','1','Astoh Siahaan','081371966597',3,200000.00,'2024-06-05 00:03:59','2024-06-05 00:03:59'],['062625432','Rusdiansyah','Pondok 1 Palapa','2003-10-08','Belutu','1','Amli','081277435817',3,200000.00,'2024-06-05 00:07:05','2024-06-05 00:07:05'],['064645432','Supriyadi','Kota Garo','2001-08-13','Kota Garo','1','Suyatno','081276692830',3,200000.00,'2024-06-05 00:10:14','2024-06-05 00:10:14'],['065655432','Azwar Andi Surya S','Dusun Nenggala','2004-03-16','Brussel','1','Andy Branan Sinulingga','082288867997',3,200000.00,'2024-06-04 22:32:21','2024-06-04 22:32:21'],['066665432','Dika Ardiansyah','Jl. Sultan Syarif Kasim','2004-07-28','Belutu','1','Sucipto','085213021909',3,200000.00,'2024-06-04 22:36:37','2024-06-04 22:36:37'],['067675432','Diki Saputra','Jl. Sultan Syarif Kasim','2004-07-28','Belutu','1','Sucipto','085213021909',3,200000.00,'2024-06-04 22:38:01','2024-06-04 22:38:01'],['069695432','Muhammad Rizki Wahyudi','Jl. Sultan Syarif Kasim','2003-09-21','Kandis','1','Kusno','082170788018',3,200000.00,'2024-06-04 22:46:15','2024-06-04 22:46:15'],['070705432','Ahmad Rizky Harahap','PDK Pks Sam - Sam','2004-02-03','Kandis','1','Illham Muda Harahap','082285241304',3,200000.00,'2024-06-04 22:27:16','2024-06-04 22:27:16'],['071715432','Ade Ardiansyah Putra','Jl. Datuk Setia Amanah','2002-03-04','Jakarta','1','Rohmawati','085363319216',3,200000.00,'2024-06-04 22:21:28','2024-06-04 22:21:28'],['072725432','Salim','Jl. Sultan Syarif Kasim','2004-06-28','Selat Panjang','1','Talizhi Soki','082172020574',3,200000.00,'2024-06-05 00:08:42','2024-06-05 00:08:42'],['073735432','Ayu Suci Sundari','Kandis','2004-12-12','Hessa Air Genting','0','Yusyanto','082253002817',3,200000.00,'2024-06-04 22:29:40','2024-06-04 22:29:40'],['074745432','Rezi Dwiya Fitri','Jl. Sultan Syarif Kasim','2003-12-23','Bagan Batu','0','Angatmin','082386948424',3,200000.00,'2024-06-05 00:05:41','2024-06-05 00:05:41'],['075755432','Jhoni Alexander Aditya','Desa Pauh','2004-05-22','Kandis','1','Suprianto','082283388873',3,200000.00,'2024-06-04 22:43:18','2024-06-04 22:43:18'],['076765432','Abdul Zikri Sinaga','Jl. Sultan Syarif Kasim','2004-04-24','Tebing Tinggi','1','Irmawan Sinaga','085218155890',3,200000.00,'2024-06-04 22:18:27','2024-06-04 22:18:27'],['077775432','Yita Meliani','Jl. Natuna','2004-02-16','Siak','0','Suroto','081371831517',3,200000.00,'2024-06-05 00:12:20','2024-06-05 00:12:20'],['079795432','Muhammad Anggi Sinaga','Jl. Sultan Syarif Kasim','2005-11-25','Tebing Tinggi','1','Irmawan Sinaga','081262341412',2,200000.00,'2024-06-05 00:25:52','2024-06-05 00:25:52'],['080805432','Muhammad Agus Salim','Pondok 5 Sam - Sam','2005-12-28','Kandis','1','Bambang Harianto','081378278920',2,200000.00,'2024-06-05 00:24:19','2024-06-05 00:24:19'],['081815432','Rizqi Firmansyah','Jl. Raya Pekanbaru','2005-08-06','Kandis','1','Endrianto','081265566727',2,200000.00,'2024-06-05 00:46:54','2024-06-05 00:46:54'],['082825432','Bagus Satrio','Jl. Sultan Syarif Kasim','2006-04-19','Kandis','1','Nasib','082369412528',1,200000.00,'2024-06-05 00:55:03','2024-06-05 00:55:03'],['083835432','Afrian Syahputra','Dusun Kandista','2006-04-18','Siak','1','Tarso','082386818366',1,200000.00,'2024-06-05 00:53:56','2024-06-05 00:53:56'],['084845432','Deswardana Iswanto','Pauh Bonai Darusalam','2005-12-29','Kandis','1','Ed Swanto','081270072917',1,200000.00,'2024-06-05 00:58:09','2024-06-05 00:58:09'],['085855432','Muhammad Aji Indra','Pauh','2007-01-01','Waduk','1','Murd','085278094704',1,200000.00,'2024-06-05 01:09:31','2024-06-05 01:09:31'],['087875432','Sri Devi Gusnianti Gea','Pauh','2006-08-17','Sp Buana 3','0','Warsman Gea','082172638494',1,200000.00,'2024-06-05 01:11:09','2024-06-05 01:11:09'],['088885432','Habib Mulyadi','Jl. Raya Pekanbaru - Duri','2005-12-12','Kandis','1','Syamsul Anuar','08127660413',1,200000.00,'2024-06-05 01:07:09','2024-06-05 01:07:09'],['089895432','Fahri Pratama Adreansyah','Jl. Sultan Syarif Kasim','2005-12-12','Duri','1','L Saputra','085297593929',1,200000.00,'2024-06-05 01:03:54','2024-06-05 01:03:54'],['090905432','Ridho Purwa Pratama','Pondok 1 Kandista','2007-06-04','Siak','1','Purwanto','085265896328',1,200000.00,'2024-06-05 01:14:47','2024-06-05 01:14:47'],['091915432','Dian Puspita Sari','Dusun Kandista','2007-02-02','Siak','0','Sugito','082382740907',1,200000.00,'2024-06-05 01:13:20','2024-06-05 01:13:20'],['092925432','Deni Fransisko Situmeang','Pondok 1 Kandista','2006-03-28','Kandis','1','Janplensius Situmeang','082268484292',1,200000.00,'2024-06-05 00:56:39','2024-06-05 00:56:39'],['093935432','Putra Royando Simbolon','Desa Cinta Damai','2005-08-02','Kota Garo','1','Ronal Everanto Simbolon','081261553866',2,200000.00,'2024-06-05 00:32:18','2024-06-05 00:32:18'],['094945432','Refill Sembiring','Jl. Raya Km. 80','2005-01-25','Kandis','1','Muatna Sembiring','085376710745',2,200000.00,'2024-06-05 00:41:19','2024-06-05 00:41:19'],['095955432','Juan Daniel Ginting','Kandis','2004-12-03','Kandis','1','Antoni Melki Ginting','082296602281',2,200000.00,'2024-06-05 00:22:44','2024-06-05 00:22:44'],['096965432','Firdaus','Jl. Padat Karya Km. *0','2003-07-11','Belawan','1','Saut Hutabarat','08127723370',2,200000.00,'2024-06-05 00:43:06','2024-06-05 00:43:06'],['097975432','Ariko Keke','Kandis','2004-11-30','Medan','1','Anto Sembiring','085173359945',2,200000.00,'2024-06-05 00:19:25','2024-06-05 00:19:51'],['098985432','Royan Muriyanto','Jl. Sultan Syarif Kasim','2005-04-02','Kampar','1','Adi Sucipto','085375431800',2,200000.00,'2024-06-05 00:45:01','2024-06-05 00:45:01'],['099995432','Ratna Sulistianingsih','Jl. Raja Ali Haji','2005-08-02','Kandis','0','Daud','082169356634',2,200000.00,'2024-06-05 00:39:03','2024-06-05 00:39:03'],['123577744','anton','kandis','2008-07-16','Kandis','1','budi','0822357365634',1,200000.00,'2024-03-13 22:07:32','2024-03-13 22:07:32'],['2301001','Azwandi','SP 2 Umum','2006-10-06','Tanjung Balai','1','Maswan','082173116541',1,200000.00,'2024-06-04 21:44:31','2024-06-04 21:44:31'],['2301004','Jekson','Waduk Km. 25','2008-06-17','Waduk','1','Eli Fao Lase','082288856250',1,200000.00,'2024-06-04 21:50:42','2024-06-04 21:50:42'],['2301005','Lestari Kurnia Sri Dewi Gulo','Libo Jaya Km. 17','2007-11-13','Pekanbaru','0','Hasali Gulo','085640644271',1,200000.00,'2024-06-04 21:53:17','2024-06-04 21:53:17'],['2301006','Liani Zebua','Waduk Km. 28','2008-07-08','Sibolga','0','Sadaria Laoli','082269980546',1,200000.00,'2024-06-04 21:55:46','2024-06-04 21:55:46'],['2301007','Muhammad Fathan Fisyawal','Kandis Km. 72','2007-10-13','Jakarta','1','Erial','081266777932',1,200000.00,'2024-06-04 21:47:36','2024-06-04 21:58:08'],['2301009','Putranus Halawa','Waduk Km. 25','2007-03-19','Libo Pauh','1','Desember Halawa','082299486475',1,200000.00,'2024-06-04 22:01:25','2024-06-04 22:01:25'],['2301010','Reka Puspita Sari','Jl. Pekanbaru - Duri Km. 78','2008-03-14','Cinta Damai','0','Supiah','082217676249',1,200000.00,'2024-06-04 22:03:20','2024-06-04 22:03:20'],['2301011','Ririn Elfina','Dusun Kandista','2008-07-12','Duri','0','Riadi','082284082758',1,200000.00,'2024-06-04 22:05:33','2024-06-04 22:05:33'],['2335566','dewi','kandis','2024-05-22','Kandis','0','budi','0822357365634',2,200000.00,'2024-05-15 21:17:48','2024-05-15 21:17:48'],['356578','RERE','kandis','2024-05-05','Kandis','0','RERE','7667534565',2,200000.00,'2024-05-15 21:45:15','2024-05-15 21:45:15']
        ];

            /* $table->string('nis', 20)->unique(); */
            /* $table->primary('nis'); */
            /* $table->string('nama_siswa', 30); */
            /* $table->string('alamat'); */
            /* $table->date('tgl_lahir'); */
            /* $table->string('tempat_lahir', 30); */
            /* $table->string('jk', 15); */
            /* $table->string('nama_orang_tua', 30); */
            /* $table->string('no_hp', 15); */
            /* $table->unsignedBigInteger('kd_kls'); */
            /* $table->foreign('kd_kls')->references('kd_kls')->on('t_kelas'); */
            /* $table->decimal('spp_perbulan', 9, 2); */
        /* $table->timestamps(); */

        foreach ($rows as $row) {
            DB::table('t_siswa')->insert([
                'nis' => $row[0],
                'nama_siswa' => $row[1],
                'alamat' => $row[2],
                'tgl_lahir' => $row[3],
                'tempat_lahir' => $row[4],
                'jk' => $row[5],
                'nama_orang_tua' => $row[6],
                'no_hp' => $row[7],
                'kd_kls' => $row[8],
                'spp_perbulan' => $row[9],
            ]);
        }
    }
}
