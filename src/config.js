$(document).ready(function () {
    let EnableNotify = $("#EnableNotify");

    EnableNotify.click(function () {
        if ($(EnableNotify).is(':checked')) {
            $('#NotifyConfig input').each(
                function (index) {
                    if ($(this).attr('disabled') === undefined) {
                        return true;
                    }
                    $(this).removeAttr('disabled');
                }
            );
        } else {
            $('#NotifyConfig input').each(
                function (index) {
                    if ($(this).attr('name') === "EnableNotify") {
                        return true;
                    }
                    $(this).attr('disabled', 'disabled');

                }
            );
        }
    });

    AddSupportDepartment();
    LoadConfig();
});

function AddSupportDepartment() {
    $.ajax({
        method: "GET",
        url: "addonmodules.php?module=PushNotificationsForAdmin&ajax=true&supportDepartment=true",
        type: "json",
        async: false
    }).done(function (msg) {
        let json = $.parseJSON(msg);
        for (let i = 0; i < json.length; i++) {
            let input = $('<div class="form-check"><input class="form-check-input" type="checkbox" value="' + json[i].id + '" name="notify[newTicketsDepartament][]" id="NotifyTicketsDepartament' + json[i].id + '" disabled="disabled"> <label class="form-check-label" for="NotifyTicketsDepartament' + json[i].id + '">Получать уведомления о новых тикетах с отдела: ' + json[i].name + '</label> </div><div class="form-check"><input class="form-check-input" type="checkbox" value="' + json[i].id + '" name="notify[TicketsReplyDepartament][]" id="NotifyTicketsReplyDepartament' + json[i].id + '" disabled="disabled"> <label class="form-check-label" for="NotifyTicketsReplyDepartament' + json[i].id + '">Получать уведомления о новых ответах на тикеты в отделе: ' + json[i].name + '</label> </div>');
            $('#NotifyConfig > button').before(input);
        }
        console.log(json);
    }).fail(function (jqXHR, textStatus) {
        console.log("error", jqXHR, textStatus);
    });
}

function LoadConfig() {
    $.ajax({
        method: "GET",
        url: "addonmodules.php?module=PushNotificationsForAdmin&ajax=true&UserConfig=true",
        type: "json",
        async: false
    }).done(function (msg) {
            let json = $.parseJSON(msg);

            if (json.hasOwnProperty("enable")) {
                $("#EnableNotify").click();
            }

            if (json.hasOwnProperty("AudioUrl")) {
                $("#NotifyAudio").val(json['AudioUrl']);
            }

            if (json.hasOwnProperty("TicketsReply")) {
                $("#NotifyTicketsReply").click();
            }

            if (json.hasOwnProperty("newOrder")) {
                $("#NotifyOrder").click();
            }

            if (json.hasOwnProperty("PaidOrder")) {
                $("#NotifyPaidOrder").click();
            }

            if (json.hasOwnProperty("newTickets")) {
                $("#NotifyTickets").click();
            }

            if (json.hasOwnProperty("newTicketsDepartament")) {
                for (let i = 0; i < json['newTicketsDepartament'].length; i++) {
                    $("#NotifyTicketsDepartament" + json['newTicketsDepartament'][i]).click();
                }
            }

            if (json.hasOwnProperty("TicketsReplyDepartament")) {
                for (let i = 0; i < json['TicketsReplyDepartament'].length; i++) {
                    $("#NotifyTicketsReplyDepartament" + json['TicketsReplyDepartament'][i]).click();
                }
            }

            console.log(json);
        }
    ).fail(function (jqXHR, textStatus) {
        console.log("error", jqXHR, textStatus);
    });
}