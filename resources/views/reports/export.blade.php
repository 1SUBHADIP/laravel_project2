<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $export['title'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111827;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 6px;
        }
        .meta {
            margin-bottom: 16px;
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #e5e7eb;
            font-weight: 700;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <h1>{{ $export['title'] }}</h1>
    <div class="meta">Generated at {{ now()->format('Y-m-d H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                @foreach($export['columns'] as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($export['rows'] as $row)
                <tr>
                    @foreach($export['columns'] as $column)
                        <td>{{ $row[$column] ?? '' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($export['columns']) }}">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
