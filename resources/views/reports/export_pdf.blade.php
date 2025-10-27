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

    <!-- Page Header (shown once) -->
    <div class="page-header" style="text-align: center; margin-bottom: 10px;">
        <h2>Tasks Report</h2>
    </div>

    <!-- Filters Section - only if filters exist -->
    @if(count($filters) > 0)
    <div class="filters" style="margin-bottom: 25px;">
        <h4>Applied Filters:</h4>
        <div class="filter-list" style="margin-top: 8px; font-size: 13px;">
            @foreach($filters as $label => $value)
            <div style="margin-bottom: 4px;">
                <strong style="text-transform: capitalize;">{{ $label }}:</strong>
                <span>{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Projects Section -->
    @foreach($projects as $projectName => $projectTasks)
    <div class="project-section" style="margin-top: 30px;">
        <!-- Project Header -->
        <div class="project-header" style="font-weight: bold; margin-bottom: 8px;">
            Project: {{ $projectName }}
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc; padding: 6px;">Sr No.</th>
                    @foreach($selectedColumns as $col)
                    <th style="border: 1px solid #ccc; padding: 6px;">{{ $allColumns[$col] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($projectTasks as $t)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 6px;">{{ $loop->iteration }}</td>
                    @foreach($selectedColumns as $col)
                    <td style="border: 1px solid #ccc; padding: 6px;">
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