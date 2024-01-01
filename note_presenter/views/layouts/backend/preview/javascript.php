<script>
     $(document).ready(function(e) {
        var objects = canvas.getObjects();
        for (var i = 0; i < objects.length; i++) {
            objects[i].selectable = false;
        }
        $('#fabric_tool_menu').remove();
        $('#fabric_slide_menu .add-button').remove();
        $('#fabric_slide_menu .remove-button').remove();
    });
</script>