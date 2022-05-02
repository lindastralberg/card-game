<template>
    <div class="container">
        <h1>Card Game</h1>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            fetch('http://localhost:8000/api/new_deck')
                .then(response => response.json())
                .then(data => console.log(data));
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