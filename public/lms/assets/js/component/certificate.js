$(function() {
    $(".dragable-element").draggable({
        containment: "#certificateImg"
    });

    $(document).on('keyup change', '.dragable-element-control', function() {

        let elementArea = $(this).closest('.element-area');
        let ItemName = elementArea.data('item');
        let styles = "";
        let title = $(elementArea).find('.dragable-element-title').val();
        let fontSize = $(elementArea).find('.dragable-element-size').val();
        let fontColor = $(elementArea).find('.dragable-element-color').val();

        let dragableItem = $(".dragable-element[data-name=\"".concat(ItemName, "\"]"));
        if (title) {
            dragableItem.html(title);
        }
        let existingLeft = dragableItem.css('left');
        let existingTop = dragableItem.css('top');

        styles += "position: absolute; ";
        if (fontSize) {
            styles += "font-size: ".concat(fontSize, "px; ");
        }
        if (fontColor) {
            styles += "color: ".concat(fontColor, "; ");
        }
        if (existingLeft && existingLeft !== 'auto') {
            styles += "left: ".concat(existingLeft, "; ");
        }
        if (existingTop && existingTop !== 'auto') {
            styles += "top: ".concat(existingTop, "; ");
        }
        dragableItem.css('cssText', styles);
    });

    $(document).on('submit', '.add-cerficate', function(e) {
        e.preventDefault();
        let form = $(this);
        let action = form.attr('action');

        let container = $("#certificateImg");
        if (container.length) {
            let bgImage = container.css('background-image');
            container.attr('style', 'background-image: ' + bgImage + '; background-repeat: no-repeat; background-size: 100% 100%;');
        }

        let certificate_content = $("#certificate-builder-area").html();
        let formData = new FormData(form[0]);
        formData.append("certificate_content", certificate_content);
        $.ajax({
            url: action,
            type: "POST",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status === 'success') {
                    toastr["success"](`${data.message}`);
                } else if (data.status == false) {
                    toastr["error"](`${data.message}`);
                }
            },
           
        });
    })
});