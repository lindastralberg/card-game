<template>
    <div class="container">
        <h1>Card Game</h1>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
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