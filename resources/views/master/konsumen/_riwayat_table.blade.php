@foreach ($riwayat as $t)
<div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
        <span class="small fw-bold">
            <i class="bi bi-calendar3 me-2"></i>
            {{ $t->created_at->format('d M Y') }}
        </span>

        <span class="badge bg-primary">
            #{{ $t->kode_transaksi }}
        </span>
    </div>

    <div class="card-body p-0">

        <div class="riwayat-table-scroll">

            <table class="table table-sm table-hover mb-0">

                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Jasa / Layanan</th>
                        <th>Sparepart / Barang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($t->details as $d)
                    <tr>

                        <td class="ps-3 align-middle">
                            @if ($d->tipe == 'jasa')
                                <div class="fw-bold text-dark">{{ $d->nama_item }}</div>
                                <small class="text-muted">Kategori: Jasa</small>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        <td class="align-middle">
                            @if ($d->tipe == 'sparepart')
                                <div class="fw-bold text-primary">{{ $d->nama_item }}</div>
                                <small class="text-muted">Kategori: Part</small>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        <td class="text-center align-middle">
                            <span class="badge bg-light text-dark border">
                                {{ $d->qty }}
                            </span>
                        </td>

                        <td class="text-end pe-3 align-middle fw-bold">
                            Rp {{ number_format($d->subtotal,0,',','.') }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>

                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end ps-3">
                            TOTAL PEMBAYARAN
                        </td>
                        <td class="text-end pe-3 text-primary">
                            Rp {{ number_format($t->total_harga,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>
@endforeach
