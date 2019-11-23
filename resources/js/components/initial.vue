<template>
    <div class="jumbotron">
        <h1>Welcome to Virtual Wallet</h1>
        <h2>{{ numWallets }}</h2>
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
        data: function(){
			return {
		        title: '',
		        showSuccess: false,
		        successMessage: '',
		        currentUser: null,
		        numWallets: '',
			}
		},
        methods: {
            login() {
                console.log('InitialPage-GotoLogin')
            },
            register() {
                console.log('InitialPage-GotoRegister')
            },
            getNumWallets: function(){
                axios.get('api/numwallets')
	                .then(response=>{this.numWallets = response.data.data; });
            }
        },
        mounted() {
			this.getUsers();
		}
    }
</script>