<script>
     $(document).ready(function(e) {
        mermaid.parseError = function (err, hash) {
            $('.alert-mermaid').removeClass('d-none');
            $('.alert-mermaid').html(err.message);
        };

        var code = `<?php echo $this->data['data'] ?>`;
        async function loadDiagram()
        {
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
        }
        loadDiagram();
    });
</script>