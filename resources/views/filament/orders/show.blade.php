<section class="py-20 bg-black">
    <div class="max-w-5xl mx-auto py-16 bg-white">
        <article class="overflow-hidden">
            <div class="bg-[white] rounded-b-md">

                <div class="p-9 mb-3">
                    <div class="flex flex-col mx-0 mt-8">
                        <table class="table-auto">
                            <tbody>
                                <tr>
                                    <td>No. Order</td>
                                    <td>:</td>
                                    <td>{{ $order->number }}</td>
                                </tr>
                                <tr>
                                    <td>No. Invoice</td>
                                    <td>:</td>
                                    <td>{{ $order->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pembelian</td>
                                    <td>:</td>
                                    <td>{{ $order->date_format_id }}</td>
                                </tr>
                                <tr>
                                    <td>Status Order</td>
                                    <td>:</td>
                                    <td>{{ $order->status->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="p-9 mt-3">
                    <div class="flex flex-col mx-0 mt-8">
                        <table class="min-w-full divide-y divide-slate-500">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="hidden py-3.5 px-3 text-right text-sm font-normal text-slate-700 sm:table-cell">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="hidden py-3.5 px-3 text-right text-sm font-normal text-slate-700 sm:table-cell">
                                        Harga Satuan
                                    </th>
                                    <th scope="col"
                                        class="py-3.5 pl-3 pr-4 text-right text-sm font-normal text-slate-700 sm:pr-6 md:pr-0">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr class="border-b border-slate-200">
                                        <td class="py-4 pl-4 pr-3 text-sm sm:pl-6 md:pl-0">
                                            <div class="font-medium text-slate-700">{{ $item->name }}</div>
                                        </td>
                                        <td class="hidden px-3 py-4 text-sm text-right text-slate-500 sm:table-cell">
                                            x{{ $item->quantity }}
                                        </td>
                                        <td class="hidden px-3 py-4 text-sm text-right text-slate-500 sm:table-cell">
                                            {{ $item->price_label }}
                                        </td>
                                        <td class="py-4 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                            {{ $item->total_price_label }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row" colspan="3"
                                        class="hidden pt-6 pl-6 pr-3 text-sm font-light text-right text-slate-500 sm:table-cell md:pl-0">
                                        Subtotal
                                    </th>
                                    <th scope="row"
                                        class="pt-6 pl-4 pr-3 text-sm font-light text-left text-slate-500 sm:hidden">
                                        Subtotal
                                    </th>
                                    <td class="pt-6 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                        {{ $order->subtotal_label }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3"
                                        class="hidden pt-6 pl-6 pr-3 text-sm font-light text-right text-slate-500 sm:table-cell md:pl-0">
                                        Ongkos Kirim
                                    </th>
                                    <th scope="row"
                                        class="pt-6 pl-4 pr-3 text-sm font-light text-left text-slate-500 sm:hidden">
                                        Ongkos Kirim
                                    </th>
                                    <td class="pt-6 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                        {{ $order->shipping_cost_label }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3"
                                        class="hidden pt-4 pl-6 pr-3 text-sm font-normal text-right text-slate-700 sm:table-cell md:pl-0">
                                        Total
                                    </th>
                                    <th scope="row"
                                        class="pt-4 pl-4 pr-3 text-sm font-normal text-left text-slate-700 sm:hidden">
                                        Total
                                    </th>
                                    <td
                                        class="pt-4 pl-3 pr-4 text-sm font-normal text-right text-slate-700 sm:pr-6 md:pr-0">
                                        {{ $order->grandtotal_label }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
