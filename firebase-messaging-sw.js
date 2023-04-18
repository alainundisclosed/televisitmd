importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyDSSNaBzBZpUYuE9vmRkQUNKfG95eIdBI0",
    authDomain: "televist-video-notification.firebaseapp.com",
    projectId: "televist-video-notification",
    storageBucket: "televist-video-notification.appspot.com",
    messagingSenderId: "583625353289",
    appId: "1:583625353289:web:8e40b41b46d4e597c33798",
    measurementId: "G-XG2KNSNPBM"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'TelevisitMD Locum Tenens';
  const notificationOptions = {
    body: 'You are getting this notification from TelevisitMD Locum Tenens.',
    icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
});
// [END background_handler]