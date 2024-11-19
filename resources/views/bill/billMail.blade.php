<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            background-color: #F6F6F6; 
            margin: 0;
            padding: 0;
        }
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            padding: 0;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .container{
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }
        .brand-section{
           background-color: #0d1033;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{
            padding: 16px;
            border: 1px solid gray;
        }
        .heading{
            font-size: 20px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        table td {
            vertical-align: middle !important;
            text-align: center;
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-right{
            text-align: end;
        }
        .w-20{
            width: 20%;
        }
        .float-right{
            float: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="brand-section">
            <div class="row">
                <div class="col-6">
                    <h1 class="text-white">OLT Manager</h1>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <p class="text-white">Fibex Telecom</p>
                        <!-- <p class="text-white">assdad asd asd</p>
                        <p class="text-white">+91 888555XXXX</p> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading">Invoice No.: {{ $transaction_id }}</h2>
                    <!-- <p class="sub-heading"><b></b>Tracking No. {{ $order_date }} </p> -->
                    <p class="sub-heading"><b>Order Date:</b> {{ $order_date }} </p>
                    <p class="sub-heading"><b>Email Address:</b> {{ $email }} </p>
                </div>
                <div class="col-6">
                    <p class="sub-heading"><b>Full Name:</b> {{ $name }} </p>
                    <p class="sub-heading"><b>Address:</b>  {{ $address }}</p>
                    <p class="sub-heading"><b>Phone Number:</b>  {{ $telephone }}</p>
                    <p class="sub-heading"><b>City:</b>  {{ $city }}</p>
                    <p class="sub-heading"><b>State:</b>  {{ $state }}</p>
                    <p class="sub-heading"><b>Pincode:</b>  {{ $zipCode }}</p>
                </div>
            </div>
        </div>

        <div class="body-section">
            <h3 class="heading">Ordered Items</h3>
            <br>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="w-20">Price</th>
                        <th class="w-20">Quantity</th>
                        <th class="w-20">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>${{ $item->item_price }}</td>
                        <td>{{ $item->item_quantity }}</td>
                        <td>${{ $item->item_price * $item->item_quantity }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right">Sub Total</td>
                        <td><b>${{ $total_amount }}</b></td>
                    </tr>
                    <!-- <tr>
                        <td colspan="3" class="text-right">Tax Total %1X</td>
                        <td> 2</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Grand Total</td>
                        <td> 12.XX</td>
                    </tr> -->
                </tbody>
            </table>
            <br>
            <h3 class="heading">Payment Status: Paid</h3>
            <h3 class="heading">Payment Mode: PayPal Checkout</h3>
        </div>

        <!-- <div class="body-section">
            <p>&copy; Copyright 2021 - Fabcart. All rights reserved. 
                <a href="https://www.fundaofwebit.com/" class="float-right">www.fundaofwebit.com</a>
            </p>
        </div>       -->
    </div>      

</body>
</html>
