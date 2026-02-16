export function isMobileDevice() {
    const ua = navigator.userAgent.toLowerCase()
    const isMobileUA = /android|iphone|ipad|ipod/i.test(ua)
    const isSmallScreen = window.innerWidth <= 768
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches

    return isMobileUA || isStandalone || isSmallScreen
}
