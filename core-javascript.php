<?php
// session_start();
?>

<!-- core-javascript.html -->
<script src="/js/jquery.js"></script>
<script src="/js/vendor/validator.min.js"></script>
<script src="/js/vendor/imgLiquid-min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/vendor/jquery.simplecolorpicker.js"></script>
<script type='text/javascript'>

    $(document).ready(function () {
        $(".imgLiquidFill").imgLiquid();
    });


    function Info_Over(x) {
        $(x).show();
    }

    function Info_Out(x) {
        $(x).hide();
    }

    $('#navbar_search').click(function () {
        $('#Search_Input').show().focus();
    });

    <?php
        if (isset($_SESSION['user_id'])) {

    ?>
    <!-- If logged in -->
    $('.user_info_popover').popover();

    $('.user_info_popover').click(function (e) {
        e.stopPropagation();
    });

    $(document).click(function (e) {
        if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
            $('.user_info_popover').popover('hide');
        }
    });

    $('.user_info_popover').click(function () {
        $('.user_info_popover').not(this).popover('hide'); //all but this
    });

    $(window).resize(function () {
        $('.user_info_popover').popover('hide');
    });

    $('.followed-by-link-button').click(function () {
        $('.user_info_popover').popover('hide');
    });

    function User_Info_Popover_Hide() {
        $('.user_info_popover').popover('hide');
    }

    $(".modal").on('hidden.bs.modal', function () {
        $(this).data('bs.modal', null);
    });

    $(document).ready(function () {
        $('.colorpicker-longlist').simplecolorpicker();
    });
    <!-- /If logged in -->
    <?php
    }
    ?>
</script>
<!-- /core-javascript.html -->