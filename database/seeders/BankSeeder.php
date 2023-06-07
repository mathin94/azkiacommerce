<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['BANK BRI', '002'],
            ['BANK EKSPOR INDONESIA', '003'],
            ['BANK MANDIRI', '008'],
            ['BANK BNI', '009'],
            ['BANK DANAMON', '011'],
            ['PERMATA BANK', '013'],
            ['BANK BCA', '014'],
            ['BANK BII', '016'],
            ['BANK PANIN', '019'],
            ['BANK ARTA NIAGA KENCANA', '020'],
            ['BANK NIAGA', '022'],
            ['BANK BUANA IND', '023'],
            ['BANK LIPPO', '026'],
            ['BANK NISP', '028'],
            ['AMERICAN EXPRESS BANK LTD', '030'],
            ['CITIBANK N.A.', '031'],
            ['JP. MORGAN CHASE BANK, N.A.', '032'],
            ['BANK OF AMERICA, N.A', '033'],
            ['ING INDONESIA BANK', '034'],
            ['BANK MULTICOR TBK.', '036'],
            ['BANK ARTHA GRAHA', '037'],
            ['BANK CREDIT AGRICOLE INDOSUEZ', '039'],
            ['THE BANGKOK BANK COMP. LTD', '040'],
            ['THE HONGKONG & SHANGHAI B.C.', '041'],
            ['THE BANK OF TOKYO MITSUBISHI UFJ LTD', '042'],
            ['BANK SUMITOMO MITSUI INDONESIA', '045'],
            ['BANK DBS INDONESIA', '046'],
            ['BANK RESONA PERDANIA', '047'],
            ['BANK MIZUHO INDONESIA', '048'],
            ['STANDARD CHARTERED BANK', '050'],
            ['BANK ABN AMRO', '052'],
            ['BANK KEPPEL TATLEE BUANA', '053'],
            ['BANK CAPITAL INDONESIA, TBK.', '054'],
            ['BANK BNP PARIBAS INDONESIA', '057'],
            ['BANK UOB INDONESIA', '058'],
            ['KOREA EXCHANGE BANK DANAMON', '059'],
            ['RABOBANK INTERNASIONAL INDONESIA', '060'],
            ['ANZ PANIN BANK', '061'],
            ['DEUTSCHE BANK AG.', '067'],
            ['BANK WOORI INDONESIA', '068'],
            ['BANK OF CHINA LIMITED', '069'],
            ['BANK BUMI ARTA', '076'],
            ['BANK EKONOMI', '087'],
            ['BANK ANTARDAERAH', '088'],
            ['BANK HAGA', '089'],
            ['BANK IFI', '093'],
            ['BANK CENTURY, TBK.', '095'],
            ['BANK MAYAPADA', '097'],
            ['BANK JABAR', '110'],
            ['BANK DKI', '111'],
            ['BPD DIY', '112'],
            ['BANK JATENG', '113'],
            ['BANK JATIM', '114'],
            ['BPD JAMBI', '115'],
            ['BPD ACEH', '116'],
            ['BANK SUMUT', '117'],
            ['BANK NAGARI', '118'],
            ['BANK RIAU', '119'],
            ['BANK SUMSEL', '120'],
            ['BANK LAMPUNG', '121'],
            ['BPD KALSEL', '122'],
            ['BPD KALIMANTAN BARAT', '123'],
            ['BPD KALTIM', '124'],
            ['BPD KALTENG', '125'],
            ['BPD SULSEL', '126'],
            ['BANK SULUT', '127'],
            ['BPD NTB', '128'],
            ['BPD BALI', '129'],
            ['BANK NTT', '130'],
            ['BANK MALUKU', '131'],
            ['BPD PAPUA', '132'],
            ['BANK BENGKULU', '133'],
            ['BPD SULAWESI TENGAH', '134'],
            ['BANK SULTRA', '135'],
            ['BANK NUSANTARA PARAHYANGAN', '145'],
            ['BANK SWADESI', '146'],
            ['BANK MUAMALAT', '147'],
            ['BANK MESTIKA', '151'],
            ['BANK METRO EXPRESS', '152'],
            ['BANK SHINTA INDONESIA', '153'],
            ['BANK MASPION', '157'],
            ['BANK HAGAKITA', '159'],
            ['BANK GANESHA', '161'],
            ['BANK WINDU KENTJANA', '162'],
            ['HALIM INDONESIA BANK', '164'],
            ['BANK HARMONI INTERNATIONAL', '166'],
            ['BANK KESAWAN', '167'],
            ['BANK TABUNGAN NEGARA (PERSERO)', '200'],
            ['BANK HIMPUNAN SAUDARA 1906, TBK .', '212'],
            ['BANK TABUNGAN PENSIUNAN NASIONAL', '213'],
            ['BANK SWAGUNA', '405'],
            ['BANK JASA ARTA', '422'],
            ['BANK MEGA', '426'],
            ['BANK JASA JAKARTA', '427'],
            ['BANK BUKOPIN', '441'],
            ['BANK SYARIAH MANDIRI', '451'],
            ['BANK BISNIS INTERNASIONAL', '459'],
            ['BANK SRI PARTHA', '466'],
            ['BANK JASA JAKARTA', '472'],
            ['BANK BINTANG MANUNGGAL', '484'],
            ['BANK BUMIPUTERA', '485'],
            ['BANK YUDHA BHAKTI', '490'],
            ['BANK MITRANIAGA', '491'],
            ['BANK AGRO NIAGA', '494'],
            ['BANK INDOMONEX', '498'],
            ['BANK ROYAL INDONESIA', '501'],
            ['BANK ALFINDO', '503'],
            ['BANK SYARIAH MEGA', '506'],
            ['BANK INA PERDANA', '513'],
            ['BANK HARFA', '517'],
            ['PRIMA MASTER BANK', '520'],
            ['BANK PERSYARIKATAN INDONESIA', '521'],
            ['BANK AKITA', '525'],
            ['LIMAN INTERNATIONAL BANK', '526'],
            ['ANGLOMAS INTERNASIONAL BANK', '531'],
            ['BANK DIPO INTERNATIONAL', '523'],
            ['BANK KESEJAHTERAAN EKONOMI', '535'],
            ['BANK UIB', '536'],
            ['BANK JAGO', '542'],
            ['BANK PURBA DANARTA', '547'],
            ['BANK MULTI ARTA SENTOSA', '548'],
            ['BANK MAYORA', '553'],
            ['BANK INDEX SELINDO', '555'],
            ['BANK VICTORIA INTERNATIONAL', '566'],
            ['BANK EKSEKUTIF', '558'],
            ['CENTRATAMA NASIONAL BANK', '559'],
            ['BANK FAMA INTERNASIONAL', '562'],
            ['BANK SINAR HARAPAN BALI', '564'],
            ['BANK HARDA', '567'],
            ['BANK FINCONESIA', '945'],
            ['BANK MERINCORP', '946'],
            ['BANK MAYBANK INDOCORP', '947'],
            ['BANK OCBC – INDONESIA', '948'],
            ['BANK CHINA TRUST INDONESIA', '949'],
            ['BANK COMMONWEALTH', '950']
        ];

        foreach ($banks as $key => $item) {
            Bank::updateOrCreate([
                'code' => $item[1],
            ], [
                'name' => $item[0],
            ]);
        }
    }
}
