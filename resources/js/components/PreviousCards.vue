<template>
    <div class="previous-cards-container">
        <h2>Previous cards</h2>
        <div class="previous-cards">
            <card v-for="card in this.previousCards"
                :key="card.code"
                :src="card.image"
                :value="card.value"
                :suit="card.suit"
                :previous="true"
            />
        </div>
    </div>
</template>

<script>
    import Card from './Card.vue';

    export default {
        components: { Card },
        props: [ 'previousCards' ],
    }
</script>

<style scoped>
.previous-cards {
    display: grid;
    grid-gap: 10px;
    grid-template-columns: repeat(2, auto);
}
</style>