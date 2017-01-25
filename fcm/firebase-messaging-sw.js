importScripts('https://www.gstatic.com/firebasejs/3.5.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.5.2/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyDaY7_6yfeMbXIQNLgrLa3n_IPDn7uXRvQ",
    authDomain: "push-a3eaa.firebaseapp.com",
    databaseURL: "https://push-a3eaa.firebaseio.com",
    storageBucket: "push-a3eaa.appspot.com",
    messagingSenderId: "290973264967"
};
firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('#####', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
