<template>
    <div class="card-container">
        <img :src="this.src" />
        <p class="card-text">{{ this.value }} of {{ this.suit }}</p>
    </div>
</template>

<script>
    export default {
        props: [
            'src',
            'suit',
            'value',
        ],
        methods: {
            
        }
    }
</script>

<style scoped>
.card-text {
    text-align: center;
}
</style>