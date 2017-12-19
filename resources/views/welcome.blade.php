@extends('layouts.app')

@section('content')
    <div class="sub-nav text-center" v-cloak="">
        <router-link active-class="active" :to="{ name: 'HomePage'}" exact>首页</router-link>
        <router-link active-class="active" :to="{ name: 'TrendingPage'}">发现</router-link>
        <router-link active-class="active" :to="{ name: 'SubscriptionPage'}">我的订阅</router-link>
    </div>

    <router-view>
        {{--placeholder to show while Vue is loading on page load first time--}}
        <p class="text-center" style="padding: 2em;">
            <span class="glyphicon glyphicon-refresh spin"></span> Loading...
        </p>
    </router-view>
    <!-- set progressbar -->
    <vue-progress-bar></vue-progress-bar>
@endsection