<script>
export default {
    name: "Index",

    data() {
        return{
            users: []
        }

    },

    mounted() {
        this.getUsers()

    },





    methods: {
        getUsers(){
            axios.get('/api/users/')
                .then(res =>{
                    this.users = res.data.data
                })
        },

        toggleFollowing(user){
            axios.post(`/api/users/${user.id}/toggle_following`)
                .then(res => {

                    user.is_followed = res.data.is_followed
                    console.log(user.is_followed);
                })

        },




    }


}
</script>

<template class="">

    <div v-if="users" class="flex justify-center items-center bg-gray-100 py-6 min-h-screen">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-1/2 max-w-screen-xl">

            <div
                v-for="user in users"
                :key="user.id"
                class="bg-white to-transparent flex-wrap justify-evenly items-center p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 flex"
            >
                <router-link class="flex justify-center items-center flex-wrap gap-3 w-80" :to="{name: 'user.show', params: {id: user.id}}">
                <p class="text-xl font-semibold mb-2">{{ user.name }}</p>
                <p class="text-gray-600 mb-2">ID: {{ user.id }}</p>
                <p class="text-gray-800 mb-2">{{ user.email }}</p>
                </router-link>
                <div class="mb-2">
                    <a @click.prevent="toggleFollowing(user)" href="#" :class="['border-2 border-blue-500 rounded-full p-2  font-regular hover:bg-blue-600 hover:border-blue-600 transition-all ease-linear', user.is_followed ? 'bg-white text-blue-500 hover:text-white hover:bg-red-500' :'bg-blue-500 text-white']" >{{user.is_followed ? 'Вы подписаны' : 'Подписаться'}}</a>
                </div>

            </div>
        </div>
    </div>


</template>

<style scoped>

</style>
