<template>
    <div class="game-over-container">
        <div class="game-over">
            <h1>Game Over</h1>
            <p>You guessed {{ this.lastGuess }}, <br>
            but {{ this.previousValue }} of {{ this.previousSuit }} were {{ this.actualResult }} <br>
            {{ this.bindingWord }} {{ this.currentValue }} of {{ this.currentSuit }}</p>
            <p><b>Total points:</b> {{ this.points }}</p>
            <div class="buttons">
                <button @click="this.playAgain">Play again</button>
            </div>
            <p 
                v-if="!this.showPreviousCards" 
                class="previous-cards-option"
                @click="showPreviousCards = true"
            >
                Display cards
            </p>
            <p 
                v-else 
                class="previous-cards-option" 
                @click="showPreviousCards = false"
            >
                Hide cards
            </p>
            <previous-cards
                v-if="this.showPreviousCards"
                :previousCards="this.previousCards"
            />
        </div>
        <div class="overlay"></div>
    </div>
</template>

<script>
    import PreviousCards from './PreviousCards.vue';

    export default {
        props: [ 'lastGuess', 'actualResult', 'points', 'previousCards' ],
        components: { PreviousCards },
        data() {
            return {
                showPreviousCards: false,
                previousValue: this.previousCards[this.previousCards.length - 1].value,
                previousSuit: this.previousCards[this.previousCards.length - 1].suit,
                currentValue: this.previousCards[this.previousCards.length - 2].value,
                currentSuit: this.previousCards[this.previousCards.length - 2].suit,
                bindingWord: (this.actualResult == 'SAME') ? 'as' : 'than',
            };
        },
        methods: {
            playAgain() {
                this.$emit('playAgain', true);
            }
        }
    }
</script>

<style scoped>
.game-over {
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

.previous-cards-option {
    cursor: pointer;
    margin-top: 22px;
}

.previous-cards-option:hover {
    color: #575757;
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