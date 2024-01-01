<script>
    $(document).ready(function(e) {
        $("#note_select_id").select2({
            matcher: matchCustom,
            placeholder: 'Select Note',
            minimumInputLength: 1,
            dropdownParent : "#popupFormCol",
            ajax: {
                url: '<?php echo $this->link_note_search ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    var ignore = [];
                    $('.product-item input[name=id_product]').each(function(){
                        var id = $(this).val();
                        if (id)
                        {
                            ignore.push(id);
                        }
                    });
                    return {
                        search: params.term,
                        ignore: ignore.join(','),
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    let list = data.data;
                    if (Array.isArray(list) && list.length > 0) {
                        list.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title,
                                link: item.link,
                                features: item.features
                            })
                        })
                    }

                    return {
                        results: items,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
        });

        $('#add-note-button').on('click', function(){
            select = $('#note_select_id').select2('data');
            var index_product = $('#index_product').val();
            if(!select.length)
            {
                alert('Please select note!');
                return false;
            }
            var note = {
                title : select[0].text,
                id : select[0].id,
                link : select[0].link,
                features : select[0].features,
            }

            if(index_product > 0)
            {
                updateCol(note, index_product);
            }
            else
            {
                addCol(note);
            }

        });

        function matchCustom(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

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
            var tableData = {
                "colHeaders" : table.getColHeader(),
                "data" : table.getData()
            }
            $('#structure').val(JSON.stringify(tableData));
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
            var tableData = {
                "colHeaders" : table.getColHeader(),
                "data" : table.getData()
            }
            $('#structure').val(JSON.stringify(tableData));
            $('#form_submit').submit();
        });

        var data = $('#table_data').val() ? JSON.parse($('#table_data').val()) : '';

        const container = document.querySelector('#note-table');
        var myHeaders = data ? data['colHeaders'] : ['', '', '', ''];
        var tableData = data ? data['data'] : [['', '', '', ''], ['', '', '', ''], ['', '', '', ''], ['', '', '', ''], ['', '', '', '']];

        const table = new Handsontable(container, {
            contextMenu: true,
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

        table.addHook('afterCreateCol', function(col, amount) {
            setTimeout(() => {
                console.log(this.getColHeader());
                let colHeader = this.getColHeader();

                this.updateSettings({
                    colHeaders: function(index) {
                        return index === col ? '' : colHeader[index];
                    }
                });
            });
        });

        table.addHook('beforeOnCellMouseDown', function(e, coords, th) {
            // open context menu when right click on header
            if(e.button === 2 && coords.row === -1){
                this.selectColumns(coords.col);
                this.selectRows(coords.row);
                this.menu.open();
            }

            // not allow remove row when exist only 1 row
            if(e.button === 2 && this.countRows() === 1) {
                this.updateSettings({
                    allowRemoveRow: false
                });
            }
            
            // not allow remove col when exist only 1 col
            if(e.button === 2 && this.countCols() === 1) {
                this.updateSettings({
                    allowRemoveColumn: false
                });
            }
        });

        table.addHook('afterOnCellMouseDown', function(e, coords, th) {
            if (coords.row === -1 && coords.col > -1) {
                let input = document.createElement('input'),
                    rect = th.getBoundingClientRect();

                input.value = th.querySelector('.colHeader').innerText;
                input.setAttribute('type', 'text');
                input.className = 'col-header-input';
                input.style.cssText = '' +
                    'position:absolute;' +
                    'left:' + rect.left + 'px;' +
                    'top:' + rect.top + 'px;' +
                    'width:' + rect.width + 'px;' +
                    'height:' + rect.height + 'px;' +
                    'z-index:1060;';
                document.body.appendChild(input);

                setTimeout(() => {
                    input.select();
                    let events = ['change', 'blur'],
                        headers = this.getColHeader();
                        
                    events.forEach(e => {
                        input.addEventListener(e, () => {
                            headers[coords.col] = input.value;
                            this.updateSettings({
                                colHeaders: headers
                            });

                            setTimeout(() => {
                                if (input.parentNode)
                                    input.parentNode.removeChild(input)
                                });
                        });
                    });
                });
            }
        });
    });

</script>