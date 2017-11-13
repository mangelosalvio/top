<script>
    $("input[type='button'][value='Delete']").click(function(){
        $("input[name='_method']").val('DELETE');
        $('form').submit();
    });
</script>