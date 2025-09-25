<!DOCTYPE html>
<html>

<head>
    <title>Tasks Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Tasks Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                @foreach($selectedColumns as $col)
                <th>{{ $allColumns[$col] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @foreach($selectedColumns as $col)
                <td>
                    @if(in_array($col, ['created_at', 'due_date']) && $t->$col)
                    {{ \Carbon\Carbon::parse($t->$col)->format('d-m-Y') }}
                    @else
                    {{ $t->$col ?? '-' }}
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>