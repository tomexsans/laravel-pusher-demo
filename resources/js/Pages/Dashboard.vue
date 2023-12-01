<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive } from 'vue';
import { onMounted } from 'vue';


const state = reactive({
    message: []
})

const sayHiToAll = () => {
    axios.get(route('event.greet-everyone'))
}




onMounted(()=>{

    console.log('READY to READS')
    window.Echo.private(`notify-all-present`)
    .listen('SayHiToAll',(e)=>{
        state.message.push(e)
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
                    <div class="p-6 text-gray-900">You're logged in! <span class="font-bold">{{ $page.props.auth.user.name }}</span></div>

                    <div class="p-6">
                        <button @click.prevent="sayHiToAll" class="button button-success mr-4">
                            Say Hi to All Online
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Messages from Classmates</div>
                    <div class="message-container">
                        <div v-if="state.message"
                            v-for="(message,key) in state.message"
                            :key="key"
                            class="flex justify-between"
                            >
                            <div>{{ message.message }}</div>
                            <div class="text-gray-500">{{ message.time }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>


.message-container > div {
    @apply p-4 my-2 border border-slate-600 rounded-md mx-5;
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