<html>
<link rel="stylesheet" href="css/jquery-confirm.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script>
function confirmation(question) {
    var defer = $.Deferred();
    $('<div></div>')
        .html(question)
        .dialog({
            autoOpen: true,
            modal: true,
            title: 'Confirmation',
            buttons: {
                "OK": function () {
                    defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                    $(this).dialog("close");
                },
                "Cancel": function () {
                    defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                    $(this).dialog("close");
                }
            },
            open: function () {
                $(this).siblings('.ui-dialog-buttonpane').find('button:eq(1)').focus();
            },
            close: function () {
                $(this).remove();
            }
        });
    return defer.promise();
}
$(document).ready(function () {
    confirmation("Hello<br/>Hello1<br/>Hello2");
});
</script>
hello world
</html>
