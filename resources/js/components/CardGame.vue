<template>
    <div class="container">
        <h1>Card Game</h1>
        <card 
            v-if="this.card"
            :card="this.card"
        />
    </div>
</template>

<script>
    import Card from './Card.vue';

    export default {
        components: { Card },
        async mounted() {
            await this.getNewDeck();
            await this.drawCard();
        },
        data() {
            return {
                deckId: null,
                card: null,
            };
        },
        methods: {
            async getNewDeck() {
                await fetch('http://localhost:8000/api/new_deck')
                    .then(response => response.json())
                    .then(data => this.deckId = data.deck_id);
            },
            async drawCard() {
                await fetch(`http://localhost:8000/api/${this.deckId}/draw`)
                    .then(response => response.json())
                    .then(data => this.card = data.cards[0]);
            },
        }
    }
</script>

<style scoped>
h1 {
    text-align: center;
}

.container {
    display: grid;
    grid-column-start: 2;
    justify-content: center;
}
</style>