<script>
     $(document).ready(function(e) {
        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            $('#form_submit').submit();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            $('#form_submit').submit();
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
                    $('#mermaid').removeAttr('data-processed');
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