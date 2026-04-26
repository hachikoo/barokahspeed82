<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            width: 58mm;
            margin: 0;
            padding: 5mm 2mm;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.5;
            /* Jarak antar baris tidak mepet */
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: 600;
        }

        .header {
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .nama-bengkel {
            font-size: 16px;
            display: block;
            margin-bottom: 2px;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 3px 0;
        }

        .price {
            text-align: right;
        }

        .section-title {
            font-size: 10px;
            font-weight: 600;
            text-decoration: underline;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <span class="nama-bengkel bold">BAROKAH SPEED 82</span>
        <small>Jl. Pasar Antri (Belakang Pasar) , Cimahi</small><br>
        <small>No Telp/WA : 0813-2333-3779</small>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>No. Faktur</td>
            <td class="price bold">{{ $transaksi->no_faktur }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td class="price bold">{{ $transaksi->konsumen->nama_konsumen ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Polisi</td>
            <td class="price bold">{{ $transaksi->no_polisi }}</td>
        </tr>
        <tr>
            <td>Mekanik</td>
            <td class="price">{{ $transaksi->mekanik->nama_mekanik ?? '-' }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td class="price">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    @php $jasas = $transaksi->details->where('tipe', 'jasa'); @endphp
    @if ($jasas->count() > 0)
        <span class="section-title">LAYANAN SERVIS</span>
        <table>
            @foreach ($jasas as $jasa)
                <tr>
                    <td>{{ $jasa->nama_item }}</td>
                    <td class="price bold">{{ number_format($jasa->subtotal) }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @php $parts = $transaksi->details->where('tipe', 'sparepart'); @endphp
    @if ($parts->count() > 0)
        <div style="margin-top: 5px;"></div>
        <span class="section-title">SUKU CADANG / PART</span>
        <table>
            @foreach ($parts as $part)
                <tr>
                    <td>
                        {{-- Ganti $part->nama_item menjadi seperti di bawah ini --}}
                        {{ $part->sparepart->nama_part ?? $part->nama_item }}<br>
                        <small>{{ $part->qty }} x {{ number_format($part->harga_satuan) }}</small>
                    </td>
                    <td class="price bold">{{ number_format($part->subtotal) }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <div class="line"></div>

    <table>
        <tr style="font-size: 13px;" class="bold">
            <td>TOTAL AKHIR</td>
            <td class="price">Rp {{ number_format($transaksi->total_harga) }}</td>
        </tr>
        <tr>
            <td>BAYAR</td>
            <td class="price">Rp{{ number_format($transaksi->bayar) }}</td>
        </tr>
        <tr class="bold">
            <td>KEMBALI</td>
            <td class="price">Rp {{ number_format(abs($transaksi->kembali)) }}</td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 20px;">
        <small>*** TERIMA KASIH ***</small><br>
        <small>Kepuasan Anda Prioritas Kami</small>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.location.href = "{{ url('/') }}";
            }, 1000);

        };
    </script>
</body>

</html>
