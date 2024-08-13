<script setup>
import {ref, onMounted, watch} from "vue";
import {useRouter, useRoute} from "vue-router";
import axios from "axios";


const token = ref(null);
const router = useRouter();
const route = useRoute();
const userId = ref(null);




const getToken = () => {
    token.value = localStorage.getItem('x_xsrf_token');
};



const getUserId = async () => {
    try {
        const response = await axios.get(`/api/users/userId`);
        userId.value = response.data.id;

    } catch (error) {
        console.error('Failed to fetch user id:', error);
    }
};


const logout = async () => {
    try {
        await axios.post('/logout');
        localStorage.removeItem('x_xsrf_token');
        router.push({name: 'user.login'});
    } catch (error){
        console.log(error)
    }
}

onMounted(() => {
    getToken();
    if (token.value) {
        getUserId();
    }

})


watch(route, (to, from) => {
    getToken();
    getUserId();


})


</script>

<template>
    <div>
        <header class="bg-blue-600 text-white p-4">
            <nav class="flex justify-around ">
                <router-link v-if="!token" :to="{name: 'user.login'}" class="hover:underline">LOGIN</router-link>
                <router-link v-if="token" :to="{name: 'user.index'}" class="hover:underline">Users</router-link>
                <router-link v-if="token" :to="{name: 'user.feed'}" class="hover:underline">Лента</router-link>
                <router-link v-if="token" :to="{name: 'user.personal'}" class="hover:underline">Мои посты</router-link>
                <router-link v-if="!token" :to="{name: 'user.registration'}" class="hover:underline">REGISTRATION</router-link>
                <router-link v-if="token && userId " :to="{name: 'user.profile', params:{id: userId}}"  class="hover:underline" >Профиль</router-link>
                <a  v-if="token" @click.prevent="logout" href="#" class="hover:underline">Logout</a>
            </nav>
        </header>

        <main>
            <router-view></router-view>
        </main>



    </div>

</template>

<style scoped>

</style>
