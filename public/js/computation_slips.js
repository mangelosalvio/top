new Vue({
    el : "#app",

    ready : function(){

        var e = this;
        this.$http.get('/api/chart-of-accounts').then(function(response){
            this.chart_of_accounts = response.json();
        });

        this.$http.get('/api/loans/' + this.loan_id + "/other-additions").then(function(response){
            var other_additions = response.json();
            $.each(other_additions, function(i, other_addition){
                e.other_additions.push({
                    chart_of_account_id : other_addition.pivot.chart_of_account_id,
                    amount : other_addition.pivot.amount
                });
            });
        });

        this.$http.get('/api/loans/' + this.loan_id + "/other-deductions").then(function(response){
            var other_deductions = response.json();
            $.each(other_deductions, function(i, other_deduction){
                e.other_deductions.push({
                    chart_of_account_id : other_deduction.pivot.chart_of_account_id,
                    amount : other_deduction.pivot.amount
                });
            });
        });

    },

    computed : {
        total_rod_charges : function () {

            var total = numeral(this.mortgage_fees);
            total.add(this.deed_of_assignment_fees);
            total.add(this.legal_and_research_fees);

            return parseFloat(numeral(total.value()).format('0.00'));
        },

        total_lto_charges : function () {

            var total = numeral(this.transfer_fees);
            total.add(this.mortgage_and_assignment_fees);
            total.add(this.misc_lto_fees);

            return parseFloat(numeral(total.value()).format('0.00'));
        },

        total_doc_fees : function () {

            var total = numeral(this.doc_stamp);

            total.add(this.science_stamps);
            total.add(this.notarial_fees);
            total.add(this.misc_fees);

            total.add(this.total_rod_charges);
            total.add(this.total_lto_charges);

            return parseFloat(numeral(total.value()).format('0.00'));
        },

        net_proceeds : function () {
            var total_net_proceeds = numeral(this.amount) ;
            total_net_proceeds.subtract(this.total_doc_fees);
            total_net_proceeds.subtract(this.service_fees);
            total_net_proceeds.subtract(this.od_insurance_fees);

            $.each(this.other_additions, function(i, other_addition){
                total_net_proceeds.add(other_addition.amount);
            });

            $.each(this.other_deductions, function(i, other_deduction){
                total_net_proceeds.subtract(other_deduction.amount);
            });
            return parseFloat(numeral(total_net_proceeds.value()).format('0.00'));
        }


    },

    data : {
        other_addition : {  amount : null , chart_of_account_id : null },
        other_deduction : {  amount : null , chart_of_account_id : null },
        other_additions : [],
        other_deductions : [],
        chart_of_accounts : null
    },

    methods : {
        addAddition : function() {
            this.other_additions.push(Vue.util.extend({},this.other_addition));
            this.other_addition = {};
        },
        addDeduction : function() {
            this.other_deductions.push(Vue.util.extend({},this.other_deduction));
            this.other_deduction = {};
        },
        removeAddition : function(addition) {
            this.other_additions.$remove(addition);
        },
        removeDeduction : function(deduction) {
            this.other_deductions.$remove(deduction);
        }
    }
});