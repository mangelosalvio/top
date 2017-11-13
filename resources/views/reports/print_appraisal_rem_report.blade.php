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
                UNLIFINANCE CORPORATION <br/>
                Corner Rosario-Amapola St. Bacolod City
            </div>

            <div class="col-xs-12 text-center">
                APPRAISAL REPORT
            </div>
        </div>

        <div class="row" style="margin-top:24px;">
            <div class="col-xs-2">
                Customer
            </div>
            <div class="col-xs-4">
                :{{ $collateral->customer->name }}
            </div>

            <div class="col-xs-2">
                Date Appraised
            </div>
            <div class="col-xs-4">
                :{{ $collateral->appraised_date }}
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12">
                LAND
            </div>
            <div class="col-xs-3">
                Title No.
            </div>
            <div class="col-xs-3">
                :{{ $collateral->title_no }}
            </div>
            <div class="col-xs-3">
                Lot Number
            </div>
            <div class="col-xs-3">
                :{{ $collateral->lot_no }}
            </div>

            <div class="col-xs-3">
                Area
            </div>
            <div class="col-xs-3">
                :{{ $collateral->area }}
            </div>
            <div class="col-xs-3">
                Date Issued
            </div>
            <div class="col-xs-3">
                :{{ $collateral->date_issued }}
            </div>

            <div class="col-xs-3">
                Reg. Owner:
            </div>
            <div class="col-xs-9">
                :{{ $collateral->registered_owner }}
            </div>

            <div class="col-xs-12">
                Description:
            </div>

            <div class="col-xs-12">
                {!! nl2br($collateral->operator) !!}
            </div>

            <div class="col-xs-12">
                &nbsp;
            </div>

            <div class="col-xs-12">
                Lot Location:
            </div>

            <div class="col-xs-12">
                {!! nl2br($collateral->lot_location) !!}
            </div>

            <div class="col-xs-12">
                &nbsp;
            </div>

            <div class="col-xs-12">
                BUILDING
            </div>

            <div class="col-xs-3">
                Location
            </div>
            <div class="col-xs-9">
                :{{ $collateral->location }}
            </div>

            <div class="col-xs-3">
                Description
            </div>
            <div class="col-xs-9">
                :{{ $collateral->description }}
            </div>

            <div class="col-xs-3">
                Type of Building
            </div>
            <div class="col-xs-9">
                :{{ $collateral->building_type }}
            </div>

            <div class="col-xs-3">
                No. of Story
            </div>
            <div class="col-xs-3">
                :{{ $collateral->no_of_story }}
            </div>

            <div class="col-xs-3">
                Flooring
            </div>
            <div class="col-xs-3">
                :{{ $collateral->flooring }}
            </div>

            <div class="col-xs-3">
                Frame Formation
            </div>
            <div class="col-xs-3">
                :{{ $collateral->frame_formation }}
            </div>

            <div class="col-xs-3">
                Door
            </div>
            <div class="col-xs-3">
                :{{ $collateral->door }}
            </div>

            <div class="col-xs-3">
                Walling
            </div>
            <div class="col-xs-3">
                :{{ $collateral->walling }}
            </div>

            <div class="col-xs-3">
                Windows
            </div>
            <div class="col-xs-3">
                :{{ $collateral->windows }}
            </div>

            <div class="col-xs-3">
                Partitions
            </div>
            <div class="col-xs-3">
                :{{ $collateral->partitions }}
            </div>

            <div class="col-xs-3">
                T & B
            </div>
            <div class="col-xs-3">
                :{{ $collateral->toilet_and_bath }}
            </div>

            <div class="col-xs-3">
                Roofing
            </div>
            <div class="col-xs-3">
                :{{ $collateral->roofing }}
            </div>

            <div class="col-xs-3">
                Floor Area
            </div>
            <div class="col-xs-3">
                :{{ $collateral->floor_area }}
            </div>

            <div class="col-xs-3">
                Beams & Trusses
            </div>
            <div class="col-xs-3">
                :{{ $collateral->beams_and_trusses }}
            </div>

            <div class="col-xs-3">
                Maintenance
            </div>
            <div class="col-xs-3">
                :{{ $collateral->maintenance }}
            </div>

            <div class="col-xs-3">
                Ceiling
            </div>
            <div class="col-xs-3">
                :{{ $collateral->ceiling }}
            </div>

            <div class="col-xs-3">
                Year Constructed
            </div>
            <div class="col-xs-3">
                :{{ $collateral->year_constructed }}
            </div>

            <div class="col-xs-12">
                &nbsp;
            </div>

            <div class="col-xs-12">
                OTHER IMPROVEMENTS
            </div>
            <div class="col-xs-12">
                {!! nl2br($collateral->other_improvements) !!}
            </div>

            <div class="col-xs-3">
                VALUATION OF PROPERTY
            </div>
            <div class="col-xs-offset-3 col-xs-3 text-right">
                <span class="b-bottom">Market Value</span>
            </div>
            <div class="col-xs-3 text-right">
                <span class="b-bottom">Appraised Value</span>
            </div>

            <div class="col-xs-3">
                Land
            </div>
            <div class="col-xs-offset-3 col-xs-3 text-right">
                {{ number_format($collateral->land_market_value,2) }}
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($collateral->land_appraised_value,2) }}
            </div>

            <div class="col-xs-3">
                Building
            </div>
            <div class="col-xs-offset-3 col-xs-3 text-right">
                {{ number_format($collateral->building_market_value,2) }}
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($collateral->building_appraised_value,2) }}
            </div>

            <div class="col-xs-3">
                Other Improvements
            </div>
            <div class="col-xs-offset-3 col-xs-3 text-right">
                {{ number_format($collateral->other_improvements_market_value,2) }}
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($collateral->other_improvements_appraised_value,2) }}
            </div>
            <div class="col-xs-6 text-right">
                TOTAL >>
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($collateral->total_market_value,2) }}
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($collateral->total_appraised_value,2) }}
            </div>

            <div class="row">
                <div class="col-xs-12">
                    &nbsp;
                </div>
            </div>

            <div class="row">
                <div class="col-xs-5">
                    BASIS OF VALUATION
                </div>
                <div class="col-xs-4 text-right">
                    Market Value/Sq. Meter
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    BIR Zonal Value
                </div>
                <div class="col-xs-4 text-right">
                    {{ number_format($collateral->bir_zonal_value,2) }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    Bankers/Appraisers Association Value
                </div>
                <div class="col-xs-4 text-right">
                    {{ number_format($collateral->appraisers_association_value,2) }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-5">
                    Market Values of Neighborhood
                </div>
                <div class="col-xs-4 text-right">
                    {{ number_format($collateral->market_value_of_neighborhood,2) }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-5">
                    Reproduction Cost of Building
                </div>
                <div class="col-xs-4 text-right">
                    {{ number_format($collateral->reproduction_cost_of_building,2) }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    Assessed Value
                </div>
                <div class="col-xs-4 text-right">
                    {{ number_format($collateral->assessed_value,2) }}
                </div>
            </div>

            <div class="row" style="margin-top:40px;">
                <div class="col-xs-3">
                    Prepared By:
                </div>

                <div class="col-xs-offset-6 col-xs-3">
                    Noted By:
                </div>
            </div>
            <div class="row" style="margin-top:30px;">
                <div class="col-xs-3">
                    JOVEN J. PIMENTEL
                </div>
                <div class="col-xs-offset-6 col-xs-3">
                    JOVEN J. PIMENTEL
                </div>
            </div>

        </div>
    </div>
@endsection