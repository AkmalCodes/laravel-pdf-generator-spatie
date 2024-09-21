<!DOCTYPE html>
<html>

<head>
    <title>Inventory Report</title>
    <style>
        @page {
            margin: 5 5 5 5; /* top, right, bottom, left */
        }

        *{
            font-size: 0.9em;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
            border-bottom: 1px solid rgba(182, 177, 177, 0.568);
        }

        img{
            height:75px;
            widht:auto;
        }

        .header-details{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
            width: 100%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="header">
        <div class="container d-flex flex-column align-items-center">
            <img class="img-logo" src="{{ $m9_logo }}" alt="Merchant9 Logo">
            <div class="header-details">
                <h1>Sales Report</h1>
                <h4>Sale period: <strong>{{ $report_generated_at_start }}</strong> to
                    <strong>{{ $report_generated_at_end }}</strong>
                </h4>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Category Code</th>
                    <th>Gold Weight Categories</th>
                    <th>Total Sales Amount</th>
                    <th>Items Sold</th>
                </tr>
            </thead>
            @foreach ($inventoryData as $description => $data)
                <tbody>
                    <tr>
                        <td>{{ $description }}</td>
                        <td>{{ $data['category_code'] }}</td>
                        <td>
                            @php
                            // Check if all gold weight categories are empty or zero
                            $allEmpty = true;
                            $categories = ['0-5', '5-10', '10-15', '15+', '20+'];
                            
                            // Check if any category has a non-zero value
                            foreach ($categories as $category) {
                                if (!empty($data['gold_weight_categories'][$category])) {
                                    $allEmpty = false;
                                    break;
                                }
                            }
                            @endphp
                            @if($allEmpty)
                                <strong>Items calculated by individual product instead of gold weight.</strong>
                            @else
                                <div>0-5g: {{ $data['gold_weight_categories']['0-5'] ?? 0 }}</div>
                                <div>5-10g: {{ $data['gold_weight_categories']['5-10'] ?? 0 }}</div>
                                <div>10-15g: {{ $data['gold_weight_categories']['10-15'] ?? 0 }}</div>
                                <div>15+g: {{ $data['gold_weight_categories']['15+'] ?? 0 }}</div>
                                <div>20+g: {{ $data['gold_weight_categories']['20+'] ?? 0 }}</div>
                            @endif
                        </td>                        
                        <td>{{ $data['sales_amount'] }}</td>
                        <td>
                            @foreach ($data['store_counts'] as $storecode => $count)
                                <div class="container p-0">
                                    <strong>{{ $storecode }}:</strong>  {{ $count }}
                                </div>
                            @endforeach
                            <div class="container p-0">
                                <strong>Total:</strong> {{ $data['items_sold_count'] }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            @endforeach
        </table>
    </div>
</body>

</html>
