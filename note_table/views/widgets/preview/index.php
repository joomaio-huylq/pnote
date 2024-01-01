<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <input id="table_data" type="hidden" name="table_data" value='<?php echo json_encode($this->data['data']); ?>'>
            <div id="preview-table"></div>
        </div>
    </div>
</div>
<?php 
    $this->theme->add($this->url . 'assets/css/handsontable.full.min.css', '', 'handsontable-css');
    $this->theme->add($this->url . 'assets/js/handsontable.full.min.js', '', 'bootstrap-handsontable');
?>
<script>
    $(document).ready(function(e) {
        var data = JSON.parse(JSON.parse($('#table_data').val()));

        const container = document.querySelector('#preview-table');

        let myHeaders = data ? data['colHeaders'] : [''];
        let tableData = data ? data['data'] : [['']];

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
