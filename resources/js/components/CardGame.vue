<template>
    <div class="container">
        <h1>Card Game</h1>
        <div v-if="!isGameOver" class="game-container">
            <card 
                v-if="currentCard"
                :src="currentCard.image"
                :value="currentCard.value"
                :suit="currentCard.suit"
                :previous="false"
            />
            <div class="buttons">
                <button @click="this.guessLower">Lower</button>
                <button @click="this.guessSame">Same</button>
                <button @click="this.guessHigher">Higher</button>
            </div>
            <p>Points: {{ this.points }}</p>
            <previous-cards :previousCards="this.previousCards" />
        </div>
        <game-over v-else @playAgain="this.restart" />
    </div>
</template>

<script>
    import Card from './Card.vue';
    import PreviousCards from './PreviousCards.vue';
    import GameOver from './GameOver.vue';

    export default {
        components: {
            Card,
            PreviousCards,
            GameOver,
        },
        async mounted() {
            this.restart();
        },
        data() {
            return {
                deckId: null,
                currentCard: null,
                previousCard: null,
                previousCards: [],
                isGameOver: false,
                points: 0,
            };
        },
        methods: {
            async getNewDeck() {
                await fetch('http://localhost:8000/api/new_deck')
                    .then(response => response.json())
                    .then(data => this.deckId = data.deck_id);

                this.currentCard = await this.drawCard();
            },
            async drawCard() {
                let card;
                await fetch(`http://localhost:8000/api/${this.deckId}/draw`)
                    .then(response => response.json())
                    .then(data => card = data.cards[0]);

                this.previousCards.push(card);

                return card;
            },
            async drawNewCard() {
                this.previousCard = this.currentCard;
                this.currentCard = await this.drawCard();
            },
            async guessLower() {
                await this.drawNewCard();

                if (this.translateValue(this.currentCard.value) < this.translateValue(this.previousCard.value)) {
                    this.points++;
                } else {
                    this.gameOver();
                }
            },
            async guessSame() {
                await this.drawNewCard();

                if (this.translateValue(this.currentCard.value) == this.translateValue(this.previousCard.value)) {
                    this.points++;
                } else {
                    this.gameOver();
                }
            },
            async guessHigher() {
                await this.drawNewCard();

                if (this.translateValue(this.currentCard.value) > this.translateValue(this.previousCard.value)) {
                    this.points++;
                } else {
                    this.gameOver();
                }
            },
            gameOver() {
                this.isGameOver = true;
            },
            async restart() {
                this.isGameOver = false;
                this.previousCards = [];
                this.previousCard = null;
                this.points = 0;

                await this.getNewDeck();
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
                        return parseInt(value);
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