new Vue({
    el : "#app",

    ready : function(){

        var e = this;
        this.$http.get('/api/chart-of-accounts').then(function(response){
            this.chart_of_accounts = response.json();
        });

        console.log();


        if(this.loan.id) {
            this.$http.get('/api/loans/'
            + this.loan.id).then(function (response) {
                var data = response.json();
                this.$set('loan.customer.name', data.customer.name);
                this.$set('loan.first_due_term', data.first_due_term);
                this.$set('loan.cash_out', numeral(data.cash_out).format('0,0.00'));
                this.$set('loan.interest_amount', numeral(data.interest_amount).format('0,0.00'));
                this.$set('loan.rebate_amount', numeral(data.rebate_amount).format('0,0.00'));
                this.$set('loan.pn_amount', numeral(data.pn_amount).format('0,0.00'));

            });
        }

        this.getLessAccounts();
        this.getAdditionalAccounts();

        if ( this.general_ledger_id != null ) {
            this.$http.get('/api/general-ledgers/' + this.general_ledger_id + "/general-ledger-details").then(function(response){
                var details = response.json();

                $.each(details, function(i, detail){
                    e.general_ledger_details.push({
                        chart_of_account_id : detail.id,
                        debit : detail.pivot.debit,
                        credit : detail.pivot.credit,
                        description : detail.pivot.description
                    });
                });
            });
        }
    },

    computed : {
        total_debit : function () {
            var total = numeral(0) ;

            $.each(this.general_ledger_details, function(i, general_ledger_detail){
                total.add(general_ledger_detail.debit);
            });

            return numeral(total.value()).format('0,0.00');
        },

        total_credit : function () {
            var total = numeral(0) ;

            $.each(this.general_ledger_details, function(i, general_ledger_detail){
                total.add(general_ledger_detail.credit);
            });

            return numeral(total.value()).format('0,0.00');
        },

        total_amount_due : function() {
            var total = numeral(0) ;

            if ( this.principal_amount ) {
                total.add(this.principal_amount);
            }

            if ( this.rff_credit ) {
                total.subtract(this.rff_credit);
            }

            if ( (this.uii_debit - this.interest_income_credit) > 0 ) {
                total.subtract(this.uii_debit - this.interest_income_credit);
            }

            $.each(this.less_other_accounts, function(i, account){
                total.subtract(account.amount);
            });

            $.each(this.add_other_accounts, function(i, account){
                total.add(account.amount);
            });

            total.add(this.net_penalty_due);

            return numeral(total.value()).format('0.00');
        },

        penalty_disc_amount : function() {

            if ( this.penalty_disc_rate == null ) {
                this.penalty_disc_rate = 0;
            }

            if ( this.penalty_rate
                && this.total_penalty
                && this.penalty_disc_rate
            ) {
                return numeral(parseFloat(this.total_penalty) * parseFloat(this.penalty_disc_rate) / 100).format('0.00');
            } else {
                return 0.00;
            }

            if ( this.total_penalty ) {
                return this.total_penalty;
            }

            return 0.00;

        },

        net_penalty_due : function () {

            if ( this.total_penalty ) {
                return numeral(parseFloat(this.total_penalty) - parseFloat(this.penalty_disc_amount)).format('0.00');
            }

            return 0;
        },

        total_payment_amount : function () {
            this.cash_amount = (this.cash_amount) ? this.cash_amount : 0;
            this.check_amount = (this.check_amount) ? this.check_amount : 0;

            return numeral(parseFloat(this.cash_amount) + parseFloat(this.check_amount)).format('0.00');
        }

    },

    data : {
        detail : {  debit : null, credit : null, chart_of_account_id : null, description : null },
        form_less_other_account : {},
        form_add_other_account : {},
        less_other_accounts : [],
        add_other_accounts : [],
        chart_of_accounts : null,
        collection_id : null,
        account_code : null,
        loan : { customer: {} },
        total_penalty : null,
        penalty_rate : null,
        is_penalty_computed : null,
        last_transaction_date : null,
        penalty_as_of_date : null,
        days_allowance : null,
        penalty_disc_rate : null,
        principal_amount : null,
        rff_debit : null,
        rff_credit : null,
        uii_debit : null,
        interest_income_credit : null,
        total_payment_amount : null,
        cash_amount : null,
        check_amount : null
    },

    methods : {
        addLessOtherAccount : function() {
            this.less_other_accounts.push(Vue.util.extend({},this.form_less_other_account));
            this.form_less_other_account.chart_of_account_id = null;
            this.form_less_other_account.amount = null;

            console.log(this.less_other_accounts[0]);
        },
        removeLessOtherAccount : function(less_other_account) {
            var e = this;

            if (e.collection_id == null ) {
                e.less_other_accounts.$remove(less_other_account);
            } else {
                this.$http.delete('/api/collections/' + e.collection_id+ "/less-account/" + less_other_account.chart_of_account_id + "/delete").then(function(response){
                    console.log(response);
                    e.less_other_accounts.$remove(less_other_account);
                });
            }
        },

        addOtherAccount : function() {
            this.add_other_accounts.push(Vue.util.extend({},this.form_add_other_account));
            this.form_add_other_account.chart_of_account_id = null;
            this.form_add_other_account.amount = null;

        },
        removeAddOtherAccount : function(add_other_account) {
            var e = this;

            if (e.collection_id == null ) {
                e.add_other_accounts.$remove(add_other_account);
            } else {
                this.$http.delete('/api/collections/' + e.collection_id+ "/additional-account/" + add_other_account.chart_of_account_id + "/delete").then(function(response){
                    e.add_other_accounts.$remove(add_other_account);
                });
            }

        },

        getLoanFromAccountCode : function (e) {
            e.preventDefault();

            this.$http.get('/api/chart-of-accounts/'
            + this.account_code + '/account-code').then(function(response){

                if ( response.data != "" ) {
                    var data = response.json();
                    this.$set('loan',data);
                    this.loan.cash_out = numeral(this.loan.cash_out).format('0,0.00');
                    this.loan.interest_amount = numeral(this.loan.interest_amount).format('0,0.00');
                    this.loan.rebate_amount = numeral(this.loan.rebate_amount).format('0,0.00');
                    this.loan.pn_amount = numeral(this.loan.pn_amount).format('0,0.00');
                } else {
                    alert("Account Code not found");
                }
            });

        },

        getLoanComputation : function (e) {
            e.preventDefault();
            var form_data = {
                current_balance : this.loan.current_balance,
                principal_amount : this.principal_amount
            };

            this.$http.post('/api/collections/' + this.loan.id + '/loan-computation', form_data).then(function(response){
                var data = response.json();

                this.$set('rff_debit',data.rebate_amount);
                this.$set('uii_debit',data.interest_amount);
                this.$set('rff_credit',data.rebate_amount);
                this.$set('interest_income_credit',data.interest_amount);
            });
        },

        getLessAccounts : function () {

            if ( this.collection_id ) {
                this.$http.get("/api/collections/" + this.collection_id + "/less-accounts").then(function(response){
                    this.$set('less_other_accounts', response.json());
                });
            }

        },

        getAdditionalAccounts : function() {
            if ( this.collection_id ) {
                this.$http.get("/api/collections/" + this.collection_id + "/additional-accounts").then(function(response){
                    this.$set('add_other_accounts', response.json());
                });
            }

        },

        applyPenalty : function() {
            if ( !this.is_penalty_computed ) {
                this.last_transaction_date = null;
                this.penalty_rate = null;
                this.penalty_as_of_date = null;
                this.days_allowance = null;
                this.total_penalty = null;
                this.penalty_disc_rate = null;
            }
            console.log(this.is_penalty_computed);
        }

    }
});