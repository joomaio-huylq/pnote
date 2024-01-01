<script>
    $(document).ready(function(e) {
        var data =  JSON.parse($('#table_data').val());

        const container = document.querySelector('#preview-table');
        var myHeaders = data ? data['colHeaders'] : [''];
        var tableData = data ? data['data'] : [['']];

        const hot = new Handsontable(container, {
            readOnly: true,
            contextMenu: false,
            disableVisualSelection: true,
            manualColumnResize: true,
            manualRowResize: true,
            colHeaders: myHeaders,
            rowHeaders: true,
            data: tableData,
            colWidths: 200,
            height: 'auto',
            cells: function(row, col, prop) {
                var cellProp = {};
                cellProp.renderer = htmlRenderer;
                return cellProp
            },
            licenseKey: 'non-commercial-and-evaluation'
        });

        function htmlRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.HtmlRenderer.apply(this, arguments);
            td.innerHTML = value;
        }
    });
</script>