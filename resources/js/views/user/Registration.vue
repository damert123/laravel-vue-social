<script>
export default {
    name: "Registration",

    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        }
    },

    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                    .then(res => {
                        localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN']);
                        this.$router.push({name: 'user.personal'})
                    })
                    .catch(error => {
                        console.error('Registration error:', error.response);
                    });
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
            <h2 class="text-2xl font-bold mb-4">Register</h2>
            <input v-model="name" type="text" placeholder="name" class="w-full p-3 mb-3 border rounded-md">
            <input v-model="email" type="email" placeholder="email" class="w-full p-3 mb-3 border rounded-md">
            <input v-model="password" type="password" placeholder="password" class="w-full p-3 mb-3 border rounded-md">
            <input v-model="password_confirmation" type="password" placeholder="password_confirmation" class="w-full p-3 mb-3 border rounded-md">
            <button @click.prevent="register" class="bg-blue-500 w-full text-white p-3 rounded-md hover:bg-blue-700 transition-all ease-in">Зарегаться</button>
        </div>
    </div>

</template>

<style scoped>

</style>
