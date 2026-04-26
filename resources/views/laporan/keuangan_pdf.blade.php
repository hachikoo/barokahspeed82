<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: right; }
        th { background: #f2f2f2; text-align: center; }
        .text-left { text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN BENGKEL</h2>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Jual Part</th>
                <th>Margin Part</th>
                <th>Total Servis</th>
                <th>Omzet Kotor</th>
                <th>Profit Bersih</th>
            </tr>
        </thead>
        <tbody>
            @php $total = ['part'=>0, 'margin'=>0, 'servis'=>0, 'kotor'=>0, 'bersih'=>0]; @endphp
            @foreach($laporan as $index => $t)
                @php
                    $p = $t->details->where('tipe','sparepart')->sum('subtotal');
                    $s = $t->details->where('tipe','jasa')->sum('subtotal');
                    $m = $t->details->where('tipe','sparepart')->sum(fn($d) => ($d->harga_satuan - ($d->sparepart->harga_beli ?? 0)) * $d->qty);
                    $k = $p + $s;
                    $b = $m + $s;
                    
                    $total['part'] += $p; $total['margin'] += $m; $total['servis'] += $s;
                    $total['kotor'] += $k; $total['bersih'] += $b;
                @endphp
                <tr>
                    <td style="text-align:center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $t->no_faktur }}</td>
                    <td style="text-align:center">{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>{{ number_format($p, 0, ',', '.') }}</td>
                    <td>{{ number_format($m, 0, ',', '.') }}</td>
                    <td>{{ number_format($s, 0, ',', '.') }}</td>
                    <td>{{ number_format($k, 0, ',', '.') }}</td>
                    <td>{{ number_format($b, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="font-weight:bold; background:#eee;">
            <tr>
                <td colspan="3">TOTAL KESELURUHAN</td>
                <td>{{ number_format($total['part'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['margin'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['servis'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['kotor'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['bersih'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>