@foreach($laporan as $index => $row)
<tr>
    <td class="text-center">{{ ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration }}</td>
    <td class="font-weight-bold text-primary">{{ $row['no_faktur'] }}</td>
    <td class="text-center">{{ $row['tanggal'] }}</td>
    <td class="text-right">Rp {{ number_format($row['jual_part'], 0, ',', '.') }}</td>
    <td class="text-right">Rp {{ number_format($row['servis'], 0, ',', '.') }}</td>
    <td class="text-right font-weight-bold bg-light">
        Rp {{ number_format($row['omzet_kotor'], 0, ',', '.') }}
    </td>
    <td class="text-right text-info">Rp {{ number_format($row['margin_part'], 0, ',', '.') }}</td>
    <td class="text-right font-weight-bold text-success bg-light">
        Rp {{ number_format($row['omzet_bersih'], 0, ',', '.') }}
    </td>
</tr>
@endforeach