<script>
export default {
    name: "Stat",

    props: [
        'stats'
    ]
}
</script>

<template>

    <div class="flex justify-between w-96 bg-white p-4 rounded-lg text-center">
        <div>
            Подписчики: {{ stats.subscribers_count }}
        </div>

        <div>
            Лайки: {{ stats.likes_count }}
        </div>

        <div>
            Постов: {{ stats.posts_count }}
        </div>

        <div>
            Отлеживаемые: {{ stats.followings_count }}
        </div>

    </div>

</template>

<style scoped>

</style>
