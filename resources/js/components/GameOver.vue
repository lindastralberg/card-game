<template>
    <div v-else class="game-over-container">
        <div class="game-over">
            <h1>Game Over</h1>
            <div class="buttons">
                <button @click="this.playAgain">Play again</button>
            </div>
            <p class="display-previous-cards">Display previous cards</p>
        </div>
        <div class="overlay"></div>
    </div>
</template>

<script>
    export default {
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

.display-previous-cards {
    cursor: pointer;
    margin-top: 22px;
}

.display-previous-cards:hover {
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