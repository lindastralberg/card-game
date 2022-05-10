<template>
    <div class="container">
        <h1 class="game-title">Card Game</h1>
        <p class="points">Points: {{ this.points }}</p>
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
            <previous-cards :previousCards="this.previousCards" />
        </div>
        <game-over
            v-else
            :lastGuess="this.lastGuess"
            :actualResult="this.actualResult"
            :points="this.points"
            :previousCards="this.previousCards"
            @playAgain="this.restart"
        />
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
                currentValue: null,
                previousCard: null,
                previousValue: null,
                previousCards: [],
                isGameOver: false,
                points: 0,
                lastGuess: null,
                actualResult: null,
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
            async guess() {
                await this.drawNewCard();
                this.setValues();
                this.setResult();

                if (this.actualResult == this.lastGuess) {
                    this.points++;
                } else {
                    this.gameOver();
                }
            },
            async guessLower() {
                this.lastGuess = 'LOWER';
                this.guess();
            },
            async guessSame() {
                this.lastGuess = 'SAME';
                this.guess();
            },
            async guessHigher() {
                this.lastGuess = 'HIGHER';
                this.guess();
            },
            setValues() {
                this.currentValue = this.translateValue(this.currentCard.value);
                this.previousValue = this.translateValue(this.previousCard.value);
            },
            setResult() {
                if (this.currentValue == this.previousValue) {
                    this.actualResult = 'SAME';
                } else if (this.currentValue > this.previousValue) {
                    this.actualResult = 'HIGHER';
                } else if (this.currentValue < this.previousValue) {
                    this.actualResult = 'LOWER';
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
.container {
    display: grid;
    grid-column-start: 2;
    justify-content: center;
}

.game-title {
    text-align: center;
    margin-bottom: 10px;
}

.points {
    text-align: center;
    margin-top: 0;
    margin-bottom: 30px;
}

.buttons {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    margin-top: 20px;
}

.buttons button {
    padding: 4px 12px;
    box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
    cursor: pointer;
}
</style>