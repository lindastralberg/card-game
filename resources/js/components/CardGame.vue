<template>
    <div class="container">
        <h1>Card Game</h1>
        <card 
            v-if="this.card"
            :src="this.card.image"
            :value="this.card.value"
            :suit="this.card.suit"
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
                const res = await fetch('http://localhost:8000/api/new_deck');
                const data = await res.json();
                this.deckId = data.deck_id;
            },
            async drawCard() {
                const res = await fetch(`http://localhost:8000/api/${this.deckId}/draw`);
                const data = await res.json();
                this.card = data.cards[0];
            },
        }
    }
</script>

<style scoped>
h1 {
    font-family: 'Roboto';
}

.container {
    display: grid;
    grid-column-start: 2;
    justify-content: center;
}
</style>