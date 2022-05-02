<template>
    <div class="container">
        <h1>Card Game</h1>
        <card 
            v-if="this.card"
            :card="this.card"
        />
        <div class="buttons">
            <button>Lower</button>
            <button>Same</button>
            <button>Higher</button>
        </div>
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

                this.card = await this.drawCard();
            },
            async drawCard() {
                let card;
                await fetch(`http://localhost:8000/api/${this.deckId}/draw`)
                    .then(response => response.json())
                    .then(data => card = data.cards[0]);

                return card;
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

.buttons {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
}

.buttons button {
    padding: 4px 12px;
    box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
    cursor: pointer;
}
</style>