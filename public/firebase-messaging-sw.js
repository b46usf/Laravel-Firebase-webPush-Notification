/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"
);

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/

// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDz2XnmikLRO8-OtKDtyamKtXw9siYoX4g",
    authDomain: "learnapi-2f9e0.firebaseapp.com",
    databaseURL: "https://learnapi-2f9e0-default-rtdb.firebaseio.com",
    projectId: "learnapi-2f9e0",
    storageBucket: "learnapi-2f9e0.appspot.com",
    messagingSenderId: "43647748479",
    appId: "1:43647748479:web:65b36ef89d8d5b80a7724a",
    measurementId: "G-194896LJQN",
};

firebase.initializeApp(firebaseConfig);

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload);
    // Customize notification here
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});

// messaging.onMessage(function (payload) {
//     const noteTitle = payload.notification.title;
//     const noteOptions = {
//         body: payload.notification.body,
//         icon: payload.notification.icon,
//     };
//     new Notification(noteTitle, noteOptions);
// });
