<template>
    <div class="jumbotron">
      <h1>Welcome to Virtual Wallet </h1>
      <!--<div v-if="this.$store.state.isLogged"> -->
      <!--<h1>Welcome {{this.$store.state.user.name}} </h1> -->
      <!--<h2>Total Virtual Wallets {{wallets.lenght}}</h2> -->
     <!-- <users></users>
    </div> -->
    
        <br>
        <br>
        <h2> Total Wallets : {{ numWallets }}</h2>
        <br>
        <br>
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
		        numWallets: 0,
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
                    .then(response=>{this.numWallets = response.data; });
                    //console.log(this.numWallets);
            }
        },
        mounted() {
			this.getNumWallets();
		}
    }
</script>