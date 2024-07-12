<script>
export default {
    name: "Post",

    props: [
        'post'

    ],

    data(){
      return {
          title: '',
          content: '',
          body: '',
          errors: [],
          isRepost: false,
          isComment: false,
          repostedId: null,
          comments: [],
          isShowed: false,
          comment: null,
      }
    },

    methods:{
        toggleLike(post){
            axios.post(`/api/posts/${post.id}/toggle_like`)
                .then(res => {
                    post.is_liked = res.data.is_liked
                    post.likes_count = res.data.likes_count
                })

        },

        openComment(){
            this.isComment = !this.isComment
        },

        setParentId(comment){
            this.comment = comment
            this.isComment = !this.isComment

        },

        storeComment(post){
            const commentData = {
                body: this.body,
            };

            if (this.comment && this.comment.id) {
                commentData.parent_id = this.comment.id;
            }
          axios.post(`/api/posts/${post.id}/comment`, commentData)
              .then(res => {
                  this.body = ''
                  this.getComments(post)
                  this.comment = null
                  post.comments_count++
                  this.isShowed = true


              })
              .catch(error => {
                  console.error('Ошибка при отправке комментария:', error);
              });
        },

        getComments(post){
            axios.get(`/api/posts/${post.id}/comment`)
                .then(res => {
                    this.comments = res.data.data
                    this.isShowed = true
                })
        },



        openRepost(){
            if (this.$route.name === 'user.personal') return
            this.isRepost = !this.isRepost


        },


        repost(post){
            if (this.$route.name === 'user.personal') return
            axios.post(`/api/posts/${post.id}/repost`, {title: this.title, content: this.content})
                .then(res => {
                    this.title = ''
                    this.content = ''
                })
                .catch(e => {
                    this.errors = e.response.data.errors;
                })

        },


    }
}
</script>

<template>
    <div
        class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"

    >
        <h2 class="text-2xl font-bold mb-4">{{ post.title }}</h2>
        <img
            v-if="post.image_url"
            :src="post.image_url"
            :alt="post.title"
            class="w-full h-96 object-cover rounded-md mb-4"
        />
        <p class="text-gray-700">{{ post.content }}</p>


        <div v-if="post.reposted_post" class="border border-blue-500 p-5 mt-2 rounded-md">
            <h2 class="text-2xl font-bold mb-4">{{ post.reposted_post.title }}</h2>
            <router-link class="text-slate-500 opacity-70 text-sm flex mt-2 flex-1" :to="{name: 'user.show', params: {id: post.reposted_post.user.id}}"> Репостнуто у {{post.reposted_post.user.name}}</router-link>

            <img
                v-if="post.reposted_post.image_url"
                :src="post.reposted_post.image_url"
                :alt="post.reposted_post.title"
                class="w-full h-96 object-cover rounded-md mb-4"
            />
            <p class="text-gray-700">{{ post.content }}</p>
        </div>
        <div class="flex justify-between mt-2 border-t items-center ">

            <router-link class="text-slate-500 opacity-70 text-sm flex mt-2 flex-1" :to="{name: 'user.show', params: {id: post.user.id}}">{{post.user.name}}</router-link>

            <div class="flex justify-center gap-x-2 mt-2 flex-1">
                <div class="flex items-center ">
                    <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" :class="['size-6 cursor-pointer transition ease-out duration-75  hover:ease-in hover:stroke-red-500 hover:fill-red-500', post.is_liked ? 'fill-red-500 stroke-red-500' : 'fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>

                    <p>{{post.likes_count}}</p>
                </div>

                <div>
                    <svg @click.prevent="openComment(post)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="size-6 cursor-pointer ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                    </svg>
                </div>


                <div class="flex items-center ">
                    <svg @click.prevent="openRepost(post)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="size-6 cursor-pointer  ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                    </svg>
                    <p>{{post.reposted_by_post_count}}</p>
                </div>


            </div>
            <p class="text-slate-500 opacity-70 text-sm flex mt-2 flex-1 justify-end">{{ post.date }}</p>

        </div>

        <div v-if="isRepost" class="mt-3 flex flex-col justify-center items-center">
            <div v-if="errors.title">
                <p v-for="error in errors.title" class="text-red-500 text-center">{{error}}</p>
            </div>
            <input v-model="title" type="text" :class="['p-3 border rounded-md w-full mb-3 focus:border-blue-500 outline-none transition-all ease-in', errors.title ? 'border-red-500' : 'border'] " placeholder="Заголовок">
            <div v-if="errors.content">
                <p v-for="error in errors.content" class="text-red-500 text-center">{{error}}</p>
            </div>
            <textarea v-model="content" :class="['p-3 border rounded-md w-full mb-3 focus:border-blue-500 outline-none transition-all ease-in', errors.content ? 'border-red-500' : 'border'] " placeholder="Текст"></textarea>
            <a @click.prevent="repost(post)" href="#" class="p-3 bg-blue-500 text-white rounded-lg w-1/2 text-center shadow-sm hover:bg-blue-700 transition-all ease-in">Репост</a>

        </div>

        <div v-if="isComment" class="mt-3 ">
            <div class="flex flex-col justify-center ">
                <p class="font-light text-sm text-slate-500 opacity-70">Написать комментарий:</p>
                <div>
                    <p v-if="comment">Ответить {{comment.user.name}}</p>
                    <p v-if="comment" @click="comment = null, openComment(post)" class="cursor-pointer text-sm">Отмена</p>
                </div>

                <input v-model="body" type="text" class="p-3 border rounded-md w-full mb-3 focus:border-blue-500 outline-none transition-all ease-in">
                <div class="flex justify-center">
                    <a @click.prevent="storeComment(post)" href="#" class="p-3 bg-blue-500 text-white rounded-lg w-1/2 text-center shadow-sm hover:bg-blue-700 transition-all ease-in">Отправить</a>
                </div>
            </div>
        </div>

        <div v-if="post.comments_count > 0" class="mt-3">
            <p v-if="!isShowed" @click="getComments(post)" class="cursor-pointer  hover:underline transition-all ease-in"> Показать {{post.comments_count}} комментариев</p>
            <p v-if="isShowed"  @click="isShowed = false" class="cursor-pointer  hover:underline transition-all ease-in"> Скрыть комментарии</p>
            <div v-if="comments && isShowed" >
                <div v-for="comment in comments" class="border rounded-md p-3 mt-3">
                    <div>
                        <div class="flex items-center gap-x-2 justify-between">
                            <p class="text-sm font-semibold mb-2">{{comment.user.name}}</p>
                            <p @click="setParentId(comment)" class="text-sm mb-2 text-slate-500 cursor-pointer ">Ответить</p>
                        </div>

                        <p class="text-md"> <span v-if="comment.answered_for_user" class="text-sky-500">{{comment.answered_for_user}}, </span>{{comment.body}}</p>
                    </div>

                    <p class="text-sm text-slate-500 opacity-70 text-right">{{comment.date}}</p>

                </div>

            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
