
$(document).ready(function () {
    CustomControls.ini();

    if (window.location.hash) {
        setTimeout(function () {
            $("a[href=" + window.location.hash + "]").trigger("click");
        }, 1500);
    }


});

var CustomControls = {
    iniButtons: function () {
        $.each($(".statusButtons"), function () {
            var group = $(this);
            var target = $("#" + group.data("target"));
            group.find("a[rel=" + target.val() + "]").addClass("active");
        });
        $(".statusButtons .btn").on({
            click: function (e) {
                e.preventDefault();
                var btn = $(this);
                var group = btn.parent();
                var target = $("#" + group.data("target"));

                group.children().removeClass("active");
                btn.addClass("active");

                target.val(btn.attr("rel"));
            }
        });
    },
    ini: function () {
        CustomControls.iniButtons();
    }
}


window.helper = {
    dates: {
        dateToHour: function (datetime) {
            var dtHour = datetime.substring(11, 20);
            return dtHour;
        }
    }
}