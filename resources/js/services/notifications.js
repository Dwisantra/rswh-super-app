import axios from 'axios'

const VAPID_PUBLIC_KEY = import.meta.env.VITE_VAPID_PUBLIC_KEY || ''
const NOTIFICATION_PROMPT_KEY = 'notification_prompt_requested'

const urlBase64ToUint8Array = (base64String) => {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const rawData = window.atob(base64)

  return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)))
}

export const isNotificationSupported = () =>
  typeof window !== 'undefined' && 'Notification' in window && 'serviceWorker' in navigator

export const isPushSupported = () => isNotificationSupported() && 'PushManager' in window

export const isVapidConfigured = () => Boolean(VAPID_PUBLIC_KEY)

export const getNotificationPermission = () => {
  if (typeof window === 'undefined' || !('Notification' in window)) {
    return 'unsupported'
  }

  return Notification.permission
}

export const ensureServiceWorkerRegistration = async () => {
  if (!('serviceWorker' in navigator)) {
    throw new Error('Service worker tidak didukung browser ini.')
  }

  return navigator.serviceWorker.ready
}

export const requestNotificationPermission = async () => {
  if (!isNotificationSupported()) {
    throw new Error('Notifikasi belum didukung di perangkat ini.')
  }

  const permission = await Notification.requestPermission()

  if (permission !== 'granted') {
    throw new Error('Izin notifikasi belum diberikan.')
  }

  return permission
}

export const subscribeToPushNotifications = async () => {
  if (!isPushSupported()) {
    return null
  }

  if (!isVapidConfigured()) {
    return null
  }

  const registration = await ensureServiceWorkerRegistration()
  const existingSubscription = await registration.pushManager.getSubscription()

  if (existingSubscription) {
    return existingSubscription.toJSON()
  }

  const subscription = await registration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
  })

  return subscription.toJSON()
}

export const registerPushSubscription = async (subscription) => {
  if (!subscription) return false

  await axios.post('/api/v1/notifications/subscriptions', subscription)
  return true
}

export const activateNotificationChannel = async () => {
  await requestNotificationPermission()

  const subscription = await subscribeToPushNotifications()

  if (subscription) {
    await registerPushSubscription(subscription)
  }

  return {
    subscriptionSent: Boolean(subscription),
    permission: getNotificationPermission(),
    pushSupported: isPushSupported(),
    vapidConfigured: isVapidConfigured()
  }
}

export const autoRequestNotificationPermissionOnFirstOpen = async () => {
  if (!isNotificationSupported()) return
  if (getNotificationPermission() !== 'default') return

  const hasPrompted = localStorage.getItem(NOTIFICATION_PROMPT_KEY)
  if (hasPrompted) return

  localStorage.setItem(NOTIFICATION_PROMPT_KEY, '1')

  try {
    await activateNotificationChannel()
  } catch (_) {
    // user bisa menolak popup; tidak perlu mengganggu flow app
  }
}