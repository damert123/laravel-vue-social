<script>
import Post from "../../components/Post.vue";
import Stat from "../../components/Stat.vue";
export default {
    name: "Show",

    data(){
        return{

            user: {},
            posts:[],
            userId: this.$route.params.id,
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
            axios.post('/api/users/stats', {user_id: this.userId})
                .then(res => {
                    this.stats = res.data.data

                })
        },

        getPosts(){
            axios.get(`/api/users/${this.userId}/posts`)
                .then(res => {
                    this.user = res.data.user
                    this.posts = res.data.posts
                })
        },
    }

}
</script>

<template>
    <div class="flex justify-center items-center  bg-gray-100  flex-col ">
        <h1>Post</h1>
        <h1 class="text-2xl">{{ user.name }} s Posts</h1>
        <Stat class="mt-3" :stats = "stats"></Stat>


                <div v-if="posts" class="px-6 py-4 bg-gray-100 min-h-screen">
                    <div class="grid grid-cols-1 gap-6 w-96">
                        <Post v-for="post in posts" :post="post"></Post>
                    </div>
                </div>
    </div>


</template>

<style scoped>

</style>
