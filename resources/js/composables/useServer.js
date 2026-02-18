import { ref, onMounted, onUnmounted } from 'vue'

export default function useServer(){
    const serverAlive = ref(true)
    let interval = null

    const check = async ()=>{
        try{
            await fetch('/health?'+Date.now(), {
                method: 'GET',
                cache: 'no-store'
            })
            serverAlive.value = true
        }catch{
            serverAlive.value = false
        }
    }

    onMounted(()=>{
        check()
        interval = setInterval(check, 5000)
    })

    onUnmounted(()=>{
        clearInterval(interval)
    })

    return { serverAlive }
}
