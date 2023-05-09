importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js');
//
// var firebaseConfig = {
//     // apiKey: "AIzaSyBNfQkBewSJoHx7zkVZ5D3HZhVdLLplrTM",
//     // authDomain: "tlk-test-1e477.firebaseapp.com",
//     // projectId: "tlk-test-1e477",
//     // storageBucket: "tlk-test-1e477.appspot.com",
//     // messagingSenderId: "206337115454",
//     // appId: "1:206337115454:web:a0eb20ac8b6e8db0e6d86f",
//     // measurementId: "G-00GVCFH1FE"
//
//     apiKey: "AIzaSyDaZriHYOjiXDwnkQPS-vIgbzA0ovRBT2s",
//     authDomain: "tlktest-79689.firebaseapp.com",
//     projectId: "tlktest-79689",
//     storageBucket: "tlktest-79689.appspot.com",
//     messagingSenderId: "1077155038285",
//     appId: "1:1077155038285:web:f7683cecf473b47f968804",
//     measurementId: "G-RXXCJ57V19"
// };

// apiKey: "AIzaSyDaZriHYOjiXDwnkQPS-vIgbzA0ovRBT2s",
//     authDomain: "tlktest-79689.firebaseapp.com",
//     projectId: "tlktest-79689",
//     storageBucket: "tlktest-79689.appspot.com",
//     messagingSenderId: "1077155038285",
//     appId: "1:1077155038285:web:f7683cecf473b47f968804",
//     measurementId: "G-RXXCJ57V19"

firebase.initializeApp({
    apiKey: "AIzaSyDaZriHYOjiXDwnkQPS-vIgbzA0ovRBT2s",
    authDomain: "tlktest-79689.firebaseapp.com",
    projectId: "tlktest-79689",
    storageBucket: "tlktest-79689.appspot.com",
    messagingSenderId: "1077155038285",
    appId: "1:1077155038285:web:f7683cecf473b47f968804",
    measurementId: "G-RXXCJ57V19"
});

// firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload);
    const notification = JSON.parse(payload);
    const notificationOption = {
        body: notification.body,
        icon: notification.icon
    };
    return self.registration.showNotification(payload.notification.title, notificationOption);
});
