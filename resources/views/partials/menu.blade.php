<ul class="nav navbar-nav">
    @if( Auth::check() )

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CIR <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url("/customers") }}">Customers Mastefile</a></li>
                <li><a href="{{ url("/banks") }}">Banks Mastefile</a></li>

                <li><a href="{{ url("/collaterals") }}">Chattel Collaterals Masterfile</a></li>
                <li><a href="{{ url("/collaterals-rem") }}">REM Collaterals Masterfile</a></li>

                <li><a href="{{ url("/collateral-classes") }}">Collateral Classes</a></li>
                <!-- <li><a href="#">Something else here</a></li> -->
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Loans <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url("/loans") }}">Loans Masterfile</a></li>
                <li><a href="{{ url("/computation-slips") }}">Computation Slips</a></li>
                <!-- <li><a href="#">Something else here</a></li> -->
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Accounting <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url("/chart-of-accounts") }}">Chart of Accounts</a></li>
                <li><a href="{{ url("/general-ledgers") }}">General Ledgers</a></li>
                <li><a href="{{ url("/check-vouchers") }}">Check Vouchers</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cashier<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url("/collections") }}">Collections</a></li>

            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url("/trial-balance") }}">Trial Balance</a></li>
                <li><a href="{{ url("/income-statement") }}">Income Statement</a></li>
                <li><a href="{{ url("/balance-sheet") }}">Balance Sheet</a></li>
                <li><a href="{{ url("/journal-listings") }}">Journal Listings</a></li>
                <li><a href="{{ url("/general-ledger-activity-report") }}">General Ledger Activity Report</a></li>
                <li><a href="{{ url("/aging-of-accounts-receivables") }}">Aging of Accounts Receivables</a></li>
                <li><a href="{{ url("/availment-report") }}">Availment Report</a></li>
                <li><a href="{{ url("/collection-report") }}">Collection Report</a></li>
                <li><a href="{{ url("/daily-collection-report") }}">Daily Collection Report</a></li>
                <li><a href="{{ url("/cash-position-report") }}">Cash Position Report</a></li>
            </ul>
        </li>

        <li>
        <li><a href="{{ url("/settings") }}">Settings</a></li>
        </li>
    @endif
</ul>