<script>
import Post from "../../components/Post.vue";
import Stat from "../../components/Stat.vue";
export default {
    name: "Personal",

    data(){
        return{

            title: '',
            content: '',
            image: '',
            posts:[],
            user: {},
            errors: [],
            stats: [],

        }

    },

    components: {
        Post,
        Stat
    },



    mounted() {
        this.getPosts()
        this.getStats()
    },



    methods:{

        getStats(){
            axios.post('/api/users/stats', {id: null})
                .then(res => {
                    this.stats = res.data.data

                })
        },

        getPosts(){
            axios.get('/api/posts')
                .then(res => {
                    this.posts = res.data.data
                })
        },

        storeImage(e){
            const file = e.target.files[0]
            const formData = new FormData()
            formData.append('image', file)

            axios.post('/api/post_images', formData)
                .then(res => {
                    this.image = res.data.data
                })
        },

        store(){
            const id = this.image ? this.image.id : null
            axios.post('/api/posts', {title: this.title, content: this.content, image_id: this.image.id})
                .then(res => {
                    this.title = '';
                    this.content = '';
                    this.image = null;
                    this.posts.unshift(res.data.data);

                })
                .catch(e => {
                    this.errors = e.response.data.errors;
                })
        },







    }


}
</script>

<template>
    <div class="flex justify-center px-6 py-4 bg-gray-100">
        <Stat :stats = "stats"></Stat>
    </div>

   <div class="flex justify-center items-center  bg-gray-100  flex-col ">
       <div class="flex flex-col justify-center bg-white p-8 rounded-lg shadow-sm w-full max-w-sm mb-16 mt-16">
           <h2 class="text-2xl mb-3 text-center">Добавление поста</h2>
           <div v-if="errors.title">
               <p v-for="error in errors.title" class="text-red-500 text-center">{{error}}</p>
           </div>
           <input v-model="title" type="text" :class="['p-3 border rounded-md w-full mb-3 focus:border-blue-500 outline-none transition-all ease-in', errors.title ? 'border-red-500' : 'border'] " placeholder="Заголовок">
           <div v-if="errors.content">
               <p v-for="error in errors.content" class="text-red-500 text-center">{{error}}</p>
           </div>
           <textarea v-model="content" :class="['p-3 border rounded-md w-full mb-3 focus:border-blue-500 outline-none transition-all ease-in', errors.content ? 'border-red-500' : 'border'] " placeholder="Текст"></textarea>

           <div v-if="image" class="mb-3">
               <img :src="image.url" alt="preview">
           </div>
           <div v-if="image">
               <a @click.prevent="image = null" href="#" class="w-full  mb-3 shadow-sm bg-red-400 text-white text-sm p-3 border rounded-md inline-flex items-center justify-center cursor-pointer hover:bg-red-500 transition-all ease-linear">Отменить</a>
           </div>
           <div class="mb-3">
               <input @change="storeImage" ref="file" type="file" id="file-upload" class="hidden">
               <a href="#" class="w-full text-sm p-3 border rounded-md shadow-sm inline-flex items-center justify-center cursor-pointer" @click.prevent="$refs.file.click()">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                   </svg>
                   <span class="ml-2">Загрузить картинку</span> <!-- Optional text -->
               </a>
           </div>
           <a @click.prevent="store" href="#" class="p-3 bg-blue-500 text-white rounded-lg w-full text-center shadow-sm hover:bg-blue-700 transition-all ease-in">Опубликовать</a>
       </div>


       <div v-if="posts" class="px-6 py-4 bg-gray-100 min-h-screen">
           <div class="grid grid-cols-1 gap-6 w-96">
              <Post v-for="post in posts" :post="post"> </Post>
           </div>
       </div>
   </div>



</template>

<style scoped>

</style>
