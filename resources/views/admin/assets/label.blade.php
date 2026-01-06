<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Asset - {{ $asset->name }}</title>
    <style>
        @media print {
            @page { size: 80mm 60mm; margin: 5mm; }
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 16px;
        }
        .card {
            width: 320px;
            background: white;
            border: 1px solid #dbe1e6;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .name {
            font-weight: 800;
            font-size: 16px;
            line-height: 1.2;
            color: #111518;
        }
        .meta {
            font-size: 11px;
            color: #617989;
            line-height: 1.4;
        }
        .qr {
            display: flex;
            justify-content: center;
            margin: 8px 0;
        }
        .qr svg {
            width: 180px;
            height: 180px;
        }
        .footer {
            font-size: 11px;
            color: #111518;
            text-align: center;
            line-height: 1.3;
        }
        .actions {
            margin-top: 12px;
            text-align: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #dbe1e6;
            background: #fff;
            color: #111518;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div>
                <div class="name">{{ $asset->name }}</div>
                <div class="meta">
                    Barcode: {{ $asset->barcode }}<br>
                    Serial: {{ $asset->serial_number }}<br>
                    {{ optional($asset->category)->name ?? 'Kategori -' }}
                </div>
            </div>
        </div>

        @if($qrSvg)
        <div class="qr">{!! $qrSvg !!}</div>
        @else
        <div class="qr" style="color:#c0392b;font-size:12px;">QR package belum di-install</div>
        @endif

        <div class="footer">
            {{ optional($asset->location)->name ?? 'Lokasi -' }}<br>
            Dept: {{ optional($asset->department)->department ?? '-' }}
        </div>

        <div class="actions no-print">
            <button class="btn" onclick="window.print()">
                🖨 Cetak Label
            </button>
        </div>
    </div>
</body>
</html>


