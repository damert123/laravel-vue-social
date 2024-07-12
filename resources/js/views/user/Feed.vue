<script>
import Post from "../../components/Post.vue";
export default {
    name: "Feed",

    data(){
        return{

            posts:[],

        }
    },

    components: {
        Post
    },

    mounted() {
        this.getPosts()
    },

    methods:{

        getPosts(){
            axios.get(`/api/users/following_posts`)
                .then(res => {
                    this.posts = res.data.data
                })
        },
    }

}
</script>

<template>
    <div class="flex justify-center items-center  bg-gray-100  flex-col ">
        <h1 class="text-2xl my-4">Лента постов</h1>

                <div v-if="posts" class="px-6 py-4 bg-gray-100 min-h-screen">
                    <div class="grid grid-cols-1 gap-6 w-96">
                        <Post v-for="post in posts" :post="post"></Post>
                    </div>
                </div>
    </div>


</template>

<style scoped>

</style>
