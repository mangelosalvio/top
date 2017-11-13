new Vue({
    el : "#app",

    ready : function(){
        if ( typeof collateral_id !== 'undefined') {
            this.collateral_id = collateral_id;
        }
        this.getCollaterals();
    },

    data : {
        customer_id : null,
        collateral_id : null,
        collaterals : [],
        customer_code : null
    },

    methods : {
        getCollaterals : function() {

            console.log(this.customer_id);

            if ( !this.customer_id ) {
                this.customer_id = 0;
            }

            this.$http.get('/api/customers/' + this.customer_id + '/collaterals').then(function($response){
                console.log($response.json());
                this.$set('collaterals',$response.json());
            });

        }
    }
});