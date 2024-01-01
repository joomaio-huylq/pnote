<script>
    function loadAttachments() {
        var url = '<?php echo $this->url(); ?>';
        var id = $('#item_id').val();
        $.ajax({
            url: '<?php echo $this->link_attachment ?>/' + id,
            type: 'GET',
            success: function(result) {
                var list = '';
                if (Array.isArray(result.list)) {

                    result.list.forEach(function(item) {
                        list += `
                        <div class="card border shadow-none d-flex flex-column me-2 justify-content-center" style="width: auto;">
                            <a href="${url}/${item.path}" target="_blank" class="h-100 my-2 px-2 mx-auto" style="">
                                <img style="height: 120px; max-width: 100%;" src="${url}/${item.image}">
                            </a>
                            <div class="card-body d-flex">
                                <p class="card-text fw-bold m-0 me-2">${item.title}</p>
                                <a data-href="${url}/${item.path}" class="ms-1 me-3 copy_attachment_item fs-4"><i class="fa-regular fa-copy"></i></a>
                                <a data-id="${item.id}" class="ms-auto remove_attachment_item fs-4"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </div>
                        `
                    });
                    $("#list-attachments").html(list);
                    addEvent();
                }
            }
        });
    }

    function addEvent() {
        $('.remove_attachment_item').on('click', function() {
            var result = confirm("You are going to delete 1 attachment. Are you sure ?");
            if (!result) {
                return false;
            }
            var id = $(this).data('id');
            url = '<?php echo $this->link_attachment_remove ?>/' + id;
            var form = new FormData();
            form.append('_method', 'DELETE');
            $.ajax({
                url: url,
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function(result) {
                    if (result.status != 'done') {
                        var message = result.message ? result.message : 'Upload Failed';
                        alert(result.message);
                    } else {
                        alert('Delete successfully!');
                    }

                    loadAttachments();
                    addEvent();
                }
            });
        });

        $('.copy_attachment_item').on('click', function() {
            var href = $(this).data('href');
            copyToClipboard(href);
        });


    }

    $(document).ready(function() {
        addEvent();
        $('.btn-upload').on('click', function(e) {
            e.preventDefault();
            var check = $('#file')[0].files.length;
            if (!check) {
                alert('File Upload Empty');
                return false;
            }

            var form = new FormData($('#form_submit')[0]);
            form.append('_method', 'POST');
            var id = $('#item_id').val();
            $.ajax({
                url: '<?php echo $this->link_attachment ?>/' + id,
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function(result) {
                    if (result.status != 'done') {
                        var message = result.message ? result.message : 'Upload Failed';
                        alert(result.message);
                    } else {
                        alert('Upload successfully!');
                    }

                    loadAttachments();
                    $('#file').val(null);
                }
            });
        });

    });

    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }
</script>