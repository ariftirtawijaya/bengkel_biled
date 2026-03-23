<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
            background: #fff;
            color: #000;
        }

        .print-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .invoice-box {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .print-wrapper {
                max-width: 100%;
                padding: 0;
            }

            .invoice-box {
                border: 1px solid #ccc;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="print-wrapper">
        <?= $content; ?>
    </div>
</body>

</html>