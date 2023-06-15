<div class="mb-3">
    <table class="filament-tables-table table-auto">
        <tbody>
            <tr>
                <td class="whitespace-nowrap">Nomor Resi</td>
                <td class="text-center" width="30">:</td>
                <td>{{ $shipping->receipt_number }}</td>
            </tr>
            <tr>
                <td class="whitespace-nowrap">Kurir</td>
                <td class="text-center" width="30">:</td>
                <td>{{ $shipping->courier_label }}</td>
            </tr>
            <tr>
                <td class="whitespace-nowrap">Nama Penerima</td>
                <td class="text-center" width="30">:</td>
                <td>{{ $shipping->recipient_name }}</td>
            </tr>
            <tr>
                <td class="whitespace-nowrap" style="vertical-align: top;">Alamat Penerima</td>
                <td class="text-center" width="30" style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $shipping->full_address }}</td>
            </tr>
        </tbody>
    </table>
</div>
<hr class="p3 mt-3">
<div class="overflow-x-auto">
    <table class="table filament-tables-table w-full text-start divide-y table-auto">
        <thead>
            <tr class="bg-white-500/5">
                <th class="filament-tables-header-cell p-0 whitespace-nowrap" width="100">Tanggal</th>
                <th class="filament-tables-header-cell p-0 whitespace-nowrap" width="100">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y whitespace-nowrap">
            @forelse ($manifests as $key => $item)
            <tr class="filament-tables-row transition hover:bg-gray-50">
                <td class="filament-tables-cell whitespace-nowrap w-10">{{ $item['timestamp'] }}</td>
                <td class="filament-tables-cell whitespace-nowrap">{{ $item['description'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">
                    <i>Belum ada data pengiriman</i>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
