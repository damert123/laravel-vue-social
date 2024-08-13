<script>
export default {
    name: "Profile",

    data(){
        return {
            user: {},
            userId: this.$route.params.id,
            avatar: '',
            errors: [],
        }

    },

    methods:{
        getUserInfo(){
            axios.get(`/api/users/${this.userId}/profile`)
                .then(res => {
                    this.user = res.data.data
                    console.log(res);


                })

        },


        storeAvatar(e){
            const file = e.target.files[0]
            const formData = new FormData()
            formData.append('avatar', file)

            axios.post('/api/users/user_avatar', formData)
                .then(res => {
                    this.avatar = res.data.data
                    console.log(res);
                })
        },


        store(){
            const id = this.avatar ? this.avatar.id : null;
            axios.post('/api/users/store_avatar', {avatar: this.avatar, avatar_id: id})
                .then(res => {
                    this.avatar = null;
                    this.getUserInfo()

                })
                .catch(e => {
                    this.errors = e.response.data.errors;
                })

        }
    },

    mounted() {
        this.getUserInfo()
    }
}
</script>

<template>

    <div class="flex justify-center flex-col items-center ">
        <p class="text-xl">Профиль</p>

        <div class="flex flex-col items-center w-96 ">
            <div class="mt-3 text-lg flex gap-x-2 border border-sky-500 p-3 rounded-lg">
                <p>Имя:</p>
                <p>{{user.name}}</p>
            </div>
            <div class="mt-3 text-lg flex gap-x-2 border border-sky-500 p-3 rounded-lg">
                <p>Email:</p>
                <p>{{user.email}}</p>


            </div>

            <div class="mt-3 text-lg flex gap-x-2 flex-col items-center">
                <p>Аватар:</p>

            </div>

            <div v-if="user.avatar_url" :class="['mb-3', avatar ? 'hidden' : '']">
                <img :src="user.avatar_url" alt="preview" class="w-52 h-52 object-cover rounded-full border-4 border-sky-500">
            </div>

            <div v-if="avatar" class="mb-3">
                <img :src="avatar.url" alt="preview">
            </div>
            <div v-if="avatar">
                <a @click.prevent="avatar = null" href="#" class="w-full  mb-3 shadow-sm bg-red-400 text-white text-sm p-3 border rounded-md inline-flex items-center justify-center cursor-pointer hover:bg-red-500 transition-all ease-linear">Отменить</a>
            </div>
            <div class="mb-3">
                <input @change="storeAvatar" ref="file" type="file" id="file-upload" class="hidden">
                <a href="#" class="w-full text-sm p-3 border rounded-md shadow-sm inline-flex items-center justify-center cursor-pointer" @click.prevent="$refs.file.click()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="ml-2">Загрузить картинку</span> <!-- Optional text -->
                </a>
            </div>

            <a @click.prevent="store" href="#" class="p-2 mt-3 text-lg bg-blue-500 text-white rounded-lg w-1/3 text-center">Сохранить</a>
        </div>


    </div>

</template>

<style scoped>

</style>
