<script>
export default {
    name: "Login",

    data() {
        return {
            email: null,
            password: null,
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', {email: this.email, password: this.password})
                    .then(r => {
                        localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN']);
                        this.$router.push({name: 'user.personal'})
                    })
                    .catch(err => {
                        console.error(err.response);
                    })
            })
                .catch(error => {
                    console.error('CSRF token error:', error.response);
                });

        }

    }


}
</script>

<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
            <h2 class="text-2xl font-bold mb-4">Login</h2>
            <input v-model="email" type="email" placeholder="email" class="w-full p-3 mb-3 border rounded-md">
            <input v-model="password" type="password" placeholder="password" class="w-full p-3 mb-3 border rounded-md">
            <button @click.prevent="login"  class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 transition-all ease-in">Войти</button>
        </div>
    </div>


</template>

<style scoped>

</style>
