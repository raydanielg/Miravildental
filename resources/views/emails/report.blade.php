<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ripoti ya Miadi</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; }
        h2 { color: #024938; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f1f5f9; }
        .summary { margin-bottom: 20px; }
        .summary p { margin: 4px 0; }
    </style>
</head>
<body>
    <h2>Ripoti ya Miadi</h2>
    <p><strong>Kipindi:</strong> {{ $summary['period'] }}</p>
    <div class="summary">
        <p><strong>Jumla ya Miadi:</strong> {{ $summary['totalAppointments'] }}</p>
        <p><strong>Zilizokamilika:</strong> {{ $summary['completed'] }}</p>
        <p><strong>Zilizohairishwa:</strong> {{ $summary['cancelled'] }}</p>
        <p><strong>Hazijafika:</strong> {{ $summary['noShows'] }}</p>
        <p><strong>Mapato (TZS):</strong> {{ number_format($summary['revenue']) }}</p>
        <p><strong>Wagonjwa Wapya:</strong> {{ $summary['newPatients'] }}</p>
    </div>

    <h3>Huduma Zilizotolewa</h3>
    <table>
        <thead>
            <tr><th>Huduma</th><th>Idadi</th><th>Mapato (TZS)</th></tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->count }}</td>
                <td>{{ number_format($service->revenue) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px; font-size: 12px; color: #666;">Ripoti hii imetumwa kutoka mfumo wa Miravil Dental.</p>
</body>
</html>
