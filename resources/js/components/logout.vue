<template>
    <div class="jumbotron">
        <h2>Confirmar Logout</h2>
        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="logout">Logout</a>
        </div>
    </div>
</template>

<script type="text/javascript">    
    export default {
        methods: {
            logout() {
                console.log('logout')
            }

        },
    }
</script>