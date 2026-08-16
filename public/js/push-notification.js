function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function registerServiceWorker() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push messaging is not supported');
        return;
    }

    navigator.serviceWorker.register('/sw.js')
        .then(function (registration) {
            console.log('Service Worker registered');
            // Tunggu service worker siap
            return navigator.serviceWorker.ready;
        })
        .then(function (registration) {
            subscribeUser(registration);
        })
        .catch(function (error) {
            console.error('Service Worker registration failed:', error);
        });
}

function subscribeUser(registration) {
    const vapidPublicKey = document.querySelector('meta[name="vapid-public-key"]').content;
    
    if (!vapidPublicKey) {
        console.error('VAPID Public Key not found in meta tag');
        return;
    }

    const applicationServerKey = urlBase64ToUint8Array(vapidPublicKey);

    registration.pushManager.getSubscription()
        .then(function (subscription) {
            if (subscription) {
                return updateSubscriptionOnServer(subscription);
            }

            console.log('Subscribing user...');
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            })
            .then(function (subscription) {
                console.log('User is subscribed');
                updateSubscriptionOnServer(subscription);
            })
            .catch(function (error) {
                console.error('Failed to subscribe the user: ', error);
            });
        });
}

function updateSubscriptionOnServer(subscription) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    return fetch('/subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(subscription)
    })
    .then(function (response) {
        if (!response.ok) {
            throw new Error('Bad status code from server.');
        }
        return response.json();
    });
}

// Inisialisasi
if (Notification.permission === 'granted') {
    registerServiceWorker();
} else if (Notification.permission !== 'denied') {
    // Bisa dipicu lewat tombol atau otomatis saat loading
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            registerServiceWorker();
        }
    });
}
