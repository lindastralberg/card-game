<template>
    <div class="container">
        <h1 class="game-title">Card Game</h1>
        <p class="points">Points: {{ this.points }}</p>
        <pulse-loader
            v-if="loading"
            :loading="loading"
            class="loader"
        />
        <div v-if="!isGameOver && !loading && !showRestartGamePrompt" class="game-container">
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
            v-if="isGameOver && !showRestartGamePrompt"
            :lastGuess="this.lastGuess"
            :actualResult="this.actualResult"
            :points="this.points"
            :previousCards="this.previousCards"
            @playAgain="this.restart"
        />
        <button class="restart-game-btn" @click="showRestartGamePrompt = true">Restart game</button>
        <div v-if="showRestartGamePrompt" class="restart-game-container">
            <div class="restart-game"><h3>Are you sure you want to restart the game?</h3>
                <p>This will reset all your current progress.</p>
                <div class="buttons">
                <button @click="showRestartGamePrompt = false">Cancel</button>
                <button @click="this.restart" class="restart-game-btn-final">Restart game</button>
                </div>
            </div>
            <div class="overlay"></div>
        </div>
    </div>
</template>

<script>
    import Card from './Card.vue';
    import PreviousCards from './PreviousCards.vue';
    import GameOver from './GameOver.vue';
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

    export default {
        components: {
            Card,
            PreviousCards,
            GameOver,
            PulseLoader,
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
                loading: false,
                showRestartGamePrompt: false,
            };
        },
        methods: {
            async getNewDeck() {
                this.loading = true;

                await fetch('http://localhost:8000/api/new_deck')
                    .then(response => response.json())
                    .then(data => this.deckId = data.deck_id);

                this.currentCard = await this.drawCard();
                this.loading = false;
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
                this.deckId = null;
                this.currentCard = null;
                this.currentValue = null;
                this.previousCard = null;
                this.previousValue = null;
                this.previousCards = [];
                this.isGameOver = false;
                this.points = 0;
                this.lastGuess = null;
                this.actualResult = null;
                this.loading = false;
                this.showRestartGamePrompt = false;

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

.loader {
    margin-left: auto;
    margin-right: auto;
}

.buttons {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    margin-top: 20px;
}

.buttons button,
.restart-game-btn {
    padding: 4px 12px;
    box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
    cursor: pointer;
}

.restart-game-btn {
    position: absolute;
    right: 20px;
    top: 20px;
}

.restart-game {
    width: 30em;
    z-index: 100;
    position: relative;
    background-color: white;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0px 0px 20px rgba(0,0,0,0.4);
    margin-top: 24px;
    font-size: 14px;
    text-align: center;
}

.restart-game-btn-final {
    background-color: #dc3545;
    border: 2px solid #dc3545;
    border-radius: 4px;
    color: white;
}

.restart-game-btn-final:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.7);
}
</style>