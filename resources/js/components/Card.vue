<template>
    <div class="card-container">
        <img :src="this.src" :class="{ 'small-card': previous }" />
    </div>
</template>

<script>
    export default {
        props: [ 'src', 'value', 'suit', 'previous' ],
    }
</script>

<style scoped>
.card-text {
    text-align: center;
}

.small-card {
    width: 100px;
}
</style>