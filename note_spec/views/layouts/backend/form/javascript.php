<script>
    var ignore = <?php echo false ? json_encode($this->data['ignore']) : "[]" ;?>;
    var removes= [];
    var active_item = 0;
    var first = true;
    var checkSave = false;
    function removeItem(id)
    {
        removes.push(id);
        var index = ignore.indexOf(id);
        if (index > -1) {
            ignore.splice(index, 1);
        }
        var parent = $('#item_' + id).data('parent');
        var position = 0;
        $('#item_' + id).remove();

        $('.item-tree[data-parent="'+ parent +'"]').each(function(index){
            position++;
            $(this).attr('data-position', position);
            $(this).data('position', position);
        })
        $('.item-tree').each(function (index){
            if ($(this).data('parent') == id)
            {
                removeItem($(this).data('id'));
            }
        });

        var form = new FormData();
        form.append('_method', 'DELETE');
        var link = $('#form_note').attr('action') + '/' + id;
        $.ajax({
            url: link,
            type: 'POST',
            processData: false,
            contentType: false,
            data: form,
            success: function(result) {
                if (result.status == 'success') {
                } else {
                    alert(result.message);
                }
            }
        });
        loadPosition();
    }

    function loadPosition()
    {
        $('.item-tree').each(function(index){
            var id = $(this).data('id');
            if(id != 0)
            {
                moveChild(id);
            }
            var parent = $(this).data('parent');
            var position = 0;
            $('.item-tree[data-parent="'+ parent +'"]').each(function(index){
                position++;
                $(this).attr('data-position', position);
                $(this).data('position', position);
            })

            var index = '';
            if (parent == 0)
            {
                index += $(this).data('position');
                $(this).data('index', index);
            }
            else
            {
                var index_parent = $('#item_'+ parent).data('index');
                position = $(this).data('position');
                index += index_parent + '.' + position;
                $(this).data('index', index);
            }

            if (id != 0)
            {
                var title = $('#item_'+ id).data("text");
                $('#item_'+ id + ' .title').html('|&mdash;' + index + ' ' + title);
            }

            if ($('.item-tree[data-parent="'+ id +'"]').length)
            {
                $(this).addClass('childs');
            }
            else{
                $(this).removeClass('childs');
            }

            position = $(this).data('position');
            var lastPosition = $('.item-tree[data-parent="' + parent + '"]').last().data('position');
            
            if (position == 1)
            {
                $('#item_' + id + ' .up-note').addClass('d-none');
            }
            else{
                $('#item_' + id + ' .up-note').removeClass('d-none');
            }

            if (position == lastPosition)
            {
                $('#item_' + id + ' .down-note').addClass('d-none');
            }
            else{
                $('#item_' + id + ' .down-note').removeClass('d-none');
            }
        });

        if (!first)
        {
            savePosition();
        }
        else
        {
            first = false;
        }
    }
    
    function savePosition()
    {
        var structure = getStructure();
        var formData = new FormData();
        formData.append('structure', JSON.stringify(structure));
        formData.append('removes', JSON.stringify(removes));
        link = '<?php echo $this->link_update_position; ?>'
        $.ajax({
            url: link,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(result) {
                if (result.status != 'success') {
                    alert(result.message);
                }
                loadDocument();
            }
        });
    }

    function moveChild(id)
    {
        var next = $('#item_' +id );
        $('.item-tree[data-parent="'+id+'"]').each(function(index){
            next.after($(this));
            next = $(this);
            moveChild($(this).data('id'));
        })
    }

    function toogleChildItem(id, toogle)
    {
        if (toogle)
        {
            $('#item_'+ id).removeClass('close');
            $('.item-tree[data-parent="'+ id +'"').removeClass('d-none');
        }
        else{
            $('#item_'+ id).addClass('close');
            $('.item-tree[data-parent="'+ id +'"').addClass('d-none');
        }

        $('.item-tree[data-parent="'+ id +'"').each(function(index){
            toogleChildItem($(this).data('id'), toogle);
        });
    }
    function eventActiveItem()
    {
        $('.open-note-form').off('click').on('click', function(){
            var id = $(this).data('id');
            var note_id = $(this).data('note-id');
            note_id = note_id ? note_id : id;
            var index = '';
            if (id)
            {
                index = $('#item_' + id).data('index');
                $('#index_note').val(index);
                $('#note_ajax_load').attr('src', '<?php echo $this->link_detail_note; ?>/' + note_id);
                $('#form_edit').val(1);
                $('#specNoteLabel .index-text').text(index + ')');
                $('#specNoteLabel #title_note').val($('#item_' + id).data('text'));
            }
            else
            {
                var parent = $(this).parents('.item-tree');
                index = getIndex(parent.data('id'));
                $('#index_note').val(index);
                $('#form_edit').val(null);
            }
        })

        $('.item-tree').off('click').on('click', function(e){
            e.preventDefault();
            $('.item-tree').removeClass('active');
            $(this).addClass('active');
            active_item = $(this).data('id');

            var detail_link = '<?php echo $this->link_request .'/' ?>' + active_item;
            var node_detail = '<?php echo $this->link_note ?>/' + active_item;

            $('#link_node').text($(this).data('text'));
            $('#link_node').attr('href', node_detail);
            $('#body_related_request').html('');
            if (active_item == '0')
            {
                $('#link_node').text('');
                return true;
            }
        });

        $('.remove-note').off('click').on('click', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (!result) {
                return false;
            }
            if (active_item == id)
            {
                active_item == 0;
            }
            
            removeItem(id);
        });

        $('.up-note').off('click').on('click', function(){
            var id = $(this).data('id');
            var position = $('#item_'+ id).data('position');
            var parent = $('#item_'+ id).data('parent');

            if (position > 1)
            {
                var move_id = $('.item-tree[data-parent="'+ parent +'"][data-position="'+ (position - 1) +'"]').data('id');
                $('#item_' + move_id ).before($('#item_' + id));
                $('#item_' + move_id ).data('position', position);
                $('#item_' + move_id ).attr('data-position', position);
                $('#item_' + id ).attr('data-position', position - 1);
                $('#item_' + id).data('position', position - 1);
            }
            
            loadPosition();
        })
        $('.item-tree').off('dblclick').on('dblclick', function(){
            var id = $(this).data('id');
            toogleChildItem(id, $(this).hasClass('close'));
            
        })
        
        $('.down-note').off('click').on('click', function(){
            var id = $(this).data('id');
            var position = $('#item_'+ id).data('position');
            var parent = $('#item_'+ id).data('parent');
            var lastPosition = $('.item-tree[data-parent="' + parent + '"]').last().data('position');
            if (position != lastPosition)
            {
                var move_id = $('.item-tree[data-parent="'+ parent +'"][data-position="'+ (position +1) +'"]').data('id');
                $('#item_' + move_id ).after($('#item_' + id));
                $('#item_' + move_id ).data('position', position);
                $('#item_' + move_id ).attr('data-position', position);
                $('#item_' + id ).attr('data-position', position +1);
                $('#item_' + id).data('position', position + 1);
            }
            
            loadPosition();
        })
    }
    $(document).ready(function(e) {

        $('#popupNoteType').on('show.bs.modal', function(){
            $('#note_select_id').val(null).trigger('change');
            $('#note_type').val(null);
        });
        $("#note_select_id").select2({
            matcher: matchCustom,
            placeholder: 'Select Notes',
            minimumInputLength: 1,
            dropdownParent : "#popupNoteType",
            ajax: {
                url: '<?php echo $this->link_note_search ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    let list = data.data;
                    if (Array.isArray(list) && list.length > 0) {
                        list.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title
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

        $('#popupNote').on('hide.bs.modal', function(){
            $('#note_ajax_load').attr('src', '');
        })

        $('.select-new-note').on('click', function(){
            select = $('#note_select_id').select2('data');
            if(!select.length)
            {
                alert('Please select note!');
                return false;
            }

            $('.select-new-note').attr('disabled', 'disabled');
            var form = new FormData();
            form.append('note_id', select[0].id);
            $.ajax({
                url: '<?php echo $this->link_note_alias; ?>',
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function(result) {
                    if(result.status == 'success')
                    {
                        var note = {
                            text : select[0].text,
                            id : result.note_id,
                            note_id : select[0].id
                        }
                        createNote(note);
                        $('#popupNoteType').modal('hide');
                    }
                    else
                    {
                        alert(result.message);
                    }

                    $('.select-new-note').removeAttr('disabled');
                }
            });
            

        });
        $('.select-note-type').on('click', function(e){
            var key = $("#note_type").val();
            if (!key)
            {
                alert('Please select note type!');
                return false;
            }
            var form = '<?php echo $this->link_form_note .'/'?>' + key;
            $('#note_ajax_load').attr('src', form);
            var index = $('#index_note').val();
            $('#specNoteLabel .index-text').text(index + ')');
            $('#specNoteLabel #title_note').val('');
            var id = $(this).data('id');
            $('#popupNoteType').modal('hide');
            $('#popupNote').modal('show');
        });

        $("#note_ajax_load").on('load', function()
        {
            var height = $("#note_ajax_load").contents().find('#form_submit').height();
            if (height < 200) {
                $("#note_ajax_load").height(Math.max(height + 450));
            } else {
                $("#note_ajax_load").height(Math.max(height + 50));
            }
            if(checkSave)
            {
                var message = $("#note_ajax_load").contents().find(".message-body.alert-success .toast-body").text();
                if (message.indexOf('successfully') !== -1 || message.indexOf('Successfully') !== -1)
                {
                    var findId = $("#note_ajax_load").contents().find("#form_submit").attr('action');
                    var path = findId.split('/');
                    var note = {
                        text : $("#note_ajax_load").contents().find("#title").val(),
                        id : path ? path[path.length - 1] : '',
                    };
                    $('#popupNote').modal('hide');
                    if ($("#form_edit").val())
                    {

                        var level = $('.item-tree.active').data('level');
                        var tab = '&nbsp; &nbsp; &nbsp; &nbsp;';
                        var levelTab = tab.repeat(level-1);
                        $('.item-tree.active').data('text', note.text);
                        loadPosition();
                    }
                    else
                    {
                        createNote(note);
                        $("#form_edit").val(1);
                    }
                    var index = $('#index_note').val();
                    $('#specNoteLabel .index-text').text(index + ')');
                    $('#specNoteLabel #title_note').text(note.text);
                }
                checkSave = false;
            }
        });

        $('#spec_save_note').on('click', function()
        {
            $("#note_ajax_load").contents().find("#title").val($('#title_note').val());
            $("#note_ajax_load").contents().find(".btn_apply").trigger('click');
            checkSave = true;
        })

        eventActiveItem();
        loadPosition();
        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val()) {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            var structure = [];
            $('.item-tree').each(function(index){
                var level = $(this).data('level');
                var position = $(this).data('position');
                var parent = $(this).data('parent');
                var id = $(this).data('id');
                structure.push({
                    id : id,
                    position : position,
                    parent : parent,
                    level : level,
                });
                $('#structure').val(JSON.stringify(structure));
                $('#removes').val(JSON.stringify(removes));
            });
            $('#form_submit').submit();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val()) {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            var structure = [];
            $('.item-tree').each(function(index){
                var level = $(this).data('level');
                var position = $(this).data('position');
                var parent = $(this).data('parent');
                var id = $(this).data('id');
                structure.push({
                    id : id,
                    position : position,
                    parent : parent,
                    level : level,
                });
                $('#structure').val(JSON.stringify(structure));
                $('#removes').val(JSON.stringify(removes));
            });
            $('#form_submit').submit();
        });

        function createNote(item)
        {
            var level = $('#item_' + active_item).data('level');
            ignore.push(item.id);
            level++;
            var lastPosition = 0;
            var lastItem = active_item;
            $('.item-tree').each(function(index)
            {
                if ($(this).data('level') == level && $(this).data('parent') == active_item)
                {
                    lastPosition = $(this).data('position');
                    lastItem = $(this).data('id');
                }
            });
            lastPosition++;
            item.note_id = item.note_id ? item.note_id : 0;
            var tab = '&nbsp; &nbsp; &nbsp; &nbsp;';
            var levelTab = tab.repeat(level-1);
            $('#item_' + lastItem).after(`
                <tr data-note-id="${item.note_id}" data-text="${item.text}" id="item_${item.id}" data-level="${level}" data-position="${lastPosition}" data-parent="${active_item}" class="item-tree" data-id="${item.id}">
                    <td>${levelTab}<span class="title">|&mdash;${item.text}</span></td>
                    <td> 
                        <div class="d-flex justify-content-end">
                            <a class="open-note-form me-3" data-note-id="${item.note_id}" data-id="${item.id}" type="button" data-bs-toggle="modal" data-bs-target="#popupNote"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a class="up-note me-2 d-none" data-id="${item.id}"><i class="fa-solid fa-arrow-up"></i></a>
                            <a class="down-note  me-2 d-none" data-id="${item.id}"><i class="fa-solid fa-arrow-down"></i></a>
                            <a class="remove-note" data-note-id="${item.note_id}" data-id="${item.id}"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                    <td width="50px">
                        <button type="button" data-id="0" class="btn btn-outline-success open-note-form" type="button" data-bs-toggle="modal" data-bs-target="#popupNoteType"><i class="fa-solid fa-plus"></i></button>
                    </td>
                </tr>
            `);
            eventActiveItem();
            loadPosition();
        }

        $('#document').on('load', function(){
            $('.loading-document').addClass('d-none');
        })
    });

    function getIndex(id)
    {
        var level = 0;
        var index = '';
        if (id != 0)
        {
            var level = parseInt($('#item_' + id).data('level'));
            var index = $('#item_' + id).data('index');
        }

        level ++;
        var count = $(`.item-tree[data-parent=${id}][data-level=${level}]`).length;
        count ++;
        index = index ? index + '.' + count : count;
        return index;
    }
    function loadNote(note)
    {
        $('#note_title').val(note.title);
        $('#note_data').val(note.data);
        tinymce.get("note_data").setContent(note.data);
        $('#note_id').val(note.id);
    }

    function getStructure()
    {
        var structure = [];
        $('.item-tree').each(function(index){
            var level = $(this).data('level');
            var position = $(this).data('position');
            var parent = $(this).data('parent');
            var id = $(this).data('id');
            structure.push({
                id : id,
                position : position,
                parent : parent,
                level : level,
            });
            $('#structure').val(JSON.stringify(structure));
            $('#removes').val(JSON.stringify(removes));
        });

        return structure;
    }

    function loadDocument()
    {
        $('.loading-document').removeClass('d-none');
        $('#document').attr("src", $('#document').attr("src"));
    }
</script>