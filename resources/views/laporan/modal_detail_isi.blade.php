<div class="row mb-3">
    <div class="col-12 col-md-6 mb-2">
        <label class="small text-muted mb-0">Konsumen</label>
        <p class="font-weight-bold mb-0">
            {{ $transaksi->konsumen->nama_konsumen ?? 'Umum' }}
        </p>
    </div>

    <div class="col-12 col-md-6 text-md-right">
        <label class="small text-muted mb-0">Mekanik</label>
        <p class="font-weight-bold mb-0">
            {{ $transaksi->mekanik->nama_mekanik ?? '-' }}
        </p>
    </div>
</div>

<table class="table table-bordered table-sm mb-0 modal-wide-table">
    <thead class="bg-light">
        <tr>
            <th>Item / Jasa</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Harga Satuan</th>
            <th class="text-right">Subtotal</th>
        </tr>
    </thead>

    <tbody>
        @foreach($transaksi->details as $d)
        <tr>
            <td>
                {{ $d->nama_item }}
                <span class="badge {{ $d->tipe == 'jasa' ? 'badge-info' : 'badge-warning' }} float-right">
                    {{ ucfirst($d->tipe) }}
                </span>
            </td>
            <td class="text-center">{{ $d->qty }}</td>
            <td class="text-right">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
            <td class="text-right font-weight-bold">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot class="bg-dark text-white">
        <tr>
            <td colspan="3" class="text-right">Total Faktur</td>
            <td class="text-right">
                Rp {{ number_format($transaksi->total_harga,0,',','.') }}
            </td>
        </tr>
    </tfoot>
</table>

