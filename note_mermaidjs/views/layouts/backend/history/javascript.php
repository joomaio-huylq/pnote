<script>
     $(document).ready(function(e) {
        $(".button-rollback").click(function(e) {
            e.preventDefault();
            var result = confirm("You are going to rollback. Are you sure ?");
            if(result)
            {
                $('#form_submit').submit();
                return true;
            }
            
            return false;
        });

        mermaid.parseError = function (err, hash) {
            $('.alert-mermaid').removeClass('d-none');
            $('.alert-mermaid').html(err.message);
        };

        $('#data').on('change', async function(){
            var code = $(this).val();
            if (code)
            {
                if (await mermaid.parse(code)) {
                    $('#mermaid').html(code);
                    mermaid.run({
                        nodes: document.querySelectorAll('#mermaid'),
                    });
                    $('.alert-mermaid').addClass('d-none');
                }
            }
        });

        $('#data').trigger("change");
    });
</script>