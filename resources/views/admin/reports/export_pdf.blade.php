<!DOCTYPE html>
<html>

<head>
    <title>Tasks Report</title>
    <style>
        @page {
            margin: 100px 25px 25px 25px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
        }

        /* Fixed header that appears on every page */
        .page-header {
            position: fixed;
            top: -75px;
            left: 0;
            right: 0;
            height: 70px;
            background: white;
        }

        .page-header h2 {
            font-size: 22px;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 8px;
            margin: 0 0 10px 0;
        }

        .filters {
            background: #f8f9fa;
            padding: 8px 15px;
            border-left: 4px solid #3498db;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .filters .filter-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 3px;
            color: #555;
            font-size: 10px;
        }

        .filters .filter-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .project-header {
            color: black;
            padding: 12px 12px;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            display: table-header-group;
        }

        th {
            background: #f0f3f8;
            color: #2c3e50;
            font-weight: 600;
            padding: 8px;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #eee;
        }

        .page-break {
            page-break-after: always;
        }

        .project-section {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <!-- Fixed Header for all pages -->
    <div class="page-header">
        <h2>Tasks Report</h2>
        @if(count($filters) > 0)
        <div class="filters">
            @foreach($filters as $label => $value)
            <span class="filter-item">
                <span class="filter-label">{{ $label }}:</span> {{ $value }}
            </span>
            @endforeach
        </div>
        @else
        <div class="filters">
            <span class="filter-item">
                <span class="filter-label">Filters:</span> None Applied
            </span>
        </div>
        @endif
    </div>

    <!-- Projects Content -->
    @foreach($projects as $projectName => $projectTasks)
    <div class="project-section">
        <!-- Project Name - Will show on each page for this project -->
        <div class="project-header">
            Project: {{ $projectName }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    @foreach($selectedColumns as $col)
                    <th>{{ $allColumns[$col] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($projectTasks as $t)
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
    </div>

    <!-- Page break between projects -->
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

</body>

</html>