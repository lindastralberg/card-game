<template>
    <div class="container">
        <h1>Card Game</h1>
        <div v-if="!isGameOver">
            <card 
                v-if="card"
                :src="card.image"
                :value="card.value"
                :suit="card.suit"
            />
            <div class="buttons">
                <button @click="this.guessLower">Lower</button>
                <button @click="this.guessSame">Same</button>
                <button @click="this.guessHigher">Higher</button>
            </div>
        </div>
        <div v-else class="game-over-container">
            <h1>Game Over</h1>
            <div class="buttons">
                <button @click="this.restart">Play again</button>
            </div>
        </div>
    </div>
</template>

<script>
    import Card from './Card.vue';

    export default {
        components: { Card },
        async mounted() {
            this.restart();
        },
        data() {
            return {
                deckId: null,
                card: null,
                previousCard: null,
                isGameOver: false,
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
            async drawNewCard() {
                this.previousCard = this.card;
                this.card = await this.drawCard();
            },
            async guessLower() {
                await this.drawNewCard();

                if (this.translateValue(this.card.value) < this.translateValue(this.previousCard.value)) {
                    console.log('Correct!');
                } else {
                    console.log('You lose.');
                    this.gameOver();
                }
            },
            async guessSame() {
                await this.drawNewCard();

                if (this.translateValue(this.card.value) == this.translateValue(this.previousCard.value)) {
                    console.log('Correct!');
                } else {
                    console.log('You lose.');
                    this.gameOver();
                }
            },
            async guessHigher() {
                await this.drawNewCard();

                if (this.translateValue(this.card.value) > this.translateValue(this.previousCard.value)) {
                    console.log('Correct!');
                } else {
                    console.log('You lose.');
                    this.gameOver();
                }
            },
            gameOver() {
                this.isGameOver = true;
            },
            async restart() {
                this.isGameOver = false;

                await this.getNewDeck();
                await this.drawCard();
            },
            translateValue(value) {
                switch(value) {
                    case 'ACE':
                        return 1;
                    case 'JACK':
                        return 11;
                    case 'QUEEN':
                        return 12;
                    case 'KING':
                        return 13;
                    default:
                        return value;
                }
            }
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