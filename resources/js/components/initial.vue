<template>
    <div class="jumbotron">
        <h2>Welcome to Virtual Wallet</h2>
        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
            <a class="btn btn-primary" v-on:click.prevent="register">Register</a>
        </div>

        <div class="form-group">
            
        </div>
    </div>
</template>

<script type="text/javascript">    
    export default {
        methods: {
            login() {
                console.log('InitialPage-GotoLogin')
            },
            register() {
                console.log('InitialPage-GotoRegister')
            }

        },
    }
</script>