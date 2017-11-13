new Vue({
    el : "#app",

    ready : function(){

        var e = this;
        this.$http.get('/api/chart-of-accounts').then(function(response){
            this.chart_of_accounts = response.json();
        });

        this.$http.get('/api/check-voucher/' + this.check_voucher_id + "/check-voucher-details").then(function(response){
            var details = response.json();
            $.each(details, function(i, detail){

                console.log(detail);
                e.check_voucher_details.push({
                    id : detail.id,
                    chart_of_account_id : detail.chart_of_account_id,
                    debit : detail.debit,
                    credit : detail.credit,
                    //description : detail.pivot.description,
                    account_desc : detail.account.account_desc,
                    account_code : detail.account.account_code
                });
            });
        });



    },

    computed : {
        amount : function () {
            var total = numeral(0) ;

            $.each(this.check_voucher_details, function(i, check_voucher_detail){
                total.add(check_voucher_detail.debit);
                total.subtract(check_voucher_detail.credit);
            });

            return parseFloat(numeral(total.value()).format('0.00'));
        }


    },

    data : {
        detail : {  debit : null, credit : null, chart_of_account_id : null, account_desc : null },
        chart_of_accounts : null,
        check_voucher_details : [],
        check_voucher_id : null
    },


    methods : {
        addDetail : function() {
            this.check_voucher_details.push(Vue.util.extend({},this.detail));
            this.other_addition = {};
            this.detail.debit = "";
            this.detail.credit = "";
        },
        removeDetail : function(check_voucher_detail) {
            console.log(check_voucher_detail.id);
            var e = this;
            this.$http.delete('/api/check-voucher-details/' + check_voucher_detail.id+ "/delete").then(function(response){
                e.check_voucher_details.$remove(check_voucher_detail);
            });

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
    }
});