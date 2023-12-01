<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive } from 'vue';
import { onMounted } from 'vue';


const props = defineProps(['auth'])


console.log(props.auth.user)
const state = reactive({
    users: [],
    message: null,
    messages:[]
})

const sendMessage = () => {
    axios.post(route('event.message'),{
        message: state.message
    }).then(()=>{
        state.messages.push({
            'user' : 'Me',
            'message' : state.message,
            'from' : 'user',
        })
        state.message = null
    })
}

onMounted(()=>{


    axios.get(route('event.join'))

    window.Echo.private(`chat-room`)
    .listen('ChatRoomJoin',(e)=>{
        if(state.users.indexOf(e.user) < 0 && e.id !== props.auth.user.id){
            state.users.push(e.user)
        }
    })
    .listen('ChatRoomSendPublicMessage',(e)=>{
        state.messages.push(e)
    });
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in as <span class="font-bold">{{ $page.props.auth.user.name }}</span></div>


                    <div class="p-6" v-if="state.users.length >= 1">
                        <p class="font-bold mb-3">Online Users: {{  state.users.length }}</p>
                        <div v-for="(name,id) in state.users" :key="id"
                            class="inline border px-2 py-1 mr-2 border-green-900 rounded-md"
                        >
                              {{ name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="message-container mb-10 pb-20 flex flex-col">
                            <template v-for="(message,id) in state.messages" :key="id">
                                <div v-if="message.from === 'server'">
                                    <span class="block">{{ message.user }}</span>
                                    <span class="bubble">
                                        {{ message.message }}
                                    </span>
                                </div>
                                <div v-else>

                                    <span class="bubble sent">
                                        {{ message.message }}
                                    </span>
                                </div>
                            </template>
                        </div>
                        <div>
                            <form @submit.prevent="sendMessage">
                                <input v-model="state.message" type="text" class="w-[90%] min-h-[50px]">
                                <button type="submit" class="w-[10%] border min-h-[50px]">
                                    SEND
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>
.bubble{
    @apply inline-block max-w-[60%] min-w-[20%]  border px-5 py-5 rounded-lg bg-gray-400;
}

span.bubble.sent{
    @apply float-right bg-blue-400 block;
}

button.button{
    @apply border border-gray-400 px-2 py-1 rounded-md;
}

button.button.button-success{
    @apply border-green-400 text-green-600 hover:bg-green-400 hover:text-white
}

button.button.button-absent{
    @apply border-red-400 text-red-600 hover:bg-red-400 hover:text-white
}
</style>