<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Closing Transaksi - Kumala Property</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #1E293B;
            background: #F4F6F9;
            padding: 40px 20px;
            font-size: 0.85rem;
            line-height: 1.5;
        }
        .report-sheet {
            max-width: 960px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #E2E8F0;
        }
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0E2238;
            padding-bottom: 24px;
            margin-bottom: 28px;
        }
        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #0E2238;
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 0.78rem;
            color: #64748B;
            margin-top: 4px;
        }
        .report-meta {
            text-align: right;
            font-size: 0.8rem;
            color: #64748B;
        }
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .kpi-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 16px 20px;
        }
        .kpi-box span {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            font-weight: 600;
            display: block;
        }
        .kpi-box strong {
            font-size: 1.3rem;
            color: #0E2238;
            display: block;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
            font-size: 0.8rem;
        }
        th {
            background: #0E2238;
            color: #FFFFFF;
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #E2E8F0;
        }
        tr:nth-child(even) td {
            background: #F8FAFC;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid #E2E8F0;
        }
        .sig-col {
            text-align: center;
            width: 220px;
        }
        .sig-space {
            height: 70px;
        }
        .print-btn-bar {
            max-width: 960px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-print {
            padding: 10px 20px;
            background: #0E2238;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-back {
            padding: 10px 18px;
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            color: #475569;
            border-radius: 6px;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
        }
        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }
            .report-sheet {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .print-btn-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <a href="{{ route('admin.transactions') }}" class="btn-back">&larr; Kembali ke Riwayat Transaksi</a>
        <button type="button" class="btn-print" onclick="window.print()">Cetak Dokumen / Simpan PDF</button>
    </div>

    <div class="report-sheet">
        <!-- Header -->
        <div class="header-bar">
            <div>
                <h1 class="brand-title">{{ $companyName }}</h1>
                <p class="brand-sub">LUXURY REAL ESTATE ATELIER • AUDIT REPORT</p>
                <p style="font-size: 0.75rem; color: #475569; margin-top: 6px; max-width: 440px;">{{ $hqAddress }}</p>
                <p style="font-size: 0.75rem; color: #475569;">Hotline Client Care: {{ $hotline }}</p>
            </div>
            <div class="report-meta">
                <strong style="color: #0E2238; font-size: 1rem; display: block;">REKAPITULASI CLOSING TRANSAKSI</strong>
                <span>Tanggal Terbit: {{ now()->format('d F Y') }}</span><br>
                <span>Dokumen Resmi No: AUDIT-{{ now()->format('Ymd') }}-KP</span><br>
                <span style="display: inline-block; margin-top: 6px; background: #E6FBF5; color: #059669; font-weight: 700; padding: 2px 8px; border-radius: 4px; font-size: 0.72rem;">STATUS: TERVERIFIKASI NOTARIAL</span>
            </div>
        </div>

        <!-- Summary KPIs -->
        <div class="kpi-row">
            <div class="kpi-box">
                <span>Total Transaksi Selesai</span>
                <strong>{{ count($transactions) }} Transaksi</strong>
            </div>
            <div class="kpi-box">
                <span>Akumulasi Nilai Deal</span>
                <strong style="color: #059669;">Rp {{ number_format($totalDeal, 0, ',', '.') }}</strong>
            </div>
            <div class="kpi-box">
                <span>Estimasi Komisi Profesional</span>
                <strong style="color: #0E2238;">Rp {{ number_format($totalDeal * 0.025, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Detail Table -->
        <table>
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Unit Properti</th>
                    <th>Skema</th>
                    <th>Harga Listing</th>
                    <th>Harga Deal Final</th>
                    <th>Notaris Rekanan</th>
                    <th>Status Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                <tr>
                    <td><strong>{{ $t->id }}</strong></td>
                    <td>{{ $t->date }}</td>
                    <td>
                        <strong>{{ $t->property_title }}</strong><br>
                        <span style="font-size: 0.72rem; color: #64748B;">{{ $t->property_id }} • {{ $t->location }}</span>
                    </td>
                    <td>
                        <span style="font-size: 0.72rem; font-weight: 600; padding: 2px 6px; border-radius: 4px; background: #E2E8F0; color: #1E293B;">
                            {{ $t->trx_type }}
                        </span>
                    </td>
                    <td>{{ $t->asking_price }}</td>
                    <td><strong style="color: #0E2238;">{{ $t->deal_price }}</strong></td>
                    <td style="font-size: 0.75rem;">{{ $t->notary }}</td>
                    <td>
                        <span style="color: #059669; font-weight: 600; font-size: 0.75rem;">{{ $t->status_doc }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Legal Signatures -->
        <div class="signatures">
            <div class="sig-col">
                <span style="font-size: 0.78rem; color: #64748B;">Disiapkan Oleh,</span>
                <div class="sig-space"></div>
                <strong style="display: block; border-bottom: 1px solid #1E293B; padding-bottom: 4px;">Aditya Pratama</strong>
                <span style="font-size: 0.72rem; color: #64748B;">Principal Broker / Admin</span>
            </div>

            <div class="sig-col">
                <span style="font-size: 0.78rem; color: #64748B;">Notaris Rekanan PPAT,</span>
                <div class="sig-space"></div>
                <strong style="display: block; border-bottom: 1px solid #1E293B; padding-bottom: 4px;">Dra. Sri Wahyuni, S.H., M.Kn.</strong>
                <span style="font-size: 0.72rem; color: #64748B;">Pejabat Pembuat Akta Tanah</span>
            </div>

            <div class="sig-col">
                <span style="font-size: 0.78rem; color: #64748B;">Disetujui Oleh,</span>
                <div class="sig-space"></div>
                <strong style="display: block; border-bottom: 1px solid #1E293B; padding-bottom: 4px;">Managing Director</strong>
                <span style="font-size: 0.72rem; color: #64748B;">Kumala Property Group</span>
            </div>
        </div>
    </div>

</body>
</html>
