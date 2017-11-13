@extends('layouts.reports')
@section('content')
    <style>

        * {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size:1.5vw;
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
            <div class="col-xs-3">
                Name
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->name }}
            </div>

            <div class="col-xs-3">
                Address
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->current_address }}
            </div>

            <div class="col-xs-3">
                Application
            </div>
            <div class="col-xs-9">
                :{{ $loan->collateral->collateral_desc }}
            </div>

            <div class="col-xs-3">
                Add' units
            </div>
            <div class="col-xs-9">
                :{{ $loan->collateral->additional_collaterals }}
            </div>

            <div class="col-xs-3">
                Purpose
            </div>
            <div class="col-xs-9">
                :{{ $loan->purpose }}
            </div>

            <div class="col-xs-3">
                Amount
            </div>
            <div class="col-xs-3">
                :{{ number_format($loan->amount,2) }}
            </div>
            <div class="col-xs-3">
                Term:
            </div>
            <div class="col-xs-3">
                :{{ $loan->term }} months
            </div>

            <div class="col-xs-3">
                Date
            </div>
            <div class="col-xs-3">
                :{{ $loan->date }}
            </div>
            <div class="col-xs-6">
                {{ $loan->loan_no }}
            </div>
        </div>

        <div class="row indent-top" style="border-top:3px double #000; border-bottom:3px double #000; padding:10px 0px;">
            <div class="col-xs-12">
                GENERAL INFORMATION:
            </div>
            <div class="col-xs-12 indent-1">
                <p>
                    {!! nl2br($loan->customer->general_information) !!}
                </p>
            </div>

            <div class="col-xs-12">
                RESIDENCE:
            </div>
            <div class="col-xs-12 indent-1">
                <p>
                    {!! nl2br($loan->customer->residense) !!}
                </p>
            </div>

            <div class="col-xs-12">
                SOURCE OF INCOME:
            </div>
            <div class="col-xs-12">
                <p>
                    {!! nl2br($loan->customer->source_of_income) !!}
                </p>
            </div>

            <div class="col-xs-12">
                REAL AND PERSONAL PROPERTIES:
            </div>

            <div class="col-xs-3 indent-1">
                Real Estate
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->prop_real }}
            </div>

            <div class="col-xs-3 indent-1">
                Appliances
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->prop_appliance }}
            </div>

            <div class="col-xs-3 indent-1">
                Chattel
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->prop_chattel }}
            </div>

            <div class="col-xs-3 indent-1">
                Deposit
            </div>
            <div class="col-xs-9">
                :{{ $loan->customer->prop_deposit }}
            </div>

            <div class="col-xs-12">
                CREDIT DEALINGS:
            </div>
            <div class="col-xs-12">
                {{ $loan->customer->credit_dealings_1 }}
            </div>
            <div class="col-xs-12">
                {{ $loan->customer->credit_dealings_2 }}
            </div>
            <div class="col-xs-12">
                {{ $loan->customer->credit_dealings_3 }}
            </div>
            <div class="col-xs-12">
                {{ $loan->customer->credit_dealings_4 }}
            </div>

            <div class="col-xs-12">
                BACREA & COURT FILES - {{ $loan->customer->ica_court_files }}
            </div>

            <div class="col-xs-5">
                Address/data Confirmed by:
            </div>

            <div class="col-xs-5">
                NOTED BY:
            </div>

            <div class="col-xs-12" style="margin-top: 60px;">
                <div class="row">
                    <div class="col-xs-5">
                        {{ $loan->credit_investigator}}
                    </div>

                    <div class="col-xs-5">
                        MANAGER
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection