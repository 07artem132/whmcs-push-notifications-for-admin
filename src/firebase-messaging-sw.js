'use strict';

const firebase = require("firebase/app");
require("firebase/messaging");

firebase.initializeApp({
    messagingSenderId: '509849389133'
});

const messaging = firebase.messaging();
// Customize notification handler
messaging.setBackgroundMessageHandler(function (payload) {
    console.log('Handling background message', payload);

    // Copy data object to get parameters in the click handler
    payload.data.data = JSON.parse(JSON.stringify(payload.data));

    return self.registration.showNotification(payload.data.title, payload.data);
});


self.addEventListener('install', (event) => {
    event.waitUntil(skipWaiting());
}, false);

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
}, false);

self.addEventListener('push', (event) => {
    if (!event.data) {
        return;
    }

    const parsedData = event.data.json();
    const notification = parsedData.notification;
    const title = notification.title;
    const body = notification.body;
    const icon = notification.icon;
    const data = parsedData.data;

    event.waitUntil(
        self.registration.showNotification(title, {body, icon, data})
    );
}, false);

self.addEventListener('notificationclick', (event) => {
    console.log('event notificationclick');
    const url = event.notification.data.url;
    console.log('url:  ' + url);

    event.notification.close();

    if (clients.openWindow) {
        event.waitUntil(self.clients.openWindow(url));
    }

}, false);

