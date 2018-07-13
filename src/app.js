'use strict';

const firebase = require("firebase/app");
require("firebase/messaging");
const play = require('audio-play');
const load = require('audio-loader');

//
console.log(PushNotificationsForAdminFCMConfig);
console.log(PushNotificationsForAdminUserConfig);

const getFirebaseMessagingObject = () => {
    firebase.initializeApp(PushNotificationsForAdminFCMConfig);
    let messaging = firebase.messaging();


    messaging.onMessage(function (payload) {
        if (PushNotificationsForAdminUserConfig === undefined) {
            return;
        }

        if (PushNotificationsForAdminUserConfig.hasOwnProperty("AudioUrl")) {
            load({sound: PushNotificationsForAdminUserConfig['AudioUrl']}).catch((error) => {
                console.log('Ошибка при загрузке аудио буфера');
                console.error(error);
            }).then(function (bufer) {
                console.log('Аудио буфер загружен,воспроизводим...');
                play(bufer['sound']);
            });
        }
    });

    return messaging;
};

const register = (messaging) => {
    if (!navigator.serviceWorker || !messaging) {
        console.log('Ошибка, serviceWorker не поддерживается или FCM не инициализирован.');
        return;
    }

    navigator.serviceWorker.register('./firebase-messaging-sw.js').then(() => {
        console.log('serviceWorker Push admin notify ready');
        return navigator.serviceWorker.ready;
    }).catch((error) => {
        console.error(error);
    }).then((registration) => {
        messaging.useServiceWorker(registration);

        messaging.requestPermission().then(() => {
            console.log('Notification permission granted.');

            messaging.getToken().then((token) => {
                console.log('token: ' + token);
                $.ajax({
                    method: "POST",
                    url: "/?m=PushNotificationsForAdmin",
                    type: "json",
                    data: {token: token}
                }).done(function (msg) {
                    console.log("Data Saved, response: ", msg);
                }).fail(function (jqXHR, textStatus) {
                    console.log("error", jqXHR, textStatus);
                });
            }).catch((error) => {
                console.error(error);
            });

        }).catch((error) => {
            console.log('Unable to get permission to notify.', error);
        });
    });
};

register(getFirebaseMessagingObject());

