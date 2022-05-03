<template>
    <div v-else class="game-over-container">
        <div class="game-over">
            <h1>Game Over</h1>
            <div class="buttons">
                <button @click="this.playAgain">Play again</button>
            </div>
            <p 
                v-if="!this.showPreviousCards" 
                class="previous-cards-option"
                @click="showPreviousCards = true"
            >
                Display previous cards
            </p>
            <p 
                v-else 
                class="previous-cards-option" 
                @click="showPreviousCards = false"
            >
                Hide previous cards
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
        props: [ 'previousCards' ],
        components: { PreviousCards },
        data() {
            return {
                showPreviousCards: false,
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