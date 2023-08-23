<div style="margin-bottom: 500px;">
    <strong>{{ $customer->name }}</strong>
    <p>
        <label for="">Level Kemitraan : </label> <strong>{{ $customer->customer_type['name'] }}</strong>
    </p>
    @if ($customer->is_agen || $customer->is_distributor || $customer->is_reseller)
    <div wire:init="loadPoint">
        <label for="">Total Pembelanjaan Di Tahun Ini : </label> <strong>{!! $amount_label !!}</strong> <br>
        <label for="">Point Anda Saat Ini : </label> <strong>{!! $point_label !!}</strong> <br>
    </div>
    @endif
</div>
