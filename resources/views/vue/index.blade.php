@extends('master')

@section('title', 'Virtual Wallet')

@section('content')
  
<!--<router-link to="/initialpage">InitialPage</router-link> -
    <router-link to="/wallet">Wallet</router-link> -
    <router-link to="/users">Users</router-link> - 
    <router-link to="/profile">Profile</router-link> -
    <router-link to="/register">Register</router-link> -
    <router-link to="/login">Login</router-link> -
    <router-link to="/logout">Logout</router-link> -
    |<em>User: @{{this.$store.state.user != null ? this.$store.state.user.name : " - None - " }}</em>|
    <img style="border-radius: 50%"
    src="img_avatar.png" alt="Avatar">
    <hr> -->
    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vue.js"></script>
 @stop
