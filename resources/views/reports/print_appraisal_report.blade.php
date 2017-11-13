@extends('layouts.reports')
@section('content')
    <style>
        @media print {
            * {
                font-size: 1.5vw;
            }
        }

        * {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #fff;
            color: #000;
        }

        .b-bottom {
            border-bottom: 1px solid #000;
        }

        .b-bottom-2 {
            border-bottom: 5px double #000;
        }

        .w-80 {
            width: 80%;
        }

        .indent-1 {
            padding-left: 40px;
        }

        .indent-2 {
            padding-left: 80px;
        }

        .indent-3 {
            padding-left: 120px;
        }

        .signatory-table{
            width: 100%;
            border-collapse: collapse;
        }

        .signatory-table td{
            border: 1px solid #000;
            text-align: center;
        }

        .indent-top {
            margin-top:12px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                {{ config('app.name', 'Company Name') }}
            </div>
        </div>

        <div class="row" style="margin-top:24px;">
            <div class="col-xs-2">
                Applicant
            </div>
            <div class="col-xs-4">
                :{{ $collateral->customer->name }}
            </div>

            <div class="col-xs-2">
                Date
            </div>
            <div class="col-xs-4">
                :{{ date("F j, Y H:i:s") }}
            </div>

            <div class="col-xs-2">
                Amount
            </div>
            <div class="col-xs-4">
                :{{ number_format($collateral->loan->amount,2) }}
            </div>

            <div class="col-xs-2">
                Term
            </div>
            <div class="col-xs-4">
                :{{ $collateral->loan->term }} months
            </div>
        </div>

        <div class="row indent-top" style="border-top:3px double #000; border-bottom:3px double #000; padding:10px 0px;">
            <div class="col-xs-12 text-center">
                APPRAISAL REPORT
            </div>

            <div class="col-xs-12 text-left">
                UNIT IDENTIFICATION:
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-3">
                        Make
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->make }}
                    </div>
                    <div class="col-xs-3">
                        Plate Number
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->plate }}
                    </div>

                    <div class="col-xs-3">
                        Model
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->model }}
                    </div>
                    <div class="col-xs-3">
                        Motor Number
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->motor }}
                    </div>

                    <div class="col-xs-3">
                        Type
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->type }}
                    </div>
                    <div class="col-xs-3">
                        Serial Number
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->serial }}
                    </div>

                    <div class="col-xs-offset-6 col-xs-3">
                        Odometer
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->odometer }}
                    </div>

                    <div class="col-xs-3">
                        Assembled by
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->assembled_by }}
                    </div>
                    <div class="col-xs-3">
                        Market Value
                    </div>
                    <div class="col-xs-3">
                        :{{ number_format($collateral->market_value,2) }}
                    </div>

                    <div class="col-xs-3">
                        Registered Owner
                    </div>
                    <div class="col-xs-3">
                        :{{ $collateral->registered_owner }}
                    </div>
                    <div class="col-xs-3">
                        Loan Value
                    </div>
                    <div class="col-xs-3">
                        :{{ number_format($collateral->loan_value,2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                APPRAISAL:
            </div>
            <div class="col-xs-12 text-center">
                <strong>GENERAL CONDITIONS & OTHER REMARKS</strong>
            </div>

            <div class="col-xs-3">
                Paint Work
            </div>
            <div class="col-xs-9">
                {{ $collateral->paint_condition }} - {{ $collateral->paint_remarks }}
            </div>

            <div class="col-xs-3">
                Tire
            </div>
            <div class="col-xs-9">
                {{ $collateral->tire_condition }} - {{ $collateral->tire_remarks }}
            </div>

            <div class="col-xs-3">
                Body
            </div>
            <div class="col-xs-9">
                {{ $collateral->body_condition }} - {{ $collateral->body_remarks }}
            </div>

            <div class="col-xs-3">
                Chrome
            </div>
            <div class="col-xs-9">
                {{ $collateral->chrome_condition }} - {{ $collateral->chrome_remarks }}
            </div>

            <div class="col-xs-3">
                Upholstery
            </div>
            <div class="col-xs-9">
                {{ $collateral->upholstery_condition }} - {{ $collateral->upholstery_remarks }}
            </div>

            <div class="col-xs-3">
                Engine
            </div>
            <div class="col-xs-9">
                {{ $collateral->engine_condition }} - {{ $collateral->engine_remarks }}
            </div>

            <div class="col-xs-3">
                Transmission
            </div>
            <div class="col-xs-9">
                {{ $collateral->transmission_condition }} - {{ $collateral->transmission_remarks }}
            </div>

            <div class="col-xs-3">
                Rear Axle
            </div>
            <div class="col-xs-9">
                {{ $collateral->rear_axle_condition }} - {{ $collateral->rear_axle_remarks }}
            </div>

            <div class="col-xs-3">
                Clutch
            </div>
            <div class="col-xs-9">
                {{ $collateral->clutch_condition }} - {{ $collateral->clutch_remarks }}
            </div>

            <div class="col-xs-3">
                Steering
            </div>
            <div class="col-xs-9">
                {{ $collateral->steering_condition }} - {{ $collateral->steering_remarks }}
            </div>

            <div class="col-xs-3">
                Brakes
            </div>
            <div class="col-xs-9">
                {{ $collateral->brakes_condition }} - {{ $collateral->brakes_remarks }}
            </div>

            <div class="col-xs-3">
                Accessories
            </div>
            <div class="col-xs-9">
                {{ $collateral->accessories_condition }} - {{ $collateral->accessories_remarks }}
            </div>

            <div class="col-xs-3">
                Glass
            </div>
            <div class="col-xs-9">
                {{ $collateral->glass_condition }} - {{ $collateral->glass_remarks }}
            </div>

            <div class="col-xs-3">
                Panel Instrument
            </div>
            <div class="col-xs-9">
                {{ $collateral->panel_instru_condition }} - {{ $collateral->panel_instru_remarks }}
            </div>
        </div>

        <div class="row indent-top" style="border-top:1px solid #000;">
            <div class="col-xs-12">
                <p class="text-center">
                    << LEGEND >> 1 - excellent ; 2 - good ; 3 - fair ; 4 - poor
                </p>
            </div>
            <div class="col-xs-3">
                Route:
            </div>
            <div class="col-xs-9">
                {{ $collateral->route }}
            </div>

            <div class="col-xs-5 col-xs-offset-7">
                Appraised by: {{ $collateral->appraised_by }}
            </div>
            <div class="col-xs-5 col-xs-offset-7">
                Place by: {{ $collateral->place }}
            </div>
            <div class="col-xs-5 col-xs-offset-7">
                Date by: {{ $collateral->appraised_date }}
            </div>
        </div>
    </div>
@endsection