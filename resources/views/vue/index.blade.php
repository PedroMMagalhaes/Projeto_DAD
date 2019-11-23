@extends('master')

@section('title', 'Virtual Wallet')

@section('content')
    <router-link to="/initialpage">InitialPage</router-link> -
    <router-link to="/users">Users</router-link> - 
    <router-link to="/profile">Profile</router-link> -
    <router-link to="/login">Login</router-link> -
    <router-link to="/logout">Logout</router-link> 
    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vue.js"></script>
 @stop
