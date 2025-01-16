<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eStock</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/orders.css" />
    <link rel="stylesheet" href="css/carousel.css" />

    <style>
        /* Table container with fixed height and scrollable body */
        .table-container {
            max-height: 400px;
            /* Adjust to your desired height */
            overflow-y: auto;
            /* Enable scrolling if content overflows */
            display: block;
            width: 100%;
            margin: 20px 0;
        }

        /* Basic table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        /* Style for table header */
        thead {
            background-color: #f4f4f4;
        }

        thead th {
            font-weight: bold;
        }

        /* Footer row styling */
        tfoot {
            position: sticky;
            bottom: 0;
            background-color: #f8f8f8;
            font-weight: bold;
        }

        /* Aligning the total column in the footer */
        tfoot td {
            text-align: right;
        }

        /* Adding some extra styles for buttons and inputs */
        .cart_delete {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .cart_delete:hover {
            background-color: #ff1a1a;
        }

        .input-box__field {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }

        /* Making sure that the cart is properly aligned */
        td {
            vertical-align: middle;
        }
    </style>
</head>