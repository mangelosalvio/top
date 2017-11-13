new Vue({
    el : "#app",

    ready : function(){

        var e = this;

        if ( this.general_ledger_id != null ) {
            showLoading();
            this.$http.get('/api/general-ledgers/' + this.general_ledger_id + "/general-ledger-details").then(function(response){
                hideLoading();
                var details = response.json();
                $.each(details, function(i, detail){
                    e.general_ledger_details.push({
                        chart_of_account_id : detail.id,
                        debit : detail.pivot.debit,
                        credit : detail.pivot.credit,
                        description : detail.pivot.description,
                        account_desc : detail.account_desc,
                        account_code : detail.account_code
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
            var total = numeral(0);

            $.each(this.general_ledger_details, function(i, general_ledger_detail){
                total.add(general_ledger_detail.credit);
            });

            return numeral(total.value()).format('0,0.00');
        }


    },

    data : {
        detail : {  debit : null, credit : null, chart_of_account_id : null, account_desc : null  },
        chart_of_accounts : null,
        general_ledger_details : [],
        general_ledger_id : null
    },

    methods : {
        addDetail : function() {
            this.general_ledger_details.push(Vue.util.extend({},this.detail));
            this.detail.debit = "";
            this.detail.credit = "";
            this.detail.account_desc = "";
            this.detail.account_code = "";
            $('#account_code').focus();
        },
        removeDetail : function(general_ledger_detail) {
            console.log(JSON.stringify(general_ledger_detail));
            var e = this;
            showLoading();
            this.$http.delete('/api/general-ledgers/' + e.general_ledger_id+ "/account/" + general_ledger_detail.chart_of_account_id + "/delete").then(function(response){
                hideLoading();
                console.log(response);
                e.general_ledger_details.$remove(general_ledger_detail);
            });
        },

        displayAccountDialog : function () {
            $('.modal').on('shown.bs.modal', function(){
                $('#account_desc').focus();
            });
            $('.modal').modal('show');

        },
        checkAccountCode : function(event){
            event.preventDefault();
            showLoading();
            this.$http.get('/api/chart-of-accounts/' + this.detail.account_code + '/account')
                .then(function(response){
                    hideLoading();
                    var data = response.json();

                    if ( !data.id ) {
                        alertify.confirm('ACCOUNT CODE "' + this.detail.account_code + '" NOT FOUND','Would you like to create an account?', this.displayAccountDialog, null);
                    } else {
                        this.detail.account_desc = data.account_desc;
                        this.detail.chart_of_account_id = data.id;
                        $('.modal').modal('hide');
                        $('#debit').focus();

                        console.log(data.account_desc);
                    }
                });
        },
        saveAccount : function (event) {
            showLoading();
            this.$http.post('/api/chart-of-accounts/' , this.detail)
                .then(function(response){
                    hideLoading();
                    var data = response.json();
                    this.detail.account_desc = data.account_desc;
                    this.detail.chart_of_account_id = data.id;
                    $('.modal').modal('hide');
                    $('#debit').focus();
                });
        }
    }
});